---
id: mcp_creators
title: Ferramentas Criadoras e Context Optimization de MCP Servers
category: mcp-tools
description: Lista de ferramentas open-source para criar, gerenciar, publicar e otimizar MCP servers — validada HTTP 2026-08-12
priority: 90
scope: global
tags: [mcp, tools, creators, generator, optimization, context]
author: CanalQb
version: 1.2
createdAt: 2026-08-12
updatedAt: 2026-08-12
---

# Ferramentas Criadoras e Context Optimization de MCP Servers

## Visão Geral

Ferramentas open-source para scaffolding de MCP (Model Context Protocol) servers, incluindo geração de configurações FastMCP, adição de tools/resources, build/testes, e otimização de contexto/search.

## Status de Validação HTTP

**HTTP validation 2026-08-12:** 14/18 URLs retornam 200 OK. 4 URLs retornam 404 (marcas "⚠️ 404 — não encontrado"). Verifique antes de usar.

| Ferramenta | Linguagem | Descrição | License | Link | Status |
|---|---|---|---|---|---|
| MCP Server Creator | Python | Cria outros MCP Servers, gera configurações FastMCP, adiciona tools/resources e gera código Python executável. | ✅ MIT | https://github.com/GongRzhe/mcp-server-creator | ✅ 200 |
| MCP Create | TypeScript | Cria e gerencia MCP Servers dinamicamente, incluindo execução, atualização e reinicialização. | ✅ Open Source | https://github.com/tesla0225/mcp-create | ✅ 200 |
| mcp-creator | Python | Permite ao agente criar projetos MCP, adicionar tools, fazer build e publicar no PyPI/GitHub. | ✅ MIT | https://pypi.org/project/mcp-creator/ | ✅ 200 |
| MCP Server Generator | TypeScript/Node | Gera servidores MCP com tools, resources, prompts e testes. | ✅ Open Source | https://www.mcp-server-generator.com/ | ✅ 200 |
| mcp-server-creator | Python | Gera servidores FastMCP dinamicamente, incluindo tools, resources e código Python. | ✅ MIT | https://pypi.org/project/mcp-server-creator/ | ✅ 200 |
| mcp-server-generator | Python | Gera projetos MCP a partir de schemas e especificações. | ✅ Open Source | https://pypi.org/project/mcp-server-generator/ | ✅ 200 |
| GitHub to MCP | TypeScript | Carrega repositórios GitHub como contexto MCP (arquivos, issues, PRs) para agentes. | ✅ Open Source | https://github.com/nirholas/github-to-mcp | ✅ 200 |
| MCP Generator | TypeScript | Scaffolding avançado de MCP servers com CLI interativa e templates. | ✅ Open Source | https://github.com/ChristopherDond/MCP-Generator | ✅ 200 |
| mcp-compressor | TypeScript | Atla compressão de contexto MCP — reduz tamanho de payloads preservando tools/resources essenciais. | ✅ Open Source (Apache 2.0) | https://github.com/atlassian-labs/mcp-compressor | ✅ 200 |
| Cloudflare MCP Context Optimization | — | Portal MCP da Cloudflare com otimização automática de contexto (token reduction). | ✅ Gratuito | https://developers.cloudflare.com/changelog/post/2026-03-26-mcp-portal-context-optimization/ | ✅ 200 |
| Parallel Search MCP | — | Busca paralela em múltiplas fontes (web, docs, código) via MCP — útil para context de pesquisa. | ✅ Closed source (freemium) | https://docs.parallel.ai/integrations/mcp/search-mcp | ✅ 200 |
| Parallel Task MCP | TypeScript | Executa tarefas MCP em paralelo (busca + execução concorrente). | ✅ Open Source | https://github.com/parallel-web/parallel-mcp | ⚠️ 404 |
| TinyContext | — | MCP server minimalista para redução de contexto — prioriza apenas tools críticas. | ❓ Desconhecido | https://github.com/TinyContext | ⚠️ 404 |
| CostAffective | — | MCP server focado em custo-benefício — roteia chamadas para provedores mais barhos. | ❓ Desconhecido | https://github.com/CostAffective | ⚠️ 404 |
| archex | — | Repo/arquivo de arquitetura de software — pode não ser um MCP server. | ❓ Desconhecido | https://github.com/archex | ⚠️ não é MCP (user profile) |
| TinyFish | — | Repo de usuário JS (jScrollPane fork) — não é um MCP server. | ❓ Desconhecido | https://github.com/TinyFish | ⚠️ não é MCP (user profile) |
| Google Workspace Universal Search MCP | — | Busca universal no Google Workspace (Gmail, Drive, Calendar, Docs) via MCP. | ✅ Gratuito (Google) | https://developers.google.com/workspace/guides/universal-search-mcp | ✅ 200 |
| Anthropic MCP Builder | Python | Skill/toolchain oficial da Anthropic para scaffolding de MCP servers (tree/main/skills/mcp-builder). | ✅ Apache 2.0 | https://github.com/anthropics/skills/tree/main/skills/mcp-builder | ✅ 200 |

## Categorias

### Criadores / Scaffolding
- **Python**: mcp-server-creator, mcp-creator, mcp-server-generator
- **TypeScript/Node**: MCP Create, MCP Generator, mcp-compressor, Parallel Task MCP (⚠️ 404)

### Context Optimization (compressão de contexto)
- **mcp-compressor** (Atlassian) — reduz payload preservando tools essenciais
- **Cloudflare MCP Context Optimization** — otimização automática via portal
- ~~TinyContext~~ — ❌ 404 (repo não encontrado)

### Context Enrichment (search/busca)
- **Parallel Search MCP** — busca paralela multi-fonte
- **GitHub to MCP** — carrega repositórios GitHub como contexto
- **Google Workspace Universal Search MCP** — busca no Google Workspace

### Routing / Custos
- ~~CostAffective~~ — ❌ 404 (repo não encontrado)

### Não-MCP (removidos da adoção)
- **archex** — user profile, não é MCP
- **TinyFish** — user profile (jScrollPane fork), não é MCP

## Recomendações por Caso de Uso

| Caso | Recomendado | Motivo |
|---|---|---|
| Projeto TypeScript rapidamente | MCP Create (TypeScript) | Build + restart automático |
| Integração com FastMCP | mcp-server-creator (Python) | FastMCP config nativa |
| Publicar no PyPI | mcp-creator (Python) | Build + publish integrado |
| Testes inclusos | MCP Server Generator | Gera tests junto |
| Schemas avançados | mcp-server-generator (Python) | Especificações formais |
| Carregar GitHub como contexto | GitHub to MCP | Fetch repo/issues/PRs via MCP |
| Reduzir tokens de contexto | mcp-compressor | Compressão preservando tools |
| Busca paralela multi-fonte | Parallel Search MCP | Web + docs + código simultaneamente |

## Usage Patterns

```bash
# Python
pip install mcp-server-creator
mcp-server-creator --name meu_mcp --transport stdio

# TypeScript
npx mcp-create --name meu_mcp
cd meu_mcp && npm test

# GitHub to MCP
npx @gongrzhe/github-to-mcp --repo owner/repo

# mcp-compressor (inline config)
mcp-compressor --ratio 0.3 --preserve-critical-tools

# Anthropic MCP Builder (oficial)
cd skills/mcp-builder && python -m mcp_builder.cli --init
```
