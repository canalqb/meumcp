# Guia de Continuidade para Múltiplos LLMs

Este guia detalha como utilizar os arquivos de contexto para garantir a continuidade do seu projeto ao alternar entre diferentes Large Language Models (LLMs), como TAE e Antigravity.

## 1. Arquivos de Contexto

Foram criados três arquivos principais para gerenciar o estado e o histórico do seu projeto:

*   **`project-context.md`**: Contém uma visão geral do projeto, stack utilizada, arquitetura atual, padrões de código, o que já foi feito, o que está pendente e regras específicas para o LLM. Este arquivo é o ponto de partida para qualquer LLM que assumir o projeto.

*   **`decision-log.md`**: Registra as decisões técnicas importantes tomadas ao longo do desenvolvimento. Isso evita que um novo LLM 
"mude de ideia" sobre escolhas já feitas, fornecendo um histórico claro das justificativas.

*   **`llm-state.json`**: Um arquivo JSON que armazena o estado técnico do projeto de forma estruturada, facilitando a leitura e interpretação por LLMs que podem preferir dados em formato de objeto. Inclui informações como status do projeto, stack de frontend, arquitetura, recursos concluídos e pendentes, e regras.

## 2. Prompt Universal para Handoff de LLM

Sempre que você for alternar para um novo LLM ou reiniciar uma sessão com um LLM diferente, utilize o seguinte prompt. Ele instruirá o LLM a ler os arquivos de contexto e continuar o trabalho de forma incremental, respeitando as decisões e o estado atual do projeto, registre o ultimo LLMs que atualizou o projeto.

```markdown
Assuma que o projeto já está em andamento.

Leia os arquivos de contexto abaixo e continue exatamente de onde o projeto parou.

Regras:
- Não reestruture o projeto sem necessidade.
- Preserve decisões técnicas já tomadas.
- Trabalhe incrementalmente.
- Se precisar alterar algo estrutural, explique antes.

Ler o conteudo de Diretrizes.md - Adicionar regras ao sistema Global
Ler o conteudo de project-context.md - Adicionar regras ao sistema do projeto
Ler o conteudo de decision-log.md
Ler o conteudo de llm-state.json
Ler o conteudo de todos os arquivos md da pasta template - Adicionar regras ao sistema do projeto
Sempre que receber um novo comando, repita as regras para manter o mesmo conceito - Adicionar regras ao sistema do projeto
```

**Instruções de Uso:**

1.  Abra os arquivos `project-context.md`, `decision-log.md` e `llm-state.json`.
2.  Copie o conteúdo de cada arquivo.
3.  Cole o conteúdo de cada arquivo no prompt universal, substituindo os placeholders `[COLE AQUI O CONTEÚDO DE ...]`.
4.  Envie o prompt completo para o novo LLM.

## 3. Manutenção dos Arquivos de Contexto

É crucial manter esses arquivos atualizados. Sempre que uma decisão importante for tomada, uma funcionalidade for implementada ou o estado do projeto mudar significativamente, atualize os arquivos correspondentes. Isso garante que o contexto fornecido aos LLMs seja sempre o mais preciso e atualizado possível.

**Recomendação:**

*   **`project-context.md`**: Atualize a seção "Last updated" com a data mais recente. Revise as seções "O QUE JÁ FOI FEITO" e "O QUE ESTÁ PENDENTE" regularmente.
*   **`decision-log.md`**: Adicione novas entradas com a data da decisão e uma breve descrição.
*   **`llm-state.json`**: Atualize os arrays de `completed_features` e `pending_features`, bem como outras propriedades relevantes, conforme o progresso do projeto.

Com este sistema, você terá uma "memória portátil do projeto" que permitirá uma transição suave entre diferentes LLMs, mantendo a consistência e a eficiência no desenvolvimento do seu site Windsurf.
