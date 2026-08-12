---
id: mcp_creators
title: Ferramentas Criadoras de MCP Servers
category: mcp-tools
description: Lista de ferramentas open-source para criar, gerenciar e publicar MCP servers
priority: 90
scope: global
tags: [mcp, tools, creators, generator, cli]
author: CanalQb
version: 1.0
createdAt: 2026-08-12
---

# Ferramentas Criadoras de MCP Servers

## Visão Geral

Ferramentas open-source para scaffolding de MCP (Model Context Protocol) servers, incluindo geração de configurações FastMCP, adição de tools/resources e build/testes.

| Ferramenta | Linguagem | Descrição | License | Link |
|---|---|---|---|---|
| MCP Server Creator | Python | Cria outros MCP Servers, gera configurações FastMCP, adiciona tools/resources e gera código Python executável. | ✅ MIT | https://github.com/GongRzhe/mcp-server-creator |
| MCP Create | TypeScript | Cria e gerencia MCP Servers dinamicamente, incluindo execução, atualização e reinicialização. | ✅ Open Source | https://github.com/tesla0225/mcp-create |
| mcp-creator | Python | Permite ao agente criar projetos MCP, adicionar tools, fazer build e publicar no PyPI/GitHub. | ✅ MIT | https://pypi.org/project/mcp-creator/ |
| MCP Server Generator | TypeScript/Node | Gera servidores MCP com tools, resources, prompts e testes. | ✅ Open Source | https://www.mcp-server-generator.com/ |
| mcp-server-creator | Python | Gera servidores FastMCP dinamicamente, incluindo tools, resources e código Python. | ✅ MIT | https://pypi.org/project/mcp-server-creator/ |
| mcp-server-generator | Python | Gera projetos MCP a partir de schemas e especificações. | ✅ Open Source | https://pypi.org/project/mcp-server-generator/ |

## Recomendações por Caso de Uso

| Caso | Recomendado | Motivo |
|---|---|---|
| Projeto TypeScript rapidamente | MCP Create (TypeScript) | Build + restart automático |
| Integração com FastMCP | mcp-server-creator (Python) | FastMCP config nativa |
| Publicar no PyPI | mcp-creator (Python) | Build + publish integrado |
| Testes inclusos | MCP Server Generator | Gera tests junto |
| Schemas avançados | mcp-server-generator (Python) | Especificações formais |

## Usage Pattern

```bash
# Python
pip install mcp-server-creator
mcp-server-creator --name meu_mcp --transport stdio

# TypeScript
npx mcp-create --name meu_mcp
cd meu_mcp && npm test
```
