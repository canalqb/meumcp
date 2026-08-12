# 🗄️ REGRA LLM - CRIAÇÃO DE TABELAS SUPABASE

## 🎯 FINALIDADE
Regra para LLMs criarem tabelas no Supabase seguindo as regras do master_rules.md

---

## 📋 PROCESSO OBRIGATÓRIO

### **🔧 1. ANTES DE QUALQUER CRIAÇÃO:**
1. **LER OBRIGATORIAMENTE**: `regras/master_rules.md` (linhas 1-20)
2. **LER TEMPLATE**: `regras/templates_php/estrutura_sql_criacao_tabelas.md`
3. **IDENTIFICAR TIPO**: Determinar se tabela é normal ou de chat
4. **SEGUIR NOMENCLATURA**: Usar padrão `regra_llms_{funcionalidade}.md`

### **🔧 2. DURANTE A CRIAÇÃO:**
1. **USAR TEMPLATE CORRETO**: Basear-se em `estrutura_sql_criacao_tabelas.md`
2. **RESPEITAR PREFIXOS**: 
   - Tabelas normais: Sem prefixo
   - Tabelas de chat: Prefixo `ovo_ia_`
3. **SEGUIR NOMENCLATURA**: Ex: `puzzles_encontrados`, `ovo_ia_chat_messages`
4. **VALIDAR SINTAXE**: PostgreSQL compatível

### **🔧 3. APÓS A CRIAÇÃO:**
1. **REGISTRAR**: Adicionar ao inventário em `master_rules.md`
2. **DOCUMENTAR**: Atualizar `readme.html` se necessário

---

## 📋 EXEMPLOS DE USO

### **🧩 Tabela Normal (Puzzles):**
```
USUÁRIO: "Crie uma tabela para registrar puzzles Bitcoin encontrados"

LLM DEVE:
1. ✅ Ler master_rules.md (regra de ouro)
2. ✅ Ler estrutura_sql_criacao_tabelas.md
3. ✅ Identificar: Tabela normal (sem prefixo)
4. ✅ Usar template: puzzles_encontrados
5. ✅ Gerar SQL sem prefixo ovo_ia_
6. ✅ Validar sintaxe PostgreSQL
7. ✅ Retornar SQL pronto para uso
```

### **💬 Tabela de Chat:**
```
USUÁRIO: "Crie uma tabela para mensagens do chat"

LLM DEVE:
1. ✅ Ler master_rules.md (regra de ouro)
2. ✅ Ler estrutura_sql_criacao_tabelas.md
3. ✅ Identificar: Tabela de chat
4. ✅ Usar template: ovo_ia_chat_messages
5. ✅ Gerar SQL com prefixo ovo_ia_
6. ✅ Validar sintaxe PostgreSQL
7. ✅ Retornar SQL pronto para uso
```

---

## 📋 VALIDAÇÃO AUTOMÁTICA

### **✅ Checklist Antes de Responder:**
- [ ] Li master_rules.md?
- [ ] Identificou tipo de tabela?
- [ ] Usou template correto?
- [ ] Respeitou nomenclatura?
- [ ] Validou sintaxe PostgreSQL?
- [ ] Documentou adequadamente?

### **✅ Sintaxe PostgreSQL Obrigatória:**
- CREATE TABLE IF NOT EXISTS
- Tipos de dados corretos (BIGINT, VARCHAR, TIMESTAMP, JSONB)
- Constraints CHECK adequadas
- Índices otimizados (hash para textuais, b-tree para exatos)
- Triggers para updated_at
- Comentários descritivos

---

## 📋 COMANDOS PROIBIDOS

### **🚨 NUNCA USAR ESTES COMANDOS:**
- `rm -rf` (PowerShell: `Remove-Item -Path -Recurse -Force`)
- `chmod` (PowerShell: não usar para permissões)
- `chown` (PowerShell: não usar para propriedade)
- Comandos Linux/macOS específicos

### **✅ ALTERNATIVAS SEGURAS:**
- Remover diretórios: `Remove-Item -Path -Recurse -Force`
- Listar conteúdo: `Get-ChildItem`
- Criar arquivos: `New-Item` ou `Set-Content`

---

## 📋 INTEGRAÇÃO COM SISTEMA

### **🔧 Atualização de master_rules.md:**
Adicionar novo template na seção correspondente:
```markdown
### 📄 Regras de Desenvolvimento (`regras/templates_php/`)
- [ ] `estrutura_sql_criacao_tabelas.md` ✅
```

### **🔧 Atualização de readme.html:**
Adicionar seção sobre criação de tabelas:
```markdown
## 🗄️ Criação de Tabelas
1. **Tipo**: Normal ou Chat
2. **Template**: SQL gerado automaticamente
3. **Validação**: Sintaxe PostgreSQL
4. **Download**: SQL pronto para uso
```

---

## 📋 ERROS COMUNS E CORREÇÕES

### **❌ Erro: Prefixo Incorreto**
- **Problema**: Usar `ovo_ia_` em tabela normal
- **Correção**: Remover prefixo para tabelas normais

### **❌ Erro: Nomenclatura Incorreta**
- **Problema**: Nome fora do padrão snake_case
- **Correção**: Usar `nome_descritivo_snake_case`

### **❌ Erro: Sintaxe PostgreSQL**
- **Problema**: Funções ou tipos não compatíveis
- **Correção**: Usar sintaxe PostgreSQL padrão

---

## 🎯 OBJETIVO FINAL

Esta regra garante que:
✅ **Padronização**: Todas as tabelas seguem master_rules.md
✅ **Qualidade**: SQL otimizado e documentado
✅ **Segurança**: RLS onde necessário
✅ **Manutenibilidade**: Estrutura clara e organizada
✅ **Consistência**: Processo padronizado para LLMs

**Regra obrigatória para criação de tabelas Supabase!** 🎯✨
