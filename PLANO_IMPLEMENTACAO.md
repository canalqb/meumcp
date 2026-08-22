# 📋 PLANO DE IMPLEMENTAÇÃO — Agente PDF Autônomo

**Data**: 22/08/2026  
**Status**: Preparação em andamento  
**Objetivo**: Criar agente Hermes autônomo para processamento de PDFs (ebooks de costuras) seguindo normas ABNT

---

## 🎯 RESUMO DO PROJETO

### Objetivo
Agente autônomo que:
1. Lê PDFs de entrada inteiros
2. Extrai texto e imagens preservando posições
3. Cria novos PDFs com normas ABNT
4. Inclui nome "Rodrigo Carlos Moraes" em todas as páginas
5. Gera índice clicável com menu
6. Estrutura com etapas seguindo protocolo 3.2

### Alcance
- Leitura de PDF → Extração texto + imagens
- Processamento → Análise estrutural
- Geração → HTML/CSS com normas ABNT
- Output → PDF final com numeração, figuras, referências

---

## 🏗️ PHASES DE IMPLEMENTAÇÃO

### FASE 1: PREPARAÇÃO DO AMBIENTE ✅
Status: Concluído

| Tarefa | Status | Detalhes |
|--------|--------|----------|
| Criar diretório base | ✅ | `C:/Users/Qb/Desktop/editrpdf/` |
| Instalar PyMuPDF | ✅ | `pip install pymupdf` |
| Instalar pypdf | ✅ | `pip install pypdf` |
| Verificar Node.js | ✅ | Encontrado em `Program Files/nodejs/` |
| Criar estrutura de pastas | ✅ | input/, output/, temp/, references/ |
| Criar skill pdf-agent | ✅ | Skill criado com regras ABNT |
| Criar tools/pdf_tool.py | ✅ | Ferramentas de leitura e criação |

---

### FASE 2: VALIDAÇÃO DAS FERRAMENTAS ⏳
Status: Em andamento

| Tarefa | Status | Detalhes |
|--------|--------|----------|
| Testar leitura PDF | 🔄 | Precisa de arquivo de teste |
| Testar extração imagens | 🔄 | Via PyMuPDF |
| Testar HTML→PDF | 🔄 | Ajustar wkhtmltopdf/puppeteer |
| Validar normas ABNT | 🔄 | CSS abnt_template() |

---

### FASE 3: PROCESSAMENTO DO PDF ORIGINAL ⏳
Status: Aguardando

| Tarefa | Status | Detalhes |
|--------|--------|----------|
| Copiar PDF para input/ | ❌ | Aguardando arquivo original |
| Ler todas as páginas | ❌ | `read_pdf(path, "all")` |
| Extrair imagens | ❌ | `pdf_extract_images(path)` |
| Identificar estrutura | ❌ | Mapear capítulos/etapas |
| Criar menu clicável | ❌ | Gerar índice HTML |
| Preservar posições fotos | ❌ | Mapear coordenadas |

---

### FASE 4: GERAÇÃO DO EBOOK ⏳
Status: Aguardando

| Tarefa | Status | Detalhes |
|--------|--------|----------|
| Criar template ABNT | ❌ | Fonte 12pt, margem 2.5cm |
| Inserir nome autor | ❌ | "Rodrigo Carlos Moraes" em todas |
| Gerar capítulos | ❌ | Estrutura com títulos/subtítulos |
| Etapas protocolo 3.2 | ❌ | Ação/Porquê/Resultado/Erro |
| Inserir imagens | ❌ | Posições corretas |
| Criar referências ABNT | ❌ | NBR 6023/6024 |
| Gerar índice clicável | ❌ | Links para capítulos |
| Exportar PDF | ❌ | `html_to_pdf_abnt()` |

---

### FASE 5: VALIDAÇÃO FINAL ⏳
Status: Aguardando

| Tarefa | Status | Detalhes |
|--------|--------|----------|
| Verificar normas ABNT | ❌ | Checklist ABNT |
| Verificar numeração | ❌ | Superior direito |
| Verificar fontes | ❌ | Times New Roman 12pt |
| Verificar espaçamento | ❌ | 1,5 entre linhas |
| Verificar figuras | ❌ | Legendadas |
| Verificar menu | ❌ | Links funcionando |
| Testar leitura | ❌ | Verificar qualidade PDF |

---

## 📊 MÉTRICAS DE SUCESSO

- ✅ PDF de saída gerado
- ✅ Normas ABNT aplicadas
- ✅ Nome autor em todas páginas
- ✅ Menu clicável funcionando
- ✅ Imagens nas posições corretas
- ✅ Etapas com protocolo 3.2
- ✅ PDF legível e profissional

---

## 🔧 COMANDOS PARA TESTE

```bash
# Testar leitura PDF
python -c "from tools.pdf_tool import read_pdf; print(read_pdf('./input/teste.pdf'))"

# Testar extração imagem
python -c "from tools.pdf_tool import pdf_extract_images; print(pdf_extract_images('./input/teste.pdf'))"

# Verificar estrutura
ls -la C:/Users/Qb/Desktop/editrpdf/
```

---

## 📞 INTERFACE PARA O USUÁRIO

**AGUARDANDO**: Informe o arquivo PDF original em `C:/Users/Qb/Desktop/editrpdf/input/`

Comando a executar quando o arquivo estiver pronto:
```
Processar PDF: path="./input/[nome_do_arquivo].pdf", acoes=["ler_tudo", "extrair_fotos", "recriar_menu", "gerar_abnt"]
```

---

## 📝 PRÓXIMOS PASSOS

1. **Usuário**: Fornecer PDF original em `input/`
2. **Sistema**: Ler e analisar estrutura
3. **Sistema**: Extrair texto e imagens
4. **Sistema**: Gerar HTML com normas ABNT
5. **Sistema**: Converter para PDF final

---

**Status Atual**: Preparação concluída, aguardando arquivo de entrada.

`Feito com Master Rules Claude v9.0 + Normas ABNT`