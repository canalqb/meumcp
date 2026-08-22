---
name: pdf-agent
description: "Agente PDF autônomo para ebooks de costuras seguindo normas ABNT - Master Rules v9.0"
version: 2.1.0
author: Hermes Agent
license: MIT
platforms: [linux, macos, windows]
---

# 🤖 Agente PDF Autônomo — Normas ABNT + Master Rules v9.0

## 📋 Regras do Usuário

1. **Nome**: Rodrigo Carlos Moraes (em todas as páginas)
2. **Leitura**: Todas as páginas do PDF
3. **Título + Subtítulo**: Estrutura de navegação
4. **Menu**: Índice clicável com links
5. **Etapas**: Protocolo 3.2 (Ação/Porquê/Resultado/Erro)
6. **Fotos**: Preservar posições do PDF original

## 📁 Estrutura do Projeto

```
./editrpdf/
├── input/           # PDFs fonte
├── output/          # PDFs gerados
├── temp/            # Intermediários HTML
├── references/      # Imagens extraídas
├── AGENTS.md        # Regras
└── PLANO_IMPLEMENTACAO.md
```

## 🛠️ Ferramentas

- `read_pdf(path)` → Texto + metadados
- `pdf_read_full(path)` → Todas as páginas
- `pdf_extract_images(path)` → Fotos com posição
- `html_to_pdf_abnt(html, output)` → PDF ABNT
- `abnt_template()` → Template HTML/CSS
- `abnt_header(title, subtitle, author)` → Cabeçalho

## 📊 Normas ABNT

- Fonte: Times New Roman 12pt
- Espaçamento: 1,5
- Margens: 2,5 cm
- Numeração: Superior direito
- Capítulos: Negrito 14pt
- Seções: Itálico 12pt
- Figuras: Legendadas numeradas

## 🚀 Comando Inicial

```
{Processar PDF: path="./input/livro.pdf", acoes=["ler_tudo", "extrair_fotos", "recriar", "salvar"]}
```

Feito com Master Rules Claude v9.0 + Normas ABNT