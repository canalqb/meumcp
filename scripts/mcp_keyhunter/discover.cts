/**
 * MCP Keyhunter — descobre novos MCPs gratuitos no GitHub (12h cycle).
 *
 * Workflow:
 * 1. Busca via GitHub Search API por MCPs (mcp-server topic, model-context-protocol)
 * 2. Filtra por licenças free/open-source (MIT, Apache-2.0, BSD, GPL, ISC, Unlicense)
 * 3. Extrai README de repos novos via gh API
 * 4. Salva knowledge/canonical/mcp_discoveries.md (auto-update do meumcp)
 * 5. Atualiza README.md do meumcp com novidades
 * 6. Cria GitHub release via gh CLI
 * 7. Deduplica via knowledge/generated/mcp_registry.json
 *
 * Ciclo: 12h (via GitHub Actions cron 00:00 UTC + 12:00 UTC).
 * Foco: apenas MCPs GRATUITOS/open-source.
 */
const fs = require('fs');
const path = require('path');
const { execSync } = require('child_process');

const ROOT = path.resolve(__dirname, '../../..');   // scripts/mcp_keyhunter -> raiz meumcp
const KNOWLEDGE_DIR = path.join(ROOT, 'knowledge');
const CANONICAL_DIR = path.join(KNOWLEDGE_DIR, 'canonical');
const GENERATED_DIR = path.join(KNOWLEDGE_DIR, 'generated');
const REGISTRY_PATH = path.join(GENERATED_DIR, 'mcp_registry.json');
const OUTPUT_MD = path.join(CANONICAL_DIR, 'mcp_discoveries.md');
const PROJECT_README = path.join(ROOT, 'README.md');

const GH_TOKEN = process.env.GITHUB_TOKEN || '';
const GH_API = 'https://api.github.com';
// Topics relacionados a MCP no GitHub
const SEARCH_QUERY = 'mcp-server OR "model-context-protocol" OR "mcp-server" topic in:name,description language:typescript,python';
const SEARCH_URL = `${GH_API}/search/repositories?q=${encodeURIComponent(SEARCH_QUERY)}&sort=updated&order=desc&per_page=50`;

// Licenças consideradas GRATUITAS / open-source
const FREE_LICENSES = new Set([
  'mit', 'apache-2.0', 'bsd-2-clause', 'bsd-3-clause', 'gpl-2.0', 'gpl-3.0',
  'lgpl-2.1', 'lgpl-3.0', 'unlicense', '0bsd', 'isc', 'mit-0', 'bsl-1.0',
  'cc0-1.0', 'mpl-2.0', 'epl-1.0', 'wtfpl', 'afl-3.0', 'artistic-2.0',
]);

const PAID_LICENSES = new Set([
  'agpl-3.0', 'agpl-4.0', 'commercial', 'proprietary', 'enterprise'
]);

function ghRequest(url) {
  /** @type {any} */
  const headers = { Accept: 'application/vnd.github+json', 'X-GitHub-Api-Version': '2022-11-28' };
  if (GH_TOKEN) headers.Authorization = `Bearer ${GH_TOKEN}`;
  return fetch(url, { headers }).then(async (res) => {
    const remaining = res.headers.get('x-ratelimit-remaining');
    if (remaining !== null) process.stderr.write(`[keyhunter] GH rate limit remaining: ${remaining}\n`);
    if (!res.ok) {
      const txt = (await res.text()).slice(0, 150);
      throw new Error(`GitHub API ${res.status} for ${url}: ${txt}`);
    }
    return res.json();
  });
}

async function fetchNewRepos(sinceId) {
  const data = await ghRequest(SEARCH_URL);
  let items = data.items || [];
  if (sinceId) items = items.filter((r) => r.id > sinceId);
  return items;
}

async function fetchRepoDetails(fullName) {
  try {
    return await ghRequest(`${GH_API}/repos/${fullName}`);
  } catch {
    return null;
  }
}

async function fetchReadme(fullName) {
  try {
    const res = await fetch(`${GH_API}/repos/${fullName}/readme`, {
      headers: { Accept: 'application/vnd.github.raw+json', Authorization: GH_TOKEN ? `Bearer ${GH_TOKEN}` : '' }
    });
    if (!res.ok) return '';
    return (await res.text()).slice(0, 3000);
  } catch {
    return '';
  }
}

async function checkHttp(url) {
  try {
    const res = await fetch(url, { method: 'HEAD', redirect: 'follow' });
    return { status: res.status, ok: res.ok };
  } catch {
    return { status: 0, ok: false };
  }
}

function loadRegistry() {
  if (!fs.existsSync(REGISTRY_PATH)) return [];
  const raw = fs.readFileSync(REGISTRY_PATH, 'utf-8');
  return JSON.parse(raw);
}

function saveRegistry(records) {
  fs.mkdirSync(GENERATED_DIR, { recursive: true });
  fs.writeFileSync(REGISTRY_PATH, JSON.stringify(records, null, 2));
}

function isFreeLicense(licenseSpdx) {
  if (!licenseSpdx) return true; // sem licença = assumir permissivo (repo público)
  const key = licenseSpdx.toLowerCase().replace(/-.*/, '');
  return FREE_LICENSES.has(licenseSpdx.toLowerCase()) || FREE_LICENSES.has(key);
}

// Absorve conhecimento do README via GitHub Copilot (não-interativo via gh api)
function absorbWithCopilot(readmeContent, repoName) {
  // Nota: gh copilot é interativo. Aqui usamos gh api + processamento local.
  // Para integração real com Copilot, use `gh copilot` via stdin (modo CLI).
  // Este stub loga a absorção — o knowledge é processado localmente.
  if (!readmeContent || readmeContent.length < 80) return null;

  console.log(`[keyhunter] Absorvendo conhecimento de ${repoName} (${readmeContent.length} chars de README)`);
  // Extrai seção de descrição + features via regex
  /** @type {any} */
  const result = { repo: repoName, readme_len: readmeContent.length };
  return result;
}

function updateProjectReadme(newEntries) {
  const readmePath = PROJECT_README;
  if (!fs.existsSync(readmePath)) {
    console.log('[keyhunter] README.md não encontrado — criando.');
    fs.writeFileSync(readmePath, '# meumcp\n\n');
  }
  let readme = fs.readFileSync(readmePath, 'utf-8');

  // Atualiza seção de MCPs descobertos no README
  const sectionHeader = '## MCPs Descoveltos pelo Keyhunter';
  if (!readme.includes(sectionHeader)) {
    readme += `\n${sectionHeader}\n\n`;
    readme += `| Data | Repositório | Stars | License | HTTP |\n|---|---|---|---|---|\n`;
  }

  // Insere novas entradas
  const lines = readme.split('\n');
  const sectionIdx = lines.findIndex((l) => l.includes(sectionHeader));
  if (sectionIdx !== -1) {
    let insertIdx = sectionIdx + 2;
    // pula header + divisória
    while (insertIdx < lines.length && lines[insertIdx].startsWith('| ---')) insertIdx++;
    for (const e of newEntries) {
      const line = `| ${e.validated_at} | ${e.full_name} | ${e.stars} | ${e.license} | ${e.http_status} |\n`;
      lines.splice(insertIdx, 0, line);
      insertIdx++;
    }
  }
  fs.writeFileSync(readmePath, lines.join('\n'));
  console.log(`[keyhunter] README.md atualizado com ${newEntries.length} nova(s) entrada(s).`);
}

async function createRelease(newEntries) {
  if (newEntries.length === 0) {
    console.log('[keyhunter] Nenhum MCP novo — nenhum release criado.');
    return;
  }
  const date = new Date().toISOString().slice(0, 10);
  const tagName = `keyhunter-${date}`;
  const names = newEntries.map((e) => e.full_name).join(', ');
  const body = `## MCPs descobertos automaticamente pelo keyhunter

- Total novos (free/open-source): ${newEntries.length}
- Repositórios: ${names}

Gerado automaticamente via GitHub Actions (12h cycle).
`;

  try {
    execSync(`gh release create ${tagName} --title "Keyhunter ${date}" --notes "${body.replace(/"/g, '\\"')}"`, {
      stdio: 'pipe',
      cwd: ROOT,
    });
    console.log(`[keyhunter] Release criado: ${tagName}`);
  } catch (e) {
    console.log(`[keyhunter] Release já existe ou falhou: ${tagName} — (${/** @type {any} */(e).message.slice(0,100)})`);
  }
}

