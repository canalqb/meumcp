# 📚 PDF Agent Autônomo — CanalQb

Agente especializado em leitura, análise e criação de PDFs (ebooks de costuras) seguindo normas ABNT.

## 📋 Status Atual

✅ **Ferramentas criadas e testadas**  
✅ **Estrutura de pastas configurada**  
✅ **Skill padrão carregado**  
⏳ **Aguardando PDF original para processar**

## 📁 Estrutura

```
editrpdf/
├── input/              # Coloque seus PDFs aqui
├── output/             # PDFs processados serão salvos aqui
├── temp/               # Arquivos temporários
├── references/         # Imagens extraídas
├── tools/
│   └── pdf_tool.py     # Ferramentas PDF
├── skills/
│   └── pdf-agent/
│       └── SKILL.md    # Regras do agente
├── AGENTS.md           # Regras do agente
├── PLANO_IMPLEMENTACAO.md
└── README.md           # Este arquivo
```

## 🚀 Como Usar

### 1. Colocar PDF para processar
```bash
cp seu_arquivo.pdf C:/Users/Qb/Desktop/editrpdf/input/
```

### 2. Processar PDF
```
{Processar PDF: path="./input/seu_arquivo.pdf", acoes=["ler_tudo", "extrair_fotos", "recriar_menu", "gerar_abnt"]}
```

### 3. Resultado
O agente criará:
- PDF final em `output/` com normas ABNT
- Índice clicável
- Imagens nas posições corretas
- Nome "Rodrigo Carlos Moraes" em todas as páginas

## 🛠️ Ferramentas Disponíveis

| Ferramenta | Uso |
|------------|-----|
| `read_pdf(path)` | Extrair texto de PDF |
| `pdf_read_full(path)` | Ler todas as páginas |
| `pdf_extract_images(path)` | Extrair fotos com posições |
| `html_to_pdf_abnt(html, output)` | Gerar PDF ABNT |

## 📊 Normas ABNT Aplicadas

- Fonte: Times New Roman 12pt
- Espaçamento: 1,5
- Margens: 2,5 cm
- Numeração: Superior direito
- Capítulos: Negrito 14pt

## 🔧 Setup

```bash
# Dependências necessárias
pip install pymupdf pypdf pdf2image pillow
```

---

**Status**: ✅ Pronto para receber PDF  
`Feito com Master Rules Claude v9.0 + Normas ABNT`