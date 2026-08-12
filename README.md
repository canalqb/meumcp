# meumcp

> **Central MCP (Model Context Protocol) Server** — Camada de conhecimento, regras, agentes e integração para o ecossistema @CanalQb.

Um servidor MCP que atua como **fonte única de verdade** para todos os LLMs e agentes, unificando conhecimento, regras, contexto e integração com MCPs existentes.

## 📋 Status

Em desenvolvimento ativo. Veja [docs/roadmap.md](docs/roadmap.md) para o plano de implementação.

## 🚀 Início Rápido

```bash
# Instalar dependências
npm install

# Build
npm run build

# Executar (stdio - para uso com Claude Desktop, Cursor, etc)
node dist/server.js

# Executar (HTTP - para uso em produção)
MCP_TRANSPORT=http node dist/server.js

# CLI - importar conhecimento
npm run cli -- import-knowledge
```

## 🏗️ Arquitetura

```
              AGENTES / LLMs
                     │
                     ▼
               MEUMCP MCP SERVER
                     │
      ┌──────────────┼──────────────┐
      ▼              ▼              ▼
  Knowledge      Rules          MCPs Externos
   Engine       Engine         (BrightData, etc)
```

Veja [docs/architecture.md](docs/architecture.md) para detalhes completos.

## 📚 Documentação

- [Arquitetura](docs/architecture.md)
- [API Reference](docs/api-reference.md)
- [Integração com Agentes](docs/integration.md)
- [Desenvolvimento](docs/development.md)

## 🧪 Testes

```bash
npm test                    # Unitários
npm run test:integration    # Integração
```

## 📄 Licença

MIT
