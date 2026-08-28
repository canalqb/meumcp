# Qb Nexus

> **Qb Nexus — Ponto de conexão entre agentes, conhecimento e MCPs**

Qb Nexus é a camada central de inteligência para o CanalQb — um *nexus* entre agentes, conhecimento, regras, agentes e MCPs descobertos.

**Qb Nexus** é um servidor [Model Context Protocol (MCP)](https://modelcontextprotocol.io) que unifica:

1. **Knowledge canonical** — regras, prompts e inventário de MCPs descobertos
2. **Rules engine** — diretrizes LLM governamentais
3. **MCP Keyhunter** — agente de busca autônoma que descobre novos MCPs free/open-source no GitHub a cada **2h**
4. **Connectors Plugin** — integração com Claude Desktop, ChatGPT, GitHub Copilot e outros agentes

---

## 🚀 Como se conectar

### 🔗 Claude Desktop (Local - STDIO)

**IMPORTANTE**: Após instalar, reinicie o Claude Desktop para que as alterações tenham efeito.

Edita o arquivo `claude_desktop_config.json`:
- Windows: `C:\Users\<seu-usuario>\AppData\Roaming\Claude\claude_desktop_config.json`
- macOS: `~/Library/Application Support/Claude/claude_desktop_config.json`

```json
{
  "mcpServers": {
    "meumcp": {
      "command": "node",
      "args": ["C:\\Users\\Qb\\Desktop\\meumcp\\dist\\server.js"],
      "env": {
        "MEUMCP_CLI": "true",
        "GITHUB_TOKEN": "ghp_sua-chave-aqui"
      }
    }
  }
}
```

> **⚠️ ATENÇÃO**: Substitua o caminho acima pelo caminho ABSOLUTO até o seu diretório meumcp.

### 🔗 Claude.ai Web (Remoto - HTTP)

O meumcp suporta conexão remota via **Streamable HTTP** com OAuth 2.0 integrado.

**Passo 1**: Inicie o servidor HTTP

```bash
# Simples (para testes):
node dist/server.js
# Ou via env:
MCP_TRANSPORT=http node dist/server.js

# Ou com porta personalizada:
MCP_SERVER_PORT=9000 MCP_TRANSPORT=http node dist/server.js
```

**Passo 2**: Se precisar de acesso público (para Claude.ai Web)

```bash
# Instale ngrok se não tiver:
npm install -g ngrok

# Exponha a porta:
ngrok http 8765

# Copie a URL (algo como https://abc123.ngrok.io)
```

**Passo 3**: No Claude.ai Web

1. Acesse: https://claude.ai/new?modal=add-custom-connector#settings/custom-connectors
2. Clique em "Add custom connector"
3. Nome: `meumcp`
4. URL: `https://abc123.ngrok.io/mcp` (substitua pela sua URL ngrok)
5. Transmissão: **HTTP Streamable**
6. Autenticação: **Nenhum** (ou configure OAuth se necessário)

### 🔗 ChatGPT

1. Navegue para https://chat.openai.com
2. Abra as Configurações → "Plugins"
3. Adicione como MCP Server remoto

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
MCP_SERVER_URL=http://localhost:8765 npm run serve:http
# Porta padrão: http://localhost:8765
```

---

## 🤖 Conectores MCP

O meumcp suporta conectores para múltiplos agentes LLM:

| Client | Transport | Configuração |
|--------|-----------|--------------|
| Claude Desktop | stdio | `node dist/server.js` |
| Claude.ai Web | http | Porta 8765 + OAuth |
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
| `get_agent` | Identidade de agentes |
| `list_mcps` | Lista MCPs conhecidos + status HTTP |
| `get_system` | Stats do servidor (uptime, etc.) |
| `get_llm_rules` | Rules LLM canônicas |
| `list_connectors` | Lista conectores disponíveis |
| `get_connector` | Detalhes de conector específico |
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
│   ├── http-handler.ts    # HTTP + OAuth endpoints
│   ├── cli.ts             # CLI interativa
│   └── connectors/       # Plugins de conexão
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

## 🔒 OAuth 2.0 / Claude.ai Integration

O meumcp implementa OAuth 2.0 conforme especificação RFC 9728:

| Endpoint | Função |
|----------|--------|
| `/.well-known/oauth-protected-resource` | Metadata OAuth para descoberta Claude.ai |
| `/mcp` | Endpoint principal MCP (Streamable HTTP) |

### Metadata Suportada

```json
{
  "resource": "https://claude.ai/mcp/meumcp",
  "authorization_servers": ["https://auth.claude.ai"],
  "scopes_supported": ["read", "write", "tools"],
  "bearer_methods_supported": ["header"],
  "resource_signing_alg_values_supported": ["RS256", "ES256"]
}
```

---

## 🪪 Licença

MIT — parte da infraestrutura AI do @CanalQb.

## 🔄 Release Notes (auto)

<!-- release-notes-start -->
# Notas da Versão — keyhunter-%Y> (HEAD -> main, origin/main)b4956416fb2e0def6c13c3bd96dd619997ce2186%MHEAD

Changelog automático (gerado pelas ações do GitHub):

- (nenhuma mudança detectada via git log)

<!-- release-notes-end -->

## MCPs Descoveltos pelo Keyhunter

| 2026-08-28T08:39:13.614Z | Shivampal157/AlgoRadar-CP | 0 | MIT | 200 |

| 2026-08-28T08:39:14.815Z | allen1881996/explain-ai-with-cards | 0 | MIT | 200 |

| 2026-08-28T08:39:21.201Z | Dyna-Techo/Research-topic-prediction | 0 | MIT | 200 |

| 2026-08-27T21:37:28.400Z | Aguila1989/obsidian-clustered-graph | 0 | MIT | 200 |

| 2026-08-27T06:51:26.211Z | richardchen99/nev-social-intelligence | 0 | MIT | 200 |

| 2026-08-26T12:40:52.563Z | martinrashkov/topic-diversity-gender-prediction | 0 | MIT | 200 |

| 2026-08-26T12:40:55.533Z | zz4fff/finance3 | 0 | GPL-3.0 | 200 |

| 2026-08-26T00:59:07.465Z | IvTole/Topicos_Selectos_II_CUCEA | 0 | MIT | 200 |

| 2026-08-26T00:59:08.477Z | vezril/hermes-ui | 0 | MIT | 200 |

| 2026-08-24T12:38:45.520Z | Billhlt/arena-game-ai | 0 | MIT | 200 |

| 2026-08-24T12:38:47.762Z | Rubidius37/Log2Topic | 0 | MIT | 200 |

| 2026-08-24T00:58:07.802Z | 2jungwu/youtube-summarizer | 0 | MIT | 200 |

| 2026-08-24T00:58:09.958Z | ADITYA-tp01/Insight-Forge-AI | 0 | MIT | 200 |

| Data | Repositório | Stars | License | HTTP |
|---|---|---|---|---|
