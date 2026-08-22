# 📋 REGRAS DE EDIÇÃO — PDF Agent Autônomo

**Propósito**: Este arquivo DEVE ser lido pelo agente antes de qualquer operação de edição, criação, modificação ou melhoria.

---

## 🎯 QUANDO USAR

O agente deve ler este arquivo quando for solicitado:

- **Editar PDF**
- **Criar novo PDF**
- **Modificar conteúdo**
- **Melhorar qualidade**

---

## 🏗️ ESTRUTURA DE PASTAS

```
C:/Users/Qb/Desktop/editrpdf/
├── Input/          # PDFs fonte para processamento
├── Editar/          # PDFs para revisão (USUÁRIO COLOCA AQUI)
├── Editado/        # PDFs revisados (SAÍDA FINAL)
├── Output/         # PDFs gerados automaticamente
├── Temp/           # Intermediários HTML/CSS
├── References/     # Imagens extraídas
├── Checks/         # Arquivos CSV de controle
└── ...
```

---

## 📊 CONTROLE DE ARQUIVOS (CSV)

**Arquivo**: `Checks/arquivos_para_editar.csv`

### Formato obrigatório:
```csv
arquivo,status,data_criacao,data_conclusao,observacoes
"nome_arquivo.pdf","pendente","2026-08-22 10:00","","notas"
```

### Ações:
- Quando colocar PDF em `Editar/` → adicionar linha no CSV com `status=pendente`
- Quando revisar em `Editado/` → atualizar linha com `status=concluido` e `data_conclusao`

---

## 📋 REGRAS DO USUÁRIO

1. **Nome Completo**: Rodrigo Carlos Moraes (em todas as páginas)
2. **Leitura**: Todas as páginas do PDF
3. **Título + Subtítulo**: Estrutura de navegação
4. **Menu**: Índice clicável com links
5. **Etapas**: Protocolo 3.2 (Ação/Porquê/Resultado/Erro)
6. **Fotos**: Preservar posições do PDF original

---

## 📊 NORMAS ABNT

- Fonte: Times New Roman 12pt ou Arial
- Espaçamento: 1,5 entre linhas
- Margens: 2,5 cm (todos os lados)
- Numeração: Superior direito
- Capítulos: Negrito 14pt
- Seções: Itálico 12pt
- Figuras: Legendadas, numeradas

---

## 🔧 PROCESSO DE EDIÇÃO

### Passo 1: Ler PDF
```
read_pdf(path="./Editar/arquivo.pdf")
→ Texto + Metadados + Imagens
```

### Passo 2: Extrair recursos
```
pdf_extract_images(path, output_dir="./References/")
```

### Passo 3: Criar HTML
```
abnt_template() → HTML com normas ABNT
abnt_header(title, subtitle, author="Rodrigo Carlos Moraes")
```

### Passo 4: Gerar PDF
```
html_to_pdf_abnt(html, "./Editado/arquivo.pdf")
```

### Passo 5: Atualizar CSV
```
status → "concluido"
data_conclusão → timestamp
```

---

## ✅ REQUISITOS ANTES DE PROCESSAR

1. PDF em `Editar/`
2. Pasta `Editado/` criada
3. CSV atualizado
4. Espaço em disco disponível
5. Dependências instaladas (PyMuPDF, pypdf)

---

## ❌ ERROS COMUNS A EVITAR

- Não modificar PDF sem conversão HTML→PDF
- Não perder imagem original
- Não esquecer do nome do autor
- Não esquecer do menu clicável
- Não esquecer do índice

---

## 📞 COMUNICAÇÃO COM O USUÁRIO

Quando o processamento terminar:
```
✅ PDF processado: Editado/nome_arquivo.pdf
📄 Páginas: X
🖼️ Imagens: Y
🔗 Índice: Criado
```

---

**Este é o arquivo que o agente deve lER SEMPRE antes de qualquer edição.**