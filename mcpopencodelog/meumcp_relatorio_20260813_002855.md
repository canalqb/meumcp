# Relatório — reinstalação do MCP meumcp
Data: 2026-08-13 00:28:55

## O MCP
- Nome: meumcp v1.0.0 (CanalQb) — servidor MCP central para o ecossistema @CanalQb
- Fonte: https://github.com/canalqb/meumcp
- Instalado em: /root/meumcp (clone limpo, commit HEAD 17281b1)
- Meumcp installado via clone + npm install + tsc build

## Pós-escrita da instalação
- npm install: 640 pacotes, 0 vulnerabilidades
- Build tsc: OK
- Correcções locais reaplicadas: logger stderr, toSchema (JSON Schema),
  wizard `meumcp install` + prompts@2.4.2

## Config no opencode
- /root/.config/opencode/opencode.jsonc → `mcp.meumcp`: local,
  `node /root/meumcp/dist/server.js`, cwd /root/meumcp, enabled=true
- Status no opencode: **connected** (8 tools)

## Tools MCP (8)
get_context, search_knowledge, get_rules, check_rule, get_agent,
list_mcps, get_system, get_llm_rules

## Conteúdo carregado
- Knowledge canonical: 3 entries (master_rules, mcp_creators, mcp_discoveries)
- Rules: 17 (10 conflitos de sobreposição entre regra_llms_*)
- Agent profiles: 2

## Wizard meumcp install (executado)
- Ativado 1 MCP: keyhunter:BryanSapo/info-pulse
- Registry local completo: 33 MCPs (11 free no keyhunter)

## Comandos CLI disponíveis (12)
init, ingest, discover, install, context, rules, knowledge, mcps, agents,
serve, serve:http, doctor

## Observações
- O comando `install` (wizard) existe apenas localmente (patch aplicado
  manualmente) — upstream ainda não o tem.
- Para o MCP refletir mudanças: reiniciar o opencode (config não é hot-reload).