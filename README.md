# Qb Nexus

> **Qb Nexus — Ponto de conexão entre agentes, conhecimento e MCPs**

Qb Nexus é a camada central de inteligência para o CanalQb — um *nexus* entre agentes, conhecimento, regras, agentes e MCPs descobertos.

**Qb Nexus** é um servidor [Model Context Protocol (MCP)](https://modelcontextprotocol.io) que unifica:

1. **Knowledge canonical** — regras, prompts e inventário de MCPs descobertos
2. **Rules engine** — diretrizes LLM governamentais
3. **MCP Keyhunter** — agente de busca autônoma que descobre novos MCPs free/open-source no GitHub a cada **2h**
4. **Connectors Plugin** — integração com Claude Desktop, ChatGPT, GitHub Copilot e outros agentes

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

### 3. Configure .env (opcional)

```bash
cp .env.example .env
# Edite .env e adicione GITHUB_TOKEN=ghp_xxx
```

### 4. Inicie o MCP

**Servidor stdio (recomendado para Claude Desktop):**

```bash
node dist/server.js
```

**Servidor HTTP (Streamable HTTP):**

```bash
npm run serve:http
# Porta padrão: http://localhost:8765
```

---

## 🤖 Conectores MCP

O meumcp suporta conectores para múltiplos agentes LLM:

| Client | Transport | Configuração |
|--------|-----------|--------------|
| Claude Desktop | stdio | `node dist/server.js` |
| ChatGPT | http | Porta 8765 |
| GitHub Copilot | stdio | `gh copilot install` |
| Hermes Agent | stdio | `hermes` CLI |
| OpenAI Chat | http | Porta 8766 |
| Anthropic Claude | http | Porta 8767 |

### Configuração via CLI

```bash
# Listar conectores
npm run cli -- list-connectors

# Obter configuração de um conector
npm run cli -- get-connector --client claude

# Configurar porta personalizada
npm run cli -- configure-connector --client claude --port 9000 --transport http
```

---

## 🧠 Tools MCP expostas (12)

| Tool | Descrição |
|------|-------------|
| `get_context` | Contexto unificado: knowledge + rules + MCPs |
| `search_knowledge` | Busca full-text em knowledge canonical |
| `get_rules` | Carrega hierarchical rules (master + agent-specific) |
| `check_rule` | Verifica se uma action é permitida |
| `get_agent` | Identidade + configuração de um agente |
| `list_mcps` | Lista todos os MCPs conhecidos + status HTTP |
| `get_system` | Stats do servidor (uptime, etc.) |
| `get_llm_rules` | Rules LLM canônicas |
| `list_connectors` | Lista conectores disponíveis |
| `get_connector` | Detalhes de um conector específico |
| `configure_connector` | Configura um conector |
| `github_auth` | Operações de auth GitHub (status, validate, sync, release) |

---

## 🔍 MCP Keyhunter

O **keyhunter** (`scripts/mcp_keyhunter/`) busca novos MCPs gratuitos no GitHub:

1. Busca via GitHub Search API
2. **Filtra apenas licenças free/open-source**: MIT, Apache-2.0, BSD, GPL, ISC, Unlicense
3. Absorve conhecimento (extrai README de cada repo)
4. **Filtra licenças pagas**: agpl/comercial/proprietary são **pulados**
5. Valida HTTP 200 de cada repo
6. Atualiza knowledge canonical + registry

### Cron (2h cycle)

| Trigger | Frequência |
|---------|------------|
| GitHub Actions | `0 */2 * * *` |
| Local cron | `npm run keyhunter` |

---

## 🔄 Update detection (nova sessão)

Em **cada nova sessão**, o meumcp verifica se o keyhunter validou novos MCPs. Se houver novidades:

```
[meumcp] ✨ 3 novos MCPs free foram descobertos pelo keyhunter!
  - github: /repos/owner/awesome-mcp — MIT, 12 estrelas
  - github: /repos/owner/another-mcp — Apache-2.0, 8 estrelas
  → Execute 'meumcp install' para ativar.
```

---

## ⚙️ GitHub OAuth

Para conectar ao GitHub (issues, PRs, releases):

```bash
# Verificar status
npm run cli -- github-auth --action status

# Validar token
npm run cli -- github-auth --action validate

# Sync automático
npm run cli -- github-auth --action sync
```

---

## 📁 Estrutura

```
meumcp/
├── src/
│   ├── server.ts          # MCP principal (12 tools)
│   ├── cli.ts             # CLI interativa
│   └── connectors/        # Plugins de conexão
├── rules/canonical/      # Regras LLM canônicas
├── knowledge/
│   ├── canonical/         # MCPs descobertos
│   └── generated/
│       └── mcp_registry.json
├── mcps/                  # Registry de MCPs
├── scripts/mcp_keyhunter/
├── .github/workflows/
└── tests/                 # Jest tests
```

---

## 🧪 Desenvolvimento

```bash
npm run dev      # watch (tsx)
npm run build    # TypeScript compile
npm test         # Jest (10 tests)
npm run lint     # ESLint
npm run format   # Prettier
```

---

## 🪪 Licença

MIT — parte da infraestrutura AI do @CanalQb.