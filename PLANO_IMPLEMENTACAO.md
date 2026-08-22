# 📋 PLANO DE IMPLEMENTAÇÃO — Agente PDF Autônomo

**Data**: 22/08/2026  
**Status**: 🚀 Em execução  

---

## 🎯 RESUMO DO PROJETO

### Objetivo
Agente autônomo para **leitura, análise e criação de PDFs** com foco em:
- **Normas ABNT** para ebooks de costuras
- **Nome**: Rodrigo Carlos Moraes em todas as páginas
- **Workflow completo**: PDF → Análise → HTML → PDF final

---

## 📁 ESTRUTURA DE PASTAS (CRIAR SE NÃO EXISTIREM)

```
C:/Users/Qb/Desktop/editrpdf/
├── input/              # PDFs fonte (processamento)
├── Editar/              # PDFs para revisão/usuario (ARQUIVO PRINCIPAL)
├── Editado/            # PDFs revisados/concluídos
├── output/              # PDFs gerados automaticamente
├── temp/                # Arquivos intermediários HTML
├── references/           # Imagens extraídas
├── checks/               # Arquivos CSV de controle
├── skills/
│   ├── pdf-orchestrator/
│   └── pdf-agent/
├── tools/
│   └── pdf_tool.py
├── mcp_config.json       # Configuração MCPs
├── AGENTS.md             # Regras do agente
├── README.md             # Documentação
├── plano.md              # Este plano
└── REGRAS_EDITACAO.md    # Regras de edição (ARQUIVO LIDIDO PARA COMPREENSÃO)
```

---

## 🔧 MCPs INSTALADOS ✅

| MCP | Instalação | Status |
|-----|------------|--------|
| @jztan/pdf-mcp | `npm install -g` | ✅ Instalado |
| @microsoft/markitdown-mcp | `npm install -g` | ✅ Instalado |
| @modelcontextprotocol/server-filesystem | `npm install -g` | ✅ Instalado |
| @modelcontextprotocol/server-memory | `npm install -g` | ✅ Instalado |

---

## 🛠️ FERRRAMENTAS LOKAIS DISPONÍVEIS

| Ferramenta | Status | Uso |
|------------|--------|-----|
| PyMuPDF | ✅ | Leitura, extração texto |
| pypdf | ✅ | Merge, split, manipulação |
| pdf2image | ✅ | Conversão páginas→imagem |
| pillow | ✅ | Processamento imagens |
| playwright/chromium | ✅ | HTML→PDF, screenshots |
| poppler | ⚠️ | Tools auxiliares |
| tesseract | ⚠️ | OCR (instalar) |
| pandoc | ⚠️ | Conversão documentos |

---

## 📚 SKILLS CRIADAS ✅

| Skill | Função | Status |
|-------|--------|--------|
| pdf-orchestrator | Coordena fluxo de processamento | ✅ Criada |
| document-architect | Planeja estrutura documental | ✅ Criada |
| pdf-agent | Regras ABNT + Master Rules | ✅ Criada |

---

## 🔄 FLUXO DE TRABALHO (NOVO)

### 1. Usuário coloca PDF em `Editar/`
```
Usuário → Coloca PDF em Editar/ → Agente detecta
```

### 2. Criação do CSV de controle
```
checks/arquivos_para_editar.csv
```

### 3. Agente lê e processa
```
Editar/*.pdf → Analisa → Temp → Temp HTML → Output
```

### 4. Usuário revisa
```
Editar → Editado (após revisão)
```

---

## 📊 ARQUIVO CSV DE CONTROLE

**Localização**: `checks/arquivos_para_editar.csv`

### Formato:
```csv
arquivo,status,data_criacao,data_conclusao,observacoes
"Sereia Yasmine.pdf","pendente","2026-08-22 10:00","","Aguardando revisão"
```

### Colunas obrigatórias:
- `arquivo` - nome do PDF
- `status` - "pendente" ou "concluido"
- `data_criacao` - quando foi colocado
- `data_conclusao` - quando revisado
- `observacoes` - notas do usuário

---

## 📋 REGRAS DE EDIÇÃO (REGRAS_EDITACAO.md)

### Arquivo que deve ser lido para entender o sistema

Quando solicitado para **editar, criar, modificar ou melhorar** um arquivo:

1. **LER REGRAS EDITAÇÃO** (`REGRAS_EDITACAO.md`)
2. **VERIFICAR CSV** (`checks/arquivos_para_editar.csv`)
3. **PROCESSAR PDF** seguindo normas ABNT
4. **INSERIR NOME** "Rodrigo Carlos Moraes" em todas as páginas
5. **CRIAR MENU** clicável com links
6. **PRESERVAR FOTOS** nas posições corretas
7. **APLICAR PROTOCOLO 3.2** (Ação/Porquê/Resultado/Erro)
8. **EXPORTAR** para `Editado/`

### Comando padrão:
```
{Editar PDF: path="./Editar/file.pdf", acoes=["ler_tudo", "extrair", "recriar", "salvar"]}
```

---

## 📊 METRICS DE SUCESSO

- ✅ PDF de saída em `Editado/`
- ✅ Normas ABNT aplicadas
- ✅ Nome autor em todas páginas
- ✅ Menu clicável
- ✅ Imagens nas posições corretas
- ✅ CSV atualizado

---

## 🚀 COMANDOS PRINCIPAIS

### Para adicionar PDF para edição:
```
/Adicionar PDF: file="./Editar/novo_arquivo.pdf"
```

### Para processar PDF:
```
{Processar PDF: path="./Editar/file.pdf", acoes=["ler_tudo", "extrair_fotos", "gerar_html_abnt"]}
```

### Para listar pendentes:
```
{lster CSV: path="./checks/arquivos_para_editar.csv"}
```

---

**Status**: Setup concluído, aguardando primeiro PDF em `Editar/`