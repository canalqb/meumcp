---
name: pdf-agent
description: "Agente PDF autônomo para criação de ebooks - Master Rules @CanalQb v9.0 + ABNT"
version: 2.1.0
author: Hermes Agent + CanalQb
license: MIT
platforms: [linux, macos, windows]
metadata:
  hermes:
    tags: [pdf, document, reading, creation, html-to-pdf, abnt, costura]
    homepage: https://github.com/canalqb/pdf-agent
---

# 🤖 Agente PDF Autônomo — Master Rules @CanalQb v9.0 + ABNT

Agente especializado em **leitura, análise e criação de PDFs** com foco em **normas ABNT** para livros de costuras.

## 📋 Regras Específicas do Usuário

1. **Nome Completo**: Rodrigo Carlos Moraes (inserir em todas as páginas)
2. **Leitura completa**: Ler todas as páginas do PDF original
3. **Título + Subtítulo**: Criar estrutura de navegação
4. **Menu clicável**: Índice com links para capítulos/etapas
5. **Etapas numeradas**: Seguir protocolo 3.2
6. **Fotos preservadas**: Manter imagens nas posições corretas

## 📁 Estrutura do Projeto

```
./editrpdf/
├── input/           # PDFs fonte
├── output/          # PDFs gerados
├── temp/            # Arquivos intermediários
├── references/      # Imagens extraídas
├── skills/          # Skills do Hermes
│   └── pdf-agent/
│       └── SKILL.md
├── tools/           # Ferramentas Python
│   └── pdf_tool.py
├── AGENTS.md        # Regras do agente
└── PLANO_IMPLEMENTACAO.md
```

## 🛠️ Ferramentas Disponíveis

### Leitura PDF
- `read_pdf(path)` → Texto e metadados
- `pdf_read_full(path)` → Todas as páginas
- `pdf_extract_images(path)` → Fotos preservando posição

### Criação ABNT
- `html_to_pdf_abnt(html, output)` → PDF com estilos ABNT
- `abnt_template()` → Template HTML/CSS
- `abnt_header(title, subtitle, author)` → Cabeçalho padrão

## 📊 Normas ABNT Aplicadas

- Fonte: Times New Roman ou Arial, tamanho 12
- Espaçamento: 1,5
- Margens: 2,5 cm
- Numeração: Superior direito
- Capítulos: Negrito 14pt, alinhamento esquerdo
- Seções: Itálico 12pt, alinhamento esquerdo
- Figuras: Legendadas e numeradas
- Referências: NBR 6023

## 🚀 Workflow

```
PDF_original → Extração(todos) → HTML_ABNT(Rodrigo Carlos Moraes) → PDF_final
```

Feito com Master Rules Claude v9.0 + Normas ABNT