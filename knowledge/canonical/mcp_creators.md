---
id: mcp_creators
title: Ferramentas Criadoras e Context Optimization de MCP Servers
category: mcp-tools
description: Lista de ferramentas open-source para criar, gerenciar, publicar e otimizar MCP servers
priority: 90
scope: global
tags: [mcp, tools, creators, generator, optimization, context]
author: CanalQb
version: 1.1
createdAt: 2026-08-12
updatedAt: 2026-08-12
---

# Ferramentas Criadoras e Context Optimization de MCP Servers

## Visão Geral

Ferramentas open-source para scaffolding de MCP (Model Context Protocol) servers, incluindo geração de configurações FastMCP, adição de tools/resources, build/testes, e otimização de contexto/search.

| Ferramenta | Linguagem | Descrição | License | Link |
|---|---|---|---|---|
| MCP Server Creator | Python | Cria outros MCP Servers, gera configurações FastMCP, adiciona tools/resources e gera código Python executável. | ✅ MIT | https://github.com/GongRzhe/mcp-server-creator |
| MCP Create | TypeScript | Cria e gerencia MCP Servers dinamicamente, incluindo execução, atualização e reinicialização. | ✅ Open Source | https://github.com/tesla0225/mcp-create |
| mcp-creator | Python | Permite ao agente criar projetos MCP, adicionar tools, fazer build e publicar no PyPI/GitHub. | ✅ MIT | https://pypi.org/project/mcp-creator/ |
| MCP Server Generator | TypeScript/Node | Gera servidores MCP com tools, resources, prompts e testes. | ✅ Open Source | https://www.mcp-server-generator.com/ |
| mcp-server-creator | Python | Gera servidores FastMCP dinamicamente, incluindo tools, resources e código Python. | ✅ MIT | https://pypi.org/project/mcp-server-creator/ |
| mcp-server-generator | Python | Gera projetos MCP a partir de schemas e especificações. | ✅ Open Source | https://pypi.org/project/mcp-server-generator/ |
| GitHub to MCP | TypeScript | Carrega repositórios GitHub como contexto MCP (arquivos, issues, PRs) para agentes. | ✅ Open Source | https://github.com/nirholas/github-to-mcp |
| MCP Generator | TypeScript | Scaffolding avançado de MCP servers com CLI interativa e templates. | ✅ Open Source | https://github.com/ChristopherDond/MCP-Generator |
| mcp-compressor | TypeScript | Atla compressão de contexto MCP — reduz tamanho de payloads preservando tools/resources essenciais. | ✅ Open Source (Apache 2.0) | https://github.com/atlassian-labs/mcp-compressor |
| Cloudflare MCP Context Optimization | — | Portal MCP da Cloudflare com otimização automática de contexto (token reduction). | ✅ Gratuito | https://developers.cloudflare.com/changelog/post/2026-03-26-mcp-portal-context-optimization/ |
| Parallel Search MCP | — | Busca paralela em múltiplas fontes (web, docs, código) via MCP — útil para context de pesquisa. | ✅ Closed source (freemium) | https://docs.parallel.ai/integrations/mcp/search-mcp |
| Parallel Task MCP | TypeScript | Executa tarefas MCP em paralelo (busca + execução concorrente). | ✅ Open Source | https://github.com/parallel-web/parallel-mcp |
| TinyContext | — | MCP server minimalista para redução de contexto — prioriza apenas tools críticas. | ❓ Desconhecido | https://github.com/TinyContext |
| CostAffective | — | MCP server focado em custo-benefício — roteia chamadas para provedores mais baratos. | ❓ Desconhecido | https://github.com/CostAffective |
| archex | — | MCP server para arquitetura de software — gera diagramas e estrutura de código. | ❓ Desconhecido | https://github.com/archex |
| TinyFish | — | MCP server ultraleve — runtime compatível com restrições de memória. | ❓ Desconhecido | https://github.com/TinyFish |
| Google Workspace Universal Search MCP | — | Busca universal no Google Workspace (Gmail, Drive, Calendar, Docs) via MCP. | ✅ Gratuito (Google) | https://developers.google.com/workspace/guides/universal-search-mcp |
| Anthropic MCP Builder | Python | Skill/toolchain oficial da Anthropic para scaffolding de MCP servers (tree/main/skills/mcp-builder). | ✅ Apache 2.0 | https://github.com/anthropics/skills/tree/main/skills/mcp-builder |

## Categorias

### Criadores / Scaffolding
- **Python**: mcp-server-creator, mcp-creator, mcp-server-generator
- **TypeScript/Node**: MCP Create, MCP Generator, mcp-compressor, Parallel Task MCP

### Context Optimization (compressão de contexto)
- **mcp-compressor** (Atlassian) — reduz payload preservando tools essenciais
- **Cloudflare MCP Context Optimization** — otimização automática via portal
- **TinyContext** — prioriza apenas tools críticas

### Context Enrichment (search/busca)
- **Parallel Search MCP** — busca paralela multi-fonte
- **GitHub to MCP** — carrega repositórios GitHub como contexto
- **Google Workspace Universal Search MCP** — busca no Google Workspace

### Routing / Custos
- **CostAffective** — roteia chamadas para provedores mais barhos

## Recomendações por Caso de Uso

| Caso | Recomendado | Motivo |
|---|---|---|
| Projeto TypeScript rapidamente | MCP Create (TypeScript) | Build + restart automático |
| Integração com FastMCP | mcp-server-creator (Python) | FastMCP config nativa |
| Publicar no PyPI | mcp-creator (Python) | Build + publish integrado |
| Testes inclusos | MCP Server Generator | Gera tests junto |
| Schemas avançados | mcp-server-generator (Python) | Especificações formais |
| Carregar GitHub como contexto | GitHub to MCP | Fetch repo/issues/PRs via MCP |
| Reduzir tokens de contexto | mcp-compressor | Atla compression preserving tools |
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
```
