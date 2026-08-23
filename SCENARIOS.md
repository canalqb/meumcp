# 📚 CENÁRIOS DE USO — PDF Agent Autônomo

Este arquivo documenta os comandos e cenários disponíveis após o agente estar pronto.

---

## 🎯 CENÁRIOS PRINCIPAIS

### 1. Processar PDF Existente (Workflow Principal)

**Situação**: Usuário coloca um PDF em `Editar/` para revisão

**Comando**:
```
{Processar PDF: path="./Editar/nome_arquivo.pdf", acoes=["ler_tudo", "extrair_fotos", "recriar_menu", "gerar_abnt", "salvar"]}
```

**Resultados**:
- PDF processado em `Editado/nome_arquivo.pdf`
- HTML intermediário em `temp/`
- Imagens em `references/`
- CSV atualizado

---

### 2. Criar Novo Ebook do Zero

**Situação**: Criar um novo ebook de costura

**Comando**:
```
{Criador PDF: tema="Livro de Costuras", autor="Rodrigo Carlos Moraes", formato="ebook", paginas=50, normas="ABNT"}
```

**Resultados**:
- Ebook completo em `output/`
- Estrutura com capítulos, sumário, etapas

---

### 3. Editar PDF Já Processado

**Situação**: Refazer ou melhorar um PDF já criado

**Comando**:
```
{Editar PDF: path="./Editado/arquivo.pdf", acoes=["ler_tudo", "extrair", "recriar", "salvar"]}
```

**Resultados**:
- PDF melhorado em `Editado/arquivo_v2.pdf`
- Notas adicionadas

---

### 4. Extrair Conteúdo de PDF

**Situação**: Apenas extrair texto ou imagens

**Comando**:
```
{Extrair PDF: path="./input/arquivo.pdf", tipo="texto" ou tipo="imagens"}
```

**Resultados**:
- Texto em `temp/arquivo_texto.txt`
- ou Imagens em `references/`

---

### 5. Comparar PDFs

**Situação**: Verificar diferenças entre dois PDFs

**Comando**:
```
{Comparar PDFs: pdf1="./input/v1.pdf", pdf2="./input/v2.pdf"}
```

**Resultados**:
- Diferenças identificadas
- Relatório gerado

---

### 6. Unir PDFs

**Situação**: Combinar múltiplos PDFs

**Comando**:
```
{Juntar PDFs: arquivos=["a.pdf", "b.pdf", "c.pdf"], output="./output/resultado.pdf"}
```

**Resultados**:
- PDF combinado em `output/resultado.pdf`

---

### 7. Dividir PDF

**Situação**: Extrair partes específicas de um PDF

**Comando**:
```
{Dividir PDF: path="./input/arquivo.pdf", ranges=["1-5", "6-10"]}
```

**Resultados**:
- PDFs separados em `temp/`

---

### 8. Verificar CSV de Controle

**Situação**: Ver qual PDF está pendente

**Comando**:
```
{Listar CSV: path="./checks/arquivos_para_editar.csv"}
```

**Resultados**:
- Lista de arquivos pendentes/comcluídos

---

### 9. Análise Completa de PDF

**Situação**: Analisar estrutura de um documento

**Comando**:
```
{Analisar PDF: path="./input/arquivo.pdf"}
```

**Resultados**:
- Palavras-chave
- Estrutura de capítulos
- Recomendações

---

## 📋 REGRAS PARA USO

### Sempre seguir:
1. **LER REGRAS_EDITACAO.md** antes de qualquer edição
2. **Verificar CSV** antes de processar
3. **Usar Editar/** para arquivos do usuário
4. **Usar Editado/** para saída final

### Não fazer:
- Modificar PDF diretamente (sem HTML intermediário)
- Esquecer do nome do autor (Rodrigo Carlos Moraes)
- Alterar posições de imagens

---

## 🚀 Fluxo Recomendado

```
1. Usuário coloca PDF em Editar/
2. Agente detecta → adiciona ao CSV (status=pendente)
3. Processar PDF → HTML intermediário
4. Gerar PDF final → Editado/
5. Atualizar CSV → status=concluido
6. Validar PDF
```

---

**Status**: ✅ Pronto para uso

`Feito com Master Rules Claude v9.0 + Normas ABNT`