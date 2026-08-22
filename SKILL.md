---
name: pdf-agent
description: "Agente PDF autônomo para ebooks de costuras - Master Rules v9.0 + ABNT. Workflow completo: PDF → Extração → HTML → PDF final"
version: 2.1.0
author: Hermes Agent + CanalQb
license: MIT
platforms: [linux, macos, windows]
metadata:
  hermes:
    tags: [pdf, pdf-reader, pdf-creator, abnt, book, e-book, sewing, costura, document-processing]
    homepage: https://github.com/canalqb/pdf-agent
---

# 🤖 Agente PDF Autônomo  
Master Rules @CanalQb v9.0 + Normas ABNT

## 📋 Regras Específicas do Usuário

| Regra | Detalhe |
|-------|---------|
| **Nome Completo** | Rodrigo Carlos Moraes (em todas as páginas) |
| **Leitura** | Ler todas as páginas do PDF original |
| **Título/Subtítulo** | Criar estrutura de navegação |
| **Menu** | Índice clicável com links para capítulos/etapas |
| **Etapas** | Protocolo 3.2 (Ação + Porquê + Resultado + Erro) |
| **Fotos** | Preservar posições exatas do PDF original |

## 📁 Estrutura do Projeto (`C:/Users/Qb/Desktop/editrpdf/`)

```
editrpdf/
├── input/              # PDFs fonte
├── output/             # PDFs gerados
├── temp/               # Arquivos intermediários HTML/CSS
├── references/         # Imagens extraídas (posições originais)
├── SKILL.md            # Este skill (régras)
├── tools/
│   └── pdf_tool.py     # Ferramentas Python
├── AGENTS.md           # Regras do agente
├── PLANO_IMPLEMENTACAO.md
└── README.md           # Documentação
```

## 🛠️ Ferramentas Disponíveis

### Leitura PDF
| Ferramenta | Uso |
|------------|-----|
| `read_pdf(path, page_range)` | Extrai texto de PDF |
| `pdf_read_full(path)` | Lê todas as páginas |
| `pdf_extract_images(path, output_dir)` | Extrai fotos preservando posições |
| `pdf_metadata(path)` | Metadados do documento |

### Criação PDF ABNT
| Ferramenta | Uso |
|------------|-----|
| `html_to_pdf_abnt(html, output, options)` | Converte HTML para PDF com estilos ABNT |
| `abnt_template()` | Template HTML/CSS completo |
| `abnt_header(title, subtitle, author)` | Cabeçalho padrão com autor |
| `pdf_merge(files, output)` | Une múltiplos PDFs |
| `pdf_split(path, ranges)` | Divide PDF em partes |

## 📊 Normas ABNT Aplicadas

```
Fonte: Times New Roman ou Arial, tamanho 12pt
Espaçamento: 1,5 entre linhas
Margens: 2,5 cm (todos os lados)
Numeração: Superior direito
Capítulos: Negrito 14pt, alinhamento esquerdo
Seções: Itálico 12pt, alinhamento esquerdo
Figuras: Legendadas, numeradas sequencialmente (NBR 6024)
Referências: Normas ABNT NBR 6023
```

## 🚀 Comandos Principais

### Quando receber um PDF para processar:
```
{Processar PDF: path="./input/livro.pdf", acoes=["ler_tudo", "extrair_fotos", "recriar_menu", "gerar_abnt", "salvar"]}
```

### Para criar novo ebook:
```
{Criador PDF: tema="Livro de Costuras", autor="Rodrigo Carlos Moraes", formato="ebook", paginas=50, normas="ABNT"}
```

### Para gerar PDF com normas:
```
{Gere PDF ABNT: titulo="Título", subtitulo="Subtítulo", autor="Rodrigo Carlos Moraes", output="./output/ebook.pdf"}
```

## 🔧 Setup Técnico

```bash
# Dependências necessárias
pip install pymupdf pypdf pdf2image pillow

# Para conversão HTML→PDF
# Opção 1: wkhtmltopdf (instalar via instalador oficial)
# Opção 2: Puppeteer (npx puppeteer install)

# Verificar se tudo está pronto
python -c "import fitz; import pypdf; print('OK')"
```

## 📋 Checklist ABNT (pós-geração)

- [ ] Fonte Times New Roman 12pt
- [ ] Espaçamento 1,5
- [ ] Margens 2,5 cm
- [ ] Numeração superior direito
- [ ] Capítulos negrito 14pt
- [ ] Seções itálico 12pt
- [ ] Figuras legendadas numeradas
- [ ] Índice com links clicáveis
- [ ] Autor: Rodrigo Carlos Moraes em todas as páginas

---

**Status**: ✅ Ferramentas criadas, aguardando PDF original  
**Próximo passo**: Informar arquivo PDF em `input/`

`Feito com Master Rules Claude v9.0 + Normas ABNT`