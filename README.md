# meumcp

> **MCP central para o ecossistema @CanalQb** — serve conhecimento, regras, agentes e descoberta automática de MCPs gratuitos.

**meumcp** é um servidor [Model Context Protocol (MCP)](https://modelcontextprotocol.io) que atua como fonte única de verdade para todos os agentes e MCPs do CanalQb. Ele consolida:

1. **Knowledge canonical** — regras, prompts e inventário de MCPs descobertos
2. **Rules engine** — regras LLM governamentais (`rules/canonical/llm/`)
3. **MCP Keyhunter** — agente de descoberta autônoma que busca novos MCPs gratuitos no GitHub a cada 12h

---

## 📦 Instalação

```bash
git clone https://github.com/canalqb/meumcp.git
cd mcpqb
npm install
npm run build
```

### Executar como servidor MCP (Streamable HTTP)

```bash
node dist/server.js
# Servidor escuta em http://localhost:3000
```

### Executar como MCP local (stdio)

Adicione ao `claude_desktop_config.json`:

```json
{
  "mcpServers": {
    "meumcp": {
      "command": "node",
      "args": ["<caminho-para>/mcpqb/dist/server.js"],
      "env": { "MEUMCP_LOG_LEVEL": "info" }
    }
  }
}
```

### CLI

```bash
npm run cli            # inicia CLI interativa
npx meumcp doctor      # verifica saúde do MCP
npx meumcp discover    # força uma run do keyhunter
```

---

## 🧠 Tools MCP expostas (8)

| Tool | Descrição |
|---|---|
| `get_context` | Contexto unificado: knowledge + rules + MCPs para um agente |
| `search_knowledge` | Busca full-text em knowledge canonical |
| `get_rules` | Carrega hierarchical rules (master + agent-specific) |
| `check_rule` | Verifica se uma action é permitida pela rule engine |
| `get_agent` | Identidade + configuração de um agente CanalQb |
| `list_mcps` | Lista todos os MCPs conhecidos + status HTTP |
| `get_system` | Stats do servidor (uptime, rules loaded, etc.) |
| `get_llm_rules` | Acesso direto às rules LLM canônicas |

---

## 🔍 MCP Keyhunter (12h cycle)

O **keyhunter** (`scripts/mcp_keyhunter/`) busca novos MCPs gratuitos no GitHub a cada 12h:

1. Busca via GitHub Search API (`mcp-server`, `model-context-protocol`)
2. Filtra apenas projetos **gratuitos / open-source** (MIT, Apache-2.0, BSD, GPL, ISC, Unlicense)
3. Absorve conhecimento — extrai README de cada repo
4. Valida HTTP 200 de cada repo
5. Atualiza automaticamente:
   - `knowledge/canonical/mcp_discoveries.md`
   - `knowledge/generated/mcp_registry.json`

### Cron / disparadores (12h cycle)

| Trigger | Frequência |
|---|---|
| GitHub Actions (cron UTC) | `00:00` e `12:00` |
| GitHub Actions (`workflow_dispatch`) | Manual via UI |
| Local cron (fallback) | `0 */12 * * *` |

Workflow: `.github/workflows/mcp-keyhunter.yml` — commit + push automático de atualizações.

---

## 📦 Releases (auto-gerenciados)

Cada run do keyhunter com novos MCPs gera:

- **Commit automático** `auto: update MCP discoveries [12h cycle]`
- **Release** via `gh release create keyhunter-YYYY-MM-DD`
  - Tag: `keyhunter-YYYY-MM-DD`
  - Body: lista de novos MCPs validados

> 📋 **Log de releases:** [releases do meumcp](https://github.com/canalqb/meumcp/releases)

---

## 📁 Estrutura

```
mcpqb/
├── src/
│   ├── server.ts        # MCP principal (8 tools)
│   └── cli.ts           # CLI: doctor, discover, serve
├── rules/canonical/llm/ # 10 regras LLM canônicas
├── knowledge/
│   ├── canonical/       # master_rules, mcp_creators, mcp_discoveries
│   └── generated/       # mcp_registry.json (deduplicado)
├── scripts/mcp_keyhunter/   # Sub-agente de descoberta (12h cycle)
├── .github/workflows/
│   └── mcp-keyhunter.yml    # Cron 12h + workflow_dispatch
└── tests/              # 10 jest tests
```

---

## 👨‍💻 Sobre

**meumcp** é desenvolvido por **Rodrigo "Moraes"** (@canalqb) — criador do canal @CanalQb (tecnologia, automação, cripto, scripts), com mais de 250.000 inscritos no YouTube e conteúdo no blog [canalqb.com.br](https://canalqb.com.br).

- **Canal YouTube:** [@canalqb](https://www.youtube.com/@canalqb)
- **Blog:** [canalqb.com.br](https://canalqb.com.br)
- **GitHub:** [@canalqb](https://github.com/canalqb)
- **Contato dev:** `qrodrigob@gmail.com`

> ✅ **meumcp é 100% open-source (MIT)** — parte da infraestrutura AI do CanalQb.

---

## 🧪 Desenvolvimento

```bash
npm run dev      # watch (tsx)
npm run lint     # ESLint
npm run format   # prettier
npm test         # 10 tests
```
