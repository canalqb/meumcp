# Qb Nexus

> **Qb Nexus — Ponto de conexão entre agentes, conhecimento e MCPs**

> Qb Nexus é a camada central de inteligência para o CanalQb — um *nexus* entre agentes, conhecimento, regras, agentes e MCPs descobertos.

**Qb Nexus** é um servidor [Model Context Protocol (MCP)](https://modelcontextprotocol.io) que unifica:

1. **Knowledge canonical** — regras, prompts e inventário de MCPs descobertos
2. **Rules engine** — diretrizes LLM governamentais
3. **MCP Keyhunter** — agente de busca autônoma que descobre novos MCPs free/open-source no GitHub a cada **12h**

---

## 📦 Instalação

### 1. Pré-requisitos

- Node.js >= 20
- npm 10+
- Chave GitHub token (para keyhunter): `gh auth login`

```bash
gh auth login
```

### 2. Clone + instale

```bash
git clone https://github.com/canalqb/meumcp.git
cd meumcp
npm install
npm run build
```

### 3. Inicie o MCP

**Servidor stdio (recomendado para Claude Desktop):**

```bash
node dist/server.js
```

**Servidor HTTP (Streamable HTTP):**

```bash
npm run serve:http
# Porta padrão: http://localhost:3000
```

### 4. Adicionar ao seu cliente MCP

#### Claude Desktop

Adicione ao `claude_desktop_config.json`:

```json
{
  "mcpServers": {
    "meumcp": {
      "command": "node",
      "args": ["<caminho>/meumcp/dist/server.js"],
      "env": {
        "MEUMCP_CLI": "true",
        "GITHUB_TOKEN": "<seu-token-aqui>"
      }
    }
  }
}
```

#### Cursor / Windsurf / Claude Code

```json
{
  "mcp": {
    "meumcp": {
      "type": "stdio",
      "command": "node",
      "args": ["<caminho>/meumcp/dist/server.js"]
    }
  }
}
```

### 5. Verifique a instalação

```bash
meumcp doctor
# Output esperado:
#   Node.js: ✓ v20+
#   MCP SDK: ✓ installed
#   Knowledge: ✓ N entries
#   Rules: ✓ N rules
#   MCPs found: ✓ N
```

---

## 🎮 Primeira execução (CLI)

```bash
npm run cli      # inicia CLI interativa
```

### Comandos CLI

| Comando | Descrição |
|---|---|
| `meumcp init` | Inicializa a estrutura de pastas do projeto |
| `meumcp discover` | Lista todos MCPs encontrados na máquina |
| `meumcp mcps` | Lista MCPs com status (enabled/disabled) |
| `meumcp knowledge -q "prompt"` | Busca conhecimento |
| `meumcp rules` | Lista regras LLM |
| `meumcp ingest` | Importa Google Docs + regras locais |
| `meumcp install` | Ativa/desativa MCPs via caixa de seleção |
| `meumcp serve` | Inicia servidor MCP (stdio) |
| `meumcp doctor` | Valida instalação + configuração |

---

## 🧠 Tools MCP expostas (8)

| Tool | Descrição |
|---|---|
| `get_context` | Contexto unificado: knowledge + rules + MCPs |
| `search_knowledge` | Busca full-text em knowledge canonical |
| `get_rules` | Carrega hierarchical rules (master + agent-specific) |
| `check_rule` | Verifica se uma action é permitida |
| `get_agent` | Identidade + configuração de um agente |
| `list_mcps` | Lista todos os MCPs conhecidos + status HTTP |
| `get_system` | Stats do servidor (uptime, etc.) |
| `get_llm_rules` | Rules LLM canônicas |

---

## 🔍 MCP Keyhunter (12h cycle)

O **keyhunter** (`scripts/mcp_keyhunter/`) busca novos MCPs gratuitos no GitHub a cada 12h:

1. Busca via GitHub Search API
2. **Filtra apenas licenças free/open-source**: MIT, Apache-2.0, BSD, GPL, ISC, Unlicense
3. Absorve conhecimento (extrai README de cada repo)
4. **Filtra licenças pagas**: agpl/comercial/proprietary são **pulados**
5. Valida HTTP 200 de cada repo
6. Atualiza knowledge canonical + registry

> ✅ Apenas tecnologias com licença free/open-source são absorvidas.
> ❌ Repos com licença enterprise, commercial ou proprietary são **ignorados**.

### Cron (12h cycle)

| Trigger | Frequência |
|---|---|
| GitHub Actions (cron UTC) | `0 0,12 * * *` |
| GitHub Actions (manual) | `gh workflow run mcp-keyhunter.yml` |
| Local cron (fallback) | `0 0,12 * * *` |

Workflow: `.github/workflows/mcp-keyhunter.yml`

---

## 🔄 Update detection (nova sessão)

Em **cada nova sessão**, o meumcp verifica se o keyhunter validou novos MCPs. Se houver novidades, o sistema informa:

```
[meumcp] ✨ 3 novos MCPs free foram descobertos pelo keyhunter!
  - github: /repos/owner/awesome-mcp — MIT, 12 estrelas
  - github: /repos/owner/another-mcp — Apache-2.0, 8 estrelas
  → Execute 'meumcp install' para ativar.
```

---

## ⚙️ Relatório de instalação de MCPs

A command `meumcp install` apresenta uma **caixa de seleção interativa** para ativar MCPs:

```
────────────────────────────────────
  Selecione os MCPs para ativar:
────────────────────────────────────
  ☐─ filesystem-server   MIT      450 stars   [HTTP 200]
  ☑─ git-mcp-server      Apache   220 stars   [HTTP 200]  ✓ ativado
  ☐─ sqlite-mcp          BSD-3    1.1k stars  [HTTP 200]
  ☐─ brave-search-mcp      -      340 stars   [HTTP 200]
────────────────────────────────────
  [↑/↓ navegar] [espaço selecionar] [enter confirmar]
────────────────────────────────────

> Relatório de instalação:
  • 3 MCPs ativados
  • 1 MCP já instalado (não repetido)
  • 0 erros de configuração
```

---

## 📦 Releases (auto-gerenciados)

Cada run do keyhunter com novos MCPs gera:

- **Commit automático** `auto: update MCP discoveries [12h cycle]`
- **Release** `keyhunter-YYYY-MM-DD` via `gh release create`

> 📋 **Log de releases:** [releases do meumcp](https://github.com/canalqb/meumcp/releases)

---

## 📁 Estrutura

```
meumcp/
├── src/
│   ├── server.ts         # MCP principal (8 tools + install + update-check)
│   └── cli.ts            # CLI interativa: doctor, discover, install
├── rules/canonical/      # 10 regras LLM canônicas
├── knowledge/
│   ├── canonical/        # master_rules, mcp_creators, mcp_discoveries
│   └── generated/
│       └── mcp_registry.json   # MCPs validados (deduplicado)
├── scripts/mcp_keyhunter/      # Sub-agente de descoberta (12h cycle)
├── .github/workflows/
│   └── mcp-keyhunter.yml       # Cron 12h + workflow_dispatch
└── tests/                      # 10 jest tests
```

---

## 🧪 Desenvolvimento

```bash
npm run dev      # watch (tsx)
npm run lint     # ESLint
npm run format   # prettier
npm test         # 10 tests
```

---

## 🪪 Licença

MIT — parte da infraestrutura AI do @CanalQb.

## MCPs Descoveltos pelo Keyhunter

| 2026-08-17T00:57:27.598Z | sergiolmendonca/topicos-app-2026-2 | 0 | MIT | 200 |

| 2026-08-17T00:57:29.823Z | willianpripp/repo-hygiene | 0 | MIT | 200 |

| 2026-08-16T12:28:27.723Z | MefeGuven/Interactive-Hodgkin-Huxley-Simulator | 0 | MIT | 200 |

| 2026-08-15T12:27:16.130Z | fatima-890/AI-Keyword-Research-Tool | 0 | MIT | 200 |

| 2026-08-15T12:27:19.153Z | cyberscaleio/topical-authority-checker | 0 | MIT | 200 |

| 2026-08-14T12:54:12.201Z | 6kongbai/dsh-plugin-market | 1 | MIT | 200 |

| 2026-08-14T07:15:38.543Z | huangzhimin4read/CodexFeishu | 0 | MIT | 200 |

| 2026-08-14T07:15:40.331Z | chaoyibot/ai-poster-generator | 0 | MIT | 200 |

| 2026-08-14T04:01:06.017Z | TheYoungChen/dsh-plugin-market | 1 | MIT | 200 |

| 2026-08-14T04:01:07.965Z | awesome-dsh-plugin/dsh-find-plugin | 3 | MIT | 200 |

| 2026-08-14T01:21:43.024Z | SeanHollen/wcfed | 0 | MIT | 200 |

| 2026-08-14T01:21:43.781Z | j86schroeder/tree-pad-2.0 | 1 | MIT | 200 |

| 2026-08-14T01:21:44.730Z | EricUfomadu/fashion-reliability | 0 | MIT | 200 |

| 2026-08-13T18:54:31.520Z | Jesse-njx/dsh-plugin-manager-registry | 0 | MIT | 200 |

| 2026-08-13T18:54:33.352Z | fatihsoysalcom/simple-command-line-note-manager | 0 | MIT | 200 |

| 2026-08-13T16:50:26.506Z | bobleer/deepseek-harness-plugin-mcp | 1 | MIT | 200 |

| 2026-08-13T16:50:27.454Z | voronindenis5/screenshot-organizer | 0 | MIT | 200 |

| 2026-08-13T14:52:02.004Z | rook-builds/arxivsnap | 0 | MIT | 200 |

| 2026-08-13T14:52:03.130Z | MatheusOliveiraSilva/topic-spin | 0 | MIT | 200 |

| 2026-08-13T14:52:04.426Z | Subrata-CS/open | 0 | MIT | 200 |

| 2026-08-13T12:55:44.438Z | mansha5/app-review-analyst | 0 | MIT | 200 |

| 2026-08-13T01:53:47.832Z | data-scrape/google-trends-scraper | 0 | MIT | 200 |

| 2026-08-12T21:48:17.525Z | Sohila-Khaled-Abbas/ride-sharing-kafka-pipeline | 0 | MIT | 200 |

| 2026-08-12T20:50:23.886Z | laurasolares811-creator/Horizon | 0 | MIT | 200 |

| 2026-08-12T20:50:27.421Z | rjriajul/wzgram | 28 | LGPL-3.0 | 200 |

| 2026-08-12T20:50:28.677Z | alxxtexxr/arxiv_agent | 0 | MIT | 200 |

| 2026-08-12T20:50:36.795Z | CaryNC-IP/carync-ip.github.io | 0 | MIT | 200 |

| 2026-08-12T20:50:37.888Z | KurimuzonAkuma/kurigram | 794 | LGPL-3.0 | 200 |

| 2026-08-12T20:50:40.370Z | Kalwmw/Zip-Password-Recovery | 4 | MIT | 200 |

| 2026-08-12T20:50:46.139Z | Glyxehon/Blum-Crypto-Trading-P2P-Chain-Exchange | 0 | MIT | 200 |

| 2026-08-12T20:50:50.743Z | hyeonseo2/finfluencer-dashboard | 2 | MIT | 200 |

| 2026-08-12T20:50:53.134Z | jcmaker/just-study | 1 | MIT | 200 |

| 2026-08-12T20:50:55.830Z | Xiezhou0828/topicpilot-platform | 0 | MIT | 200 |

| 2026-08-12T20:42:10.998Z | rjriajul/wzgram | 28 | LGPL-3.0 | 200 |

| 2026-08-12T20:42:12.049Z | alxxtexxr/arxiv_agent | 0 | MIT | 200 |

| 2026-08-12T20:42:20.294Z | CaryNC-IP/carync-ip.github.io | 0 | MIT | 200 |

| 2026-08-12T20:42:21.336Z | KurimuzonAkuma/kurigram | 794 | LGPL-3.0 | 200 |

| 2026-08-12T20:42:23.646Z | Kalwmw/Zip-Password-Recovery | 4 | MIT | 200 |

| 2026-08-12T20:42:30.790Z | Glyxehon/Blum-Crypto-Trading-P2P-Chain-Exchange | 0 | MIT | 200 |

| 2026-08-12T20:42:36.485Z | hyeonseo2/finfluencer-dashboard | 2 | MIT | 200 |

| 2026-08-12T20:42:38.908Z | jcmaker/just-study | 1 | MIT | 200 |

| 2026-08-12T20:42:41.108Z | Xiezhou0828/topicpilot-platform | 0 | MIT | 200 |

| 2026-08-12T20:42:44.458Z | AvaProtocol/protocols | 0 | MIT | 200 |

| 2026-08-12T20:40:32.134Z | rjriajul/wzgram | 28 | LGPL-3.0 | 200 |

| 2026-08-12T20:40:33.410Z | alxxtexxr/arxiv_agent | 0 | MIT | 200 |

| 2026-08-12T20:40:42.136Z | CaryNC-IP/carync-ip.github.io | 0 | MIT | 200 |

| 2026-08-12T20:40:43.310Z | KurimuzonAkuma/kurigram | 794 | LGPL-3.0 | 200 |

| 2026-08-12T20:40:46.007Z | Kalwmw/Zip-Password-Recovery | 4 | MIT | 200 |

| 2026-08-12T20:40:54.152Z | Glyxehon/Blum-Crypto-Trading-P2P-Chain-Exchange | 0 | MIT | 200 |

| 2026-08-12T20:40:58.884Z | hyeonseo2/finfluencer-dashboard | 2 | MIT | 200 |

| 2026-08-12T20:41:01.104Z | jcmaker/just-study | 1 | MIT | 200 |

| 2026-08-12T20:41:03.461Z | Xiezhou0828/topicpilot-platform | 0 | MIT | 200 |

| 2026-08-12T20:41:07.210Z | AvaProtocol/protocols | 0 | MIT | 200 |

| 2026-08-12T20:34:15.075Z | alxxtexxr/arxiv_agent | 0 | MIT | 200 |

| 2026-08-12T20:34:23.127Z | CaryNC-IP/carync-ip.github.io | 0 | MIT | 200 |

| 2026-08-12T20:34:24.315Z | KurimuzonAkuma/kurigram | 794 | LGPL-3.0 | 200 |

| 2026-08-12T20:34:26.945Z | Kalwmw/Zip-Password-Recovery | 4 | MIT | 200 |

| 2026-08-12T20:34:36.011Z | Glyxehon/Blum-Crypto-Trading-P2P-Chain-Exchange | 0 | MIT | 200 |

| 2026-08-12T20:34:42.019Z | hyeonseo2/finfluencer-dashboard | 2 | MIT | 200 |

| 2026-08-12T20:34:44.378Z | jcmaker/just-study | 1 | MIT | 200 |

| 2026-08-12T20:34:47.377Z | Xiezhou0828/topicpilot-platform | 0 | MIT | 200 |

| 2026-08-12T20:34:50.910Z | AvaProtocol/protocols | 0 | MIT | 200 |

| 2026-08-12T20:31:16.430Z | CaryNC-IP/carync-ip.github.io | 0 | MIT | 200 |

| 2026-08-12T20:31:17.668Z | KurimuzonAkuma/kurigram | 794 | LGPL-3.0 | 200 |

| 2026-08-12T20:31:20.405Z | Kalwmw/Zip-Password-Recovery | 4 | MIT | 200 |

| 2026-08-12T20:31:24.019Z | alxxtexxr/arxiv_agent | 0 | MIT | 200 |

| 2026-08-12T20:31:30.264Z | Glyxehon/Blum-Crypto-Trading-P2P-Chain-Exchange | 0 | MIT | 200 |

| 2026-08-12T20:31:35.494Z | hyeonseo2/finfluencer-dashboard | 2 | MIT | 200 |

| 2026-08-12T20:31:38.323Z | jcmaker/just-study | 1 | MIT | 200 |

| 2026-08-12T20:31:42.153Z | Xiezhou0828/topicpilot-platform | 0 | MIT | 200 |

| 2026-08-12T20:31:45.759Z | AvaProtocol/protocols | 0 | MIT | 200 |

| Data | Repositório | Stars | License | HTTP |
|---|---|---|---|---|


## 🔄 Release Notes (auto)

<!-- release-notes-start -->
# Release Notes — test-tag-1786571246

Changelog automático (gerado pelo Copilot Actions):

- (nenhuma mudança detectada via git log)

<!-- release-notes-end -->