async function main() {
  console.log('[keyhunter] === INICIANDO RUN (12h cycle) ===');
  fs.mkdirSync(GENERATED_DIR, { recursive: true });
  fs.mkdirSync(CANONICAL_DIR, { recursive: true });

  const registry = loadRegistry();
  const knownIds = new Set(registry.map((r) => r.id));
  console.log(`[keyhunter] Registry carregado: ${registry.length} MCPs previamente validados.`);

  const ids = Array.from(knownIds);
  const maxId = ids.length > 0 ? Math.max(.../** @type {number[]} */ (ids)) : 0;
  const repos = /** @type {Array<any>} */ await fetchNewRepos(maxId);
  const newRepos = repos.filter((r) => !knownIds.has(r.id));
  console.log(`[keyhunter] ${newRepos.length} novos repositórios encontrados (de ${repos.length} fetched).`);

  let added = 0;
  const newEntries = [];
  for (const repo of newRepos.slice(0, 30)) {
    const details = await fetchRepoDetails(repo.full_name).catch(() => null);
    const licenseKey = details?.license?.spdx_id || details?.license?.key || 'unknown';
    const isFree = isFreeLicense(licenseKey);
    const http = await checkHttp(repo.html_url);

    const readme = details ? await fetchReadme(repo.full_name).catch(() => '') : '';
    const absorbed = absorbWithCopilot(readme, repo.full_name);

    const record = {
      id: repo.id,
      full_name: repo.full_name,
      url: repo.html_url,
      language: repo.language || 'Unknown',
      stars: repo.stargazers_count,
      license: licenseKey,
      license_free: isFree,
      http_status: http.status,
      validated_at: new Date().toISOString(),
      topics: repo.topics || details?.topics || [],
      readme_absorbed: absorbed ? absorbed.repo : null,
    };
    registry.push(record);
    if (http.ok && isFree) added++;
    if (isFree && http.ok) newEntries.push(record);

    const tag = isFree ? 'FREE' : 'SKIPPED(paid)';
    console.log(`[keyhunter] + ${repo.full_name} [${http.status}] ${tag} lang=${repo.language} stars=${repo.stargazers_count} lic=${licenseKey}`);
  }

  saveRegistry(registry);

  // Regenera knowledge canonical markdown
  const now = new Date().toISOString().slice(0, 10);
  const valid = registry.filter((r) => r.http_status === 200 && r.license_free);
  const broken = registry.filter((r) => r.http_status !== 200);
  const skipped = registry.filter((r) => !r.license_free);

  let md = `---
id: mcp_discoveries
title: Descobertas MCP - Keyhunter GitHub
category: mcp-tools
description: MCPs descobertos pelo keyhunter (${registry.length} total, ${valid.length} validados free, ${skipped.length} pulados/pago)
priority: 85
scope: global
tags: [mcp, discovery, github, automated, free, open-source]
author: meumcp-keyhunter
version: ${registry.length}
createdAt: ${now}
updatedAt: ${now}
---

# Descobertas MCP — Keyhunter GitHub (12h cycle)

> Última execução: ${new Date().toISOString()}
> Total descoberto: ${registry.length} | Validados (HTTP 200 + free): ${valid.length} | Pulados (licença paga/enterprise): ${skipped.length}

## MCPs descobertos (gratuitos + validados HTTP 200)

| Repositório | Linguagem | Estrelas | License | Tópicos | Link |
|---|---|---|---|---|---|
`;
  for (const r of valid) {
    md += `| ${r.full_name} | ${r.language} | ${r.stars} | ${r.license} | ${(r.topics || []).slice(0, 3).join(', ')} | [repo](${r.url}) |\n`;
  }
  md += `\n## Projetos pulados (licenças não-free / enterprise)\n\n`;
  for (const r of skipped) {
    md += `- ${r.full_name} — licença: ${r.license}\n`;
  }
  if (skipped.length === 0) md += '(nenhum)\n';
  md += `\n## MCPs inválidos (HTTP != 200)\n\n`;
  for (const r of broken) {
    md += `- ${r.full_name} — HTTP ${r.http_status}\n`;
  }
  if (broken.length === 0) md += '(nenhum)\n';

  fs.writeFileSync(OUTPUT_MD, md);
  console.log(`[keyhunter] knowledge/canonical/mcp_discoveries.md atualizado (${registry.length} entries, ${valid.length} free-validados).`);

  // Atualiza README.md do projeto
  if (newEntries.length > 0) updateProjectReadme(newEntries);

  // Cria release
  await createRelease(newEntries);

  console.log('[keyhunter] === RUN CONCLUIDO ===');
}

main().catch((err) => {
  console.error('[keyhunter] FATAL:', err.message);
  process.exit(1);
});
