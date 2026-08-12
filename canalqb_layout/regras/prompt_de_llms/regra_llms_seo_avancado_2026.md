# Diretrizes de SEO Avançado e AdSense SGE 2026 para LLMs

Este documento define regras obrigatórias de SEO para 2026 focadas em SGE, Gemini, E-E-A-T, Topical Authority e AdSense Moderno. Exija a aplicação dessas regras em qualquer conteúdo ou estrutura de site gerada pelo LLM.

## 1. Information Gain (Ganho de Informação)
- **Nunca "reescreva"**: O conteúdo gerado deve ter **Information Gain** (dados originais, testes, experiências, estudos de caso). Textos sintéticos genéricos podem perder até 90% do tráfego.
- **Micro-Nichos**: Estruture a semântica do projeto para foco hiper-local ou micro-segmentado. Exija dados originais ou insights analíticos.
- **Atualização**: Implemente *Freshness*. Artigos gerados devem conter data de revisão visível para auditorias semestrais.

## 2. E-E-A-T Progressivo e Expertise
- Sempre construa conteúdo como uma **Experiência Real**. Inclua menções a *screenshots*, *resultados tangíveis* e *tutoriais práticos*.
- **Autoria Real**: A página de autor (`/autor/{nome}`) não é opcional em blogs monetizados. Deve ter estrutura de `Person Schema` e linkar para redes profissionais (LinkedIn/GitHub).

## 3. SEO Técnico e Performance Extremista
- **INP (Interaction to Next Paint)** `< 200ms`: O LLM deve focar em JS defer/async, mini-tasks em web workers e eliminação imediata de blocking scripts para aprovação AdSense.
- **CLS (Cumulative Layout Shift) `= 0`**: Reserve sempre espaço CSS exato para imagens, publicidade (AdSense slots) e Iframes.

## 4. Estrutura AEO (Answer Engine Optimization)
A estrutura de textos deve agradar tanto humanos quanto IA Generativa (SGE/Gemini):
- **Pergunta Clara**: `<h2>` em formato de interrogação.
- **Resposta Direta**: Parágrafo imediato abaixo do `<h2>` com 40 a 60 palavras exatas contendo o "answer target".
- **Aprofundamento**: Listas `<ul>`/`<ol>`, Tabelas HTML responsivas ou blocos expansíveis para contexto massivo.

## 5. Arquitetura de Múltiplos Pilares (Silos)
Construa ecossistemas de tráfego, não páginas:
- `site.com/blog/` (Notícias / Freshness)
- `site.com/guias/` (Pillar pages gigantes 3k+ palavras linkando as secundárias)
- `site.com/ferramentas/` (Mini-saas, calculadoras, geradores JSON — Tráfego Garantido)
- `site.com/glossario/` (Definições concisas mapeadas via AEO)

## 6. Programmatic SEO (Escala Controlada)
O LLM não deve temer automação, desde que estruturada:
- Gere templates para criar centenas de páginas à partir de um Array (SQL).
- Exemplo de DB: `{cidade}`, `{restaurante}`, `{nota}` transformadas em blocos visuais (`/melhores-restaurantes-em-sao-paulo`).
- Mutações nos textos para não gerar Duplicate Content Flag.

## 7. Parasite SEO Zero e Pruning
- Crie rotinas para o usuário realizar *Content Pruning* periódico (eliminar o que nunca tem tráfego e redirecionar via 301). Exclua páginas Zumbi para fortalecer o "Site Quality" perante o ranqueamento global do AdSense.

> **Validação de LLM:** Quando solicitado para otimizar um site/blog para aprovação de AdSense, cruze a `regra_llms_adsense_aprovacao_2025.md` com esta diretriz avançada de SEO 2026.
