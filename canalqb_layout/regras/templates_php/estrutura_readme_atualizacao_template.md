# 📖 Template - Atualização README.html

## 📋 FINALIDADE
Template universal para atualização automática do README.html quando novos templates/prompts são criados

## 🎯 USO OBRIGATÓRIO
O LLM deve usar este template **sempre** que criar ou modificar qualquer arquivo de regra/template

---

## 🔧 PROCESSO AUTOMÁTICO

### **📋 1. Antes de Finalizar Qualquer Criação:**
1. **Validar**: Verificar se arquivo segue master_rules.md
2. **Documentar**: Preparar conteúdo para README.html
3. **Localizar**: `[RAIZ_DO_PROJETO]/regras/readme.html`

### **📋 2. Conteúdo Obrigatório:**

#### **🎯 Para Regras LLM (prompt_de_llms/):**
```html
<!-- SEÇÃO: NOVA REGRA LLM -->
<div class="card mb-3" data-search="regra llm {{NOME_ARQUIVO}} {{FUNCIONALIDADE}}">
    <div class="card-header bg-primary text-white">
        <h6 class="mb-0">🤖 {{NOME_ARQUIVO}}</h6>
    </div>
    <div class="card-body">
        <p><strong>Finalidade:</strong> {{FINALIDADE}}</p>
        <p><strong>Tipo:</strong> Regra LLM</p>
        <p><strong>Uso:</strong> {{EXEMPLO_USO}}</p>
        <p><strong>Data:</strong> {{DATA_CRIACAO}}</p>
        <div class="alert alert-info">
            <strong>Como solicitar:</strong> {{COMANDO_SOLICITACAO}}
        </div>
    </div>
</div>
```

#### **🎨 Para Templates PHP (templates_php/):**
```html
<!-- SEÇÃO: NOVO TEMPLATE PHP -->
<div class="card mb-3" data-search="template php {{NOME_ARQUIVO}} {{FUNCIONALIDADE}}">
    <div class="card-header bg-success text-white">
        <h6 class="mb-0">🎨 {{NOME_ARQUIVO}}</h6>
    </div>
    <div class="card-body">
        <p><strong>Finalidade:</strong> {{FINALIDADE}}</p>
        <p><strong>Tipo:</strong> Template PHP</p>
        <p><strong>Uso:</strong> {{EXEMPLO_USO}}</p>
        <p><strong>Data:</strong> {{DATA_CRIACAO}}</p>
        <div class="alert alert-success">
            <strong>Como usar:</strong> {{COMANDO_USO}}
        </div>
        <div class="mt-2">
            <strong>Dependências:</strong>
            <ul class="list-unstyled">
                {{DEPENDENCIAS}}
            </ul>
        </div>
    </div>
</div>
```

#### **🗄️ Para Templates SQL (templates_php/):**
```html
<!-- SEÇÃO: NOVO TEMPLATE SQL -->
<div class="card mb-3" data-search="template sql {{NOME_ARQUIVO}} banco de dados">
    <div class="card-header bg-info text-white">
        <h6 class="mb-0">🗄️ {{NOME_ARQUIVO}}</h6>
    </div>
    <div class="card-body">
        <p><strong>Finalidade:</strong> {{FINALIDADE}}</p>
        <p><strong>Tipo:</strong> Template SQL</p>
        <p><strong>Banco:</strong> {{TIPO_BANCO}}</p>
        <p><strong>Uso:</strong> {{EXEMPLO_USO}}</p>
        <p><strong>Data:</strong> {{DATA_CRIACAO}}</p>
        <div class="alert alert-info">
            <strong>Exemplo de solicitação:</strong> {{EXEMPLO_SOLICITACAO}}
        </div>
    </div>
</div>
```

### **📋 3. Variáveis do Template:**
- `{{NOME_ARQUIVO}}`: Nome do arquivo criado
- `{{FUNCIONALIDADE}}`: Funcionalidade principal
- `{{FINALIDADE}}`: Finalidade do arquivo
- `{{EXEMPLO_USO}}`: Exemplo prático de uso
- `{{DATA_CRIACAO}}`: Data de criação
- `{{COMANDO_SOLICITACAO}}`: Comando para solicitar
- `{{COMANDO_USO}}`: Como usar o template
- `{{TIPO_BANCO}}`: Tipo de banco de dados
- `{{EXEMPLO_SOLICITACAO}}`: Exemplo de solicitação
- `{{DEPENDENCIAS}}`: Lista de dependências

---

## 🔧 INTEGRAÇÃO COM README.HTML

### **📋 Localização das Seções:**
1. **Regras LLM**: Adicionar após seção "🤖 Regras para LLMs"
2. **Templates PHP**: Adicionar após seção "🎨 Templates PHP"
3. **Templates SQL**: Adicionar após seção "🗄️ Templates SQL"

### **📋 Formato HTML:**
- Usar Bootstrap 5 classes
- Cards com `card`, `card-header`, `card-body`
- Cores: `bg-primary` (regras), `bg-success` (templates), `bg-info` (SQL)
- `data-search` para funcionalidade de busca
- `alert` para destaques

---

## 📋 EXEMPLOS PRÁTICOS

### **🤖 Exemplo - Regra LLM:**
```html
<!-- Variáveis:
NOME_ARQUIVO = regra_llms_criacao_tabelas_supabase.md
FUNCIONALIDADE = criação de tabelas supabase
FINALIDADE = Ensinar LLMs a criar tabelas corretamente
EXEMPLO_USO = "Crie uma tabela para registrar puzzles"
DATA_CRIACAO = 06/03/2026
COMANDO_SOLICITACAO = "Por favor, crie uma tabela seguindo o regra_llms_criacao_tabelas_supabase.md"
-->

<div class="card mb-3" data-search="regra llm regra_llms_criacao_tabelas_supabase.md criação de tabelas supabase">
    <div class="card-header bg-primary text-white">
        <h6 class="mb-0">🤖 regra_llms_criacao_tabelas_supabase.md</h6>
    </div>
    <div class="card-body">
        <p><strong>Finalidade:</strong> Ensinar LLMs a criar tabelas no Supabase seguindo master_rules.md</p>
        <p><strong>Tipo:</strong> Regra LLM</p>
        <p><strong>Uso:</strong> "Crie uma tabela para registrar puzzles Bitcoin encontrados"</p>
        <p><strong>Data:</strong> 06/03/2026</p>
        <div class="alert alert-info">
            <strong>Como solicitar:</strong> "Por favor, crie uma tabela seguindo o regra_llms_criacao_tabelas_supabase.md"
        </div>
    </div>
</div>
```

### **🎨 Exemplo - Template SQL:**
```html
<!-- Variáveis:
NOME_ARQUIVO = estrutura_sql_criacao_tabelas.md
FUNCIONALIDADE = criação de tabelas
FINALIDADE = Template universal para criação de tabelas Supabase
EXEMPLO_USO = "Criar tabela puzzles_encontrados"
DATA_CRIACAO = 06/03/2026
TIPO_BANCO = PostgreSQL/Supabase
EXEMPLO_SOLICITACAO = "Crie uma tabela para puzzles encontrados com WIFs"
-->

<div class="card mb-3" data-search="template sql estrutura_sql_criacao_tabelas.md banco de dados">
    <div class="card-header bg-info text-white">
        <h6 class="mb-0">🗄️ estrutura_sql_criacao_tabelas.md</h6>
    </div>
    <div class="card-body">
        <p><strong>Finalidade:</strong> Template universal para criação de tabelas no Supabase</p>
        <p><strong>Tipo:</strong> Template SQL</p>
        <p><strong>Banco:</strong> PostgreSQL/Supabase</p>
        <p><strong>Uso:</strong> "Criar tabela puzzles_encontrados com todos os campos necessários"</p>
        <p><strong>Data:</strong> 06/03/2026</p>
        <div class="alert alert-info">
            <strong>Exemplo de solicitação:</strong> "Por favor, crie uma tabela para puzzles encontrados usando o template estrutura_sql_criacao_tabelas.md"
        </div>
    </div>
</div>
```

---

## 📋 ORIENTAÇÃO AUTOMÁTICA PARA O USUÁRIO

### **📋 Mensagem Padrão:**
```
🎯 TEMPLATE/PROMPT CRIADO COM SUCESSO!

📋 Arquivo criado: {{NOME_ARQUIVO}}
📍 Localização: regras/{{PASTA}}/{{NOME_ARQUIVO}}

📖 ATUALIZAÇÃO OBRIGATÓRIA DO README.HTML:
1. ✅ Arquivo validado conforme master_rules.md
2. ✅ Template/prompt documentado
3. ⚠️ README.html precisa ser atualizado

🔧 Para atualizar o README.html:
1. Abra: `[RAIZ_DO_PROJETO]/regras/readme.html`
2. Localize a seção apropriada (Regras LLM / Templates PHP / Templates SQL)
3. Adicione o card HTML correspondente usando o template: estrutura_readme_atualizacao_template.md
4. Salve as alterações

📋 Conteúdo para adicionar:
[Inserir o card HTML gerado automaticamente]

🎯 Próximos passos:
- Verifique se o README.html foi atualizado corretamente
- Teste a funcionalidade de busca com data-search
- Valide se todos os links funcionam

🚨 IMPORTANTE: O README.html deve ser atualizado SEMPRE que criar/modificar templates ou prompts!
```

---

## 📋 VALIDAÇÃO FINAL

### **✅ Checklist Antes de Finalizar:**
- [ ] Template/prompt criado segue master_rules.md?
- [ ] Nomenclatura correta (`regra_llms_` ou `estrutura_php_`)?
- [ ] Conteúdo para README.html preparado?
- [ ] Usuário orientado sobre atualização?
- [ ] Exemplo prático incluído?

### **✅ Processo Concluído Quando:**
- Arquivo criado ✅
- README.html atualizado ✅
- Usuário notificado ✅
- Busca funcionando ✅

---

## 🎯 OBJETIVO FINAL

Este template garante que:
✅ **Documentação automática** de todo novo conteúdo
✅ **Busca funcional** com data-search
✅ **Processo padronizado** para LLMs
✅ **Orientação clara** para usuários
✅ **Integração completa** com README.html
✅ **Validação obrigatória** antes de finalizar

**Template universal para documentação automática do README.html!** 🎯✨
