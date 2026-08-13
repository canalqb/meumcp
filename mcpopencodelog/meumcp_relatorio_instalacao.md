# Relatório — o que foi instalado com o MCP meumcp
Data: 2026-08-12

## O MCP
- Nome: meumcp v1.0.0 (CanalQb) — servidor MCP central para o ecossistema @CanalQb
- Código-fonte: https://github.com/canalqb/meumcp
- Instalado em: /root/meumcp
- Token do commit: c10caf7..ee4f2c4 (atualizado, fast-forward)

## Dependências instaladas (npm, 626 pacotes)
- Runtime (11): @modelcontextprotocol/sdk@1.30.0, chokidar, commander,
  dotenv, fastify, gray-matter, js-yaml, marked, picocolors, pino, zod
- Dev (14): jest, tsx, typescript@5.5.4, typescript-eslint, eslint,
  prettier, supertest, ts-jest, @types/node@22, entre outros
- 0 vulnerabilidades; build tsc OK

## Configuração no opencode
- Arquivo: /root/.config/opencode/opencode.jsonc
- Entrada `mcp.meumcp`: type local, `node /root/meumcp/dist/server.js`,
  cwd `/root/meumcp` (obrigatório p/ caminhos relativos), env
  MEUMCP_LOG_LEVEL=info, enabled=true

## Tools MCP expostas (8)
1. get_context           — contexto unificado (knowledge + rules + MCPs)
2. search_knowledge      — busca full-text no knowledge canonical
3. get_rules             — regras hierárquicas (master + agent-specific)
4. check_rule            — se uma action é permitida
5. get_agent             — identidade/config de um agente
6. list_mcps             — MCPs conhecidos + status HTTP
7. get_system            — stats do servidor
8. get_llm_rules         — regras LLM canônicas

## Conteúdo carregado
- Knowledge canonical: 3 entradas
- Rules: 17 regras (com 10 conflitos de sobreposição entre regra_llms_*)
- Agentes: registros em agents/agents.json (default, content-creator)
- Registry local: 0 MCPs (vazio)

## MCPs descobertos pelo keyhunter (knowledge/generated/mcp_registry.json)
- Total no registry: 30 | licenças free/open-source: 9 (MIT / LGPL-3.0)
  1. CaryNC-IP/carync-ip.github.io        (MIT)
  2. KurimuzonAkuma/kurigram              (LGPL-3.0, 794 stars)
  3. Kalwmw/Zip-Password-Recovery         (MIT)
  4. alxxtexxr/arxiv_agent                (MIT)
  5. Glyxehon/Blum-Crypto-Trading-P2P-Chain-Exchange (MIT)
  6. hyeonseo2/finfluencer-dashboard      (MIT)
  7. jcmaker/just-study                   (MIT)
  8. Xiezhou0828/topicpilot-platform      (MIT)
  9. AvaProtocol/protocols                (MIT)
- 21 restantes não passaram no filtro free (license_free=false) — ignorados por política

## Comandos disponíveis na CLI
init, ingest, discover, context, rules, knowledge, mcps, agents,
serve (stdio), serve:http, doctor

## Observações
- O wizard `meumcp install` (caixa de seleção) está documentado no README,
  mas NÃO está implementado no CLI atual.
- Para listar MCPs descobertos: `npx meumcp discover` (0 na máquina local) ou
  consultar knowledge/generated/mcp_registry.json (30 no registro do keyhunter).
- Para que o MCP fique ativo no opencode: reiniciar o opencode (config não é hot-reload).