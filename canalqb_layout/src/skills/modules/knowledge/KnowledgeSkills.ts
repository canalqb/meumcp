import { SkillExecutor } from '../../ManifestSchema.js';

// Filesystem persistence is Node-only; in the browser we fall back to
// in-memory storage so the skills (including Memory) work in both contexts.
const isBrowser =
  typeof window !== 'undefined' &&
  typeof window.document !== 'undefined';

// Lazy-load Node fs/path only when not in a browser context.
let _fsLib: {
  existsSync: (p: string) => boolean;
  mkdirSync: (p: string, o?: { recursive: boolean }) => void;
  readFileSync: (p: string, enc: string) => string;
  writeFileSync: (p: string, data: string, enc: string) => void;
} | null = null;

let _pathLib: { dirname: (p: string) => string; join: (...p: string[]) => string } | null = null;
let _memDir: string | null = null;

function getFs() {
  if (_fsLib) return _fsLib;
  if (isBrowser) return null;
  // Only runs in Node (test/run mode), never in browser bundle
  try {
    const fs = require('node:fs');
    const path = require('node:path');
    _fsLib = {
      existsSync: (p: string) => fs.existsSync(p),
      mkdirSync: (p: string, o?: { recursive: boolean }) => fs.mkdirSync(p, o),
      readFileSync: (p: string, enc: string) => fs.readFileSync(p, enc),
      writeFileSync: (p: string, data: string, enc: string) => fs.writeFileSync(p, data, enc),
    };
    _pathLib = {
      dirname: (p: string) => path.dirname(p),
      join: (...p: string[]) => path.join(...p),
    };
    // __dirname equivalent for ESM — try import.meta.url, fallback to process.cwd()
    const urlMod = require('url');
    const metaUrl: string | undefined = (globalThis as Record<string, unknown>).__importMetaUrl as string | undefined;
    let urlVal = metaUrl || 'file://';
    try {
      // Try to get the real ESM URL via Function constructor (avoids eval)
      const getMeta = new Function('return (function(){ try { return import.meta.url } catch { return undefined } })()');
      const val = getMeta();
      if (val) urlVal = val;
    } catch { /* browser/no-meta */ }
    _memDir = path.join(path.dirname(urlMod.fileURLToPath(urlVal)), '../../../knowledge');
    return _fsLib;
  } catch {
    return null;
  }
}

function getMemFile(): string | null {
  getFs(); // ensure fs loaded
  if (!_memDir) return null;
  return _pathLib ? _pathLib.join(_memDir, 'memoria.json') : null;
}

const TIMEOUT_MS = 20000;

function esc(v: string): string {
  return v.replace(/\\/g, '\\\\').replace(/"/g, '\\"').replace(/\n/g, ' ');
}

async function httpJson(url: string): Promise<Record<string, unknown>> {
  const ctrl = new AbortController();
  const t = setTimeout(() => ctrl.abort(), TIMEOUT_MS);
  try {
    const res = await fetch(url, {
      signal: ctrl.signal,
      headers: { 'User-Agent': 'BorisPet/1.0 (knowledge skill)' }
    });
    if (!res.ok) throw new Error(`HTTP ${res.status}`);
    return (await res.json()) as Record<string, unknown>;
  } finally {
    clearTimeout(t);
  }
}

function loadMem(): Record<string, Array<{ value: string; ts: string }>> {
  const fs = getFs();
  if (!fs) return {}; // browser: in-memory only
  const f = getMemFile();
  if (!f) return {};
  try {
    return fs.existsSync(f) ? JSON.parse(fs.readFileSync(f, 'utf-8')) : {};
  } catch {
    return {};
  }
}

function saveMem(mem: Record<string, Array<{ value: string; ts: string }>>): void {
  const fs = getFs();
  if (!fs) return; // browser: in-memory only — memory lost on refresh
  const f = getMemFile();
  if (!f || !_memDir) return;
  fs.mkdirSync(_memDir, { recursive: true });
  fs.writeFileSync(f, JSON.stringify(mem, null, 2), 'utf-8');
}

/**
 * Consulta a base de conhecimento do Wikidata via SPARQL
 * (endpoint publico e gratuito: https://query.wikidata.org).
 */
export const KnowledgeWikidataSkill: SkillExecutor = {
  manifest: {
    id: 'knowledge_wikidata',
    name: 'Wikidata Knowledge (SPARQL)',
    version: '1.0.0',
    description: 'Consulta o Wikidata (SPARQL) para responder fatos: o que e, quando, onde, quem. Conhecimento factual gratuito.',
    permissions: [{ name: 'INTERNET', required: true }],
    inputSchema: {
      query: 'string (termo ou pergunta a pesquisar)',
      lang: 'string (idioma das respostas, padrao pt)'
    }
  },
  async execute(args) {
    const term = String(args.query || '').trim();
    if (!term) return { success: false, error: 'Informe o termo a pesquisar (query).' };
    const lang = String(args.lang || 'pt').trim() || 'pt';

    const searchUrl =
      'https://www.wikidata.org/w/api.php?action=wbsearchentities' +
      '&search=' + encodeURIComponent(term) +
      '&language=' + encodeURIComponent(lang) +
      '&uselang=' + encodeURIComponent(lang) +
      '&format=json&limit=5';

    const hit = await (async (): Promise<{ id: string; label: string; desc: string } | undefined> => {
      try {
        const data = (await httpJson(searchUrl)) as {
          search?: Array<{ id: string; label?: string; description?: string }>;
        };
        const first = (data.search || [])[0];
        if (!first) return undefined;
        return { id: first.id, label: first.label || '', desc: first.description || '' };
      } catch {
        return undefined;
      }
    })();

    if (hit) {
      return {
        success: true,
        result: {
          entidade: `${hit.label} (${hit.id})`,
          descricao: hit.desc || '(sem descrição)',
          fonte: `https://www.wikidata.org/wiki/${hit.id}`
        }
      };
    }

    const sparql =
      'SELECT ?itemLabel ?desc WHERE { ' +
      '  ?item rdfs:label ?label . ' +
      '  OPTIONAL { ?item schema:description ?desc . FILTER(LANG(?desc) = "' + esc(lang) + '") } ' +
      '  FILTER(LANG(?label) = "' + esc(lang) + '") ' +
      '  FILTER(CONTAINS(LCASE(?label), LCASE("' + esc(term) + '"))) ' +
      '} LIMIT 5';
    const url =
      'https://query.wikidata.org/sparql?query=' + encodeURIComponent(sparql) + '&format=json';
    try {
      const data = (await httpJson(url)) as {
        results?: { bindings?: Array<Record<string, { value: string }>> };
      };
      const rows = (data.results?.bindings || []).map((b) => ({
        entidade: b.itemLabel?.value || '(sem label)',
        descricao: b.desc?.value || '(sem descrição)'
      }));
      return {
        success: true,
        result: rows.length ? rows : `Nada encontrado no Wikidata para "${term}" (idioma ${lang}).`
      };
    } catch (e) {
      const msg = e instanceof Error ? e.message : String(e);
      return { success: false, error: `Erro na consulta Wikidata: ${msg}` };
    }
  }
};

/**
 * Busca web leve via DuckDuckGo Instant Answer (sem chave).
 * Bom para "o que e X"; para busca ampla usar o MCP ddg-search/searxng.
 */
export const KnowledgeSearchSkill: SkillExecutor = {
  manifest: {
    id: 'knowledge_search',
    name: 'Web Search (DuckDuckGo)',
    version: '1.0.0',
    description: 'Busca resumo web (DuckDuckGo Instant Answer) para termos e entidades. Gratuito, sem chave.',
    permissions: [{ name: 'INTERNET', required: true }],
    inputSchema: {
      query: 'string (o que procurar)'
    }
  },
  async execute(args) {
    const q = String(args.query || '').trim();
    if (!q) return { success: false, error: 'Informe a query.' };
    try {
      const data = (await httpJson(
        'https://api.duckduckgo.com/?q=' +
          encodeURIComponent(q) +
          '&format=json&no_html=1&skip_disambig=1'
      )) as {
        AbstractText?: string;
        AbstractURL?: string;
        RelatedTopics?: Array<{ Text?: string; FirstURL?: string; Topics?: Array<{ Text: string; FirstURL?: string }> }>;
      };
      const out: Array<{ tipo: string; texto: string; url?: string }> = [];
      if (data.AbstractText) {
        out.push({ tipo: 'resumo', texto: data.AbstractText, url: data.AbstractURL });
      }
      for (const r of data.RelatedTopics || []) {
        if (r.Text) out.push({ tipo: 'relacionado', texto: r.Text, url: r.FirstURL });
        else if (r.Topics) {
          for (const t of r.Topics) {
            if (t.Text) out.push({ tipo: 'relacionado', texto: t.Text, url: t.FirstURL });
          }
        }
      }
      return {
        success: true,
        result: out.length ? out.slice(0, 8) : `Sem resultado instantaneo para "${q}". Usar MCP ddg-search para busca ampla.`
      };
    } catch (e) {
      const msg = e instanceof Error ? e.message : String(e);
      return { success: false, error: `Erro na busca DuckDuckGo: ${msg}` };
    }
  }
};

/**
 * Busca e extrai texto de uma URL (para leitura de páginas/sites).
 */
export const KnowledgeFetchSkill: SkillExecutor = {
  manifest: {
    id: 'knowledge_fetch',
    name: 'Fetch Web Page',
    version: '1.0.0',
    description: 'Baixa uma pagina web e devolve o texto limpo (HTML removido).',
    permissions: [{ name: 'INTERNET', required: true }],
    inputSchema: {
      url: 'string (URL http/https)'
    }
  },
  async execute(args) {
    const url = String(args.url || '').trim();
    if (!/^https?:\/\//i.test(url)) return { success: false, error: 'URL inválida (use http/https).' };
    try {
      const ctrl = new AbortController();
      const t = setTimeout(() => ctrl.abort(), TIMEOUT_MS);
      const res = await fetch(url, { signal: ctrl.signal, headers: { 'User-Agent': 'BorisPet/1.0' } });
      clearTimeout(t);
      if (!res.ok) return { success: false, error: `HTTP ${res.status} ao buscar ${url}` };
      const html = await res.text();
      const plain = html
        .replace(/<script[\s\S]*?<\/script>/gi, ' ')
        .replace(/<style[\s\S]*?<\/style>/gi, ' ')
        .replace(/<[^>]+>/g, ' ')
        .replace(/\s+/g, ' ')
        .trim();
      const truncated = plain.length > 4000;
      return {
        success: true,
        result: (truncated ? plain.slice(0, 4000) + ' …(truncado)' : plain) || '(página sem texto)'
      };
    } catch (e) {
      const msg = e instanceof Error ? e.message : String(e);
      return { success: false, error: `Erro ao buscar ${url}: ${msg}` };
    }
  }
};

/**
 * Hora/data em qualquer fuso (sem rede).
 */
export const KnowledgeTimeSkill: SkillExecutor = {
  manifest: {
    id: 'knowledge_time',
    name: 'Date & Time',
    version: '1.0.0',
    description: 'Informa data/hora atual em um fuso horário.',
    permissions: [],
    inputSchema: {
      timezone: 'string (ex.: America/Sao_Paulo; padrao = fuso local)'
    }
  },
  async execute(args) {
    const tz = String(args.timezone || '').trim() || Intl.DateTimeFormat().resolvedOptions().timeZone;
    try {
      const fmt = new Intl.DateTimeFormat('pt-BR', { timeZone: tz, dateStyle: 'full', timeStyle: 'medium' });
      return { success: true, result: `Agora são ${fmt.format(new Date())} (fuso ${tz}).` };
    } catch (e) {
      const msg = e instanceof Error ? e.message : String(e);
      return { success: false, error: `Fuso inválido "${tz}": ${msg}` };
    }
  }
};

/**
 * Memória persistente dos pets em arquivo (src/knowledge/memoria.json).
 * No browser, usa memória volátil (in-memory).
 */
const _memCache: Record<string, Array<{ value: string; ts: string }>> = {};

export const KnowledgeMemorySkill: SkillExecutor = {
  manifest: {
    id: 'knowledge_memory',
    name: 'Pet Knowledge Memory',
    version: '1.0.0',
    description: 'Grava e lê memórias do pet (preferências, fatos sobre o dono, aprendizado) em arquivo local.',
    permissions: [],
    inputSchema: {
      action: 'string (add | read)',
      key: 'string (categoria, ex.: dono, preferencias, fatos)',
      value: 'string (obrigatorio em add)'
    }
  },
  async execute(args) {
    const action = String(args.action || 'read').trim();
    const key = String(args.key || '').trim();
    if (action === 'add') {
      const value = String(args.value || '').trim();
      if (!key || !value) return { success: false, error: 'add exige key e value.' };
      const mem = { ..._memCache, ...loadMem() };
      if (!mem[key]) mem[key] = [];
      mem[key].push({ value, ts: new Date().toISOString() });
      _memCache[key] = mem[key];
      saveMem(mem);
      return { success: true, result: `Memória salva em '${key}' (${mem[key].length} registro(s)).` };
    }
    if (!key) return { success: false, error: 'read exige key.' };
    // Merge disk + cache
    const disk = loadMem();
    const cached = { ...disk };
    if (_memCache[key]) cached[key] = _memCache[key];
    const items = cached[key] || [];
    return { success: true, result: items.length ? items : `Nada guardado ainda sob '${key}'.` };
  }
};
