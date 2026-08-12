# 📚 Regra LLM: Explicações em "Leitura Obrigatória"

## 🎯 Objetivo
Estabelecer que TODAS as explicações sobre o que o usuário está solicitando devem ser criadas na pasta `leitura obrigatória` em vez de serem criadas na raiz do projeto.

## 📋 Regra Fundamental

### ✅ OBRIGATÓRIO
- **TODA** explicação sobre solicitações do usuário deve ser criada em `leitura obrigatória/`
- **NUNCA** criar arquivos de explicação na raiz do projeto
- **SEMPRE** usar nomenclatura padronizada

### ❌ PROIBIDO
- Criar arquivos de explicação na raiz (`/`)
- Misturar explicações com código funcional
- Usar nomes genéricos sem padrão

## 🗂️ Estrutura e Nomenclatura

### Localização Obrigatória
```
leitura obrigatória/
├── EXPLICA_{funcionalidade}.md
├── EXPLICA_{sistema}_{modulo}.md
├── EXPLICA_{correcao}_{problema}.md
└── EXPLICA_{novo}_{recurso}.md
```

### Padrão de Nomes
- **Funcionalidades**: `EXPLICA_{funcionalidade}.md`
  - Ex: `EXPLICA_sistema_login.md`
  - Ex: `EXPLICA_modulo_ovo.md`
  - Ex: `EXPLICA_pagina_admin.md`

- **Correções**: `EXPLICA_correcao_{problema}.md`
  - Ex: `EXPLICA_correcao_menu_navigation.md`
  - Ex: `EXPLICA_correcao_sql_error.md`

- **Novos Recursos**: `EXPLICA_novo_{recurso}.md`
  - Ex: `EXPLICA_novo_sistema_pagamento.md`
  - Ex: `EXPLICA_novo_template_cards.md`

## 📝 Conteúdo Obrigatório da Explicação

### Estrutura do Arquivo
```markdown
# 📚 Explicação: [Nome da Funcionalidade]

## 🎯 O que o usuário pediu
[Breve descrição da solicitação do usuário]

## 🏗️ O que foi criado
[Detalhes técnicos do que foi implementado]

## 📍 Localização dos arquivos
- **Principal**: `caminho/do/arquivo.php`
- **Templates**: `templates_php/...`
- **Config**: `config/...`

## 🔧 Como funciona
[Explicação técnica do funcionamento]

## 🚀 Como usar
[Instruções de uso para o usuário]

## ⚙️ Configurações necessárias
[Configurações ou dependências]

## 📋 Requisitos
- PHP 8.0+
- MySQLi
- Bootstrap 5
- FontAwesome 6

## 🔍 Validação
- ✅ Semântica HTML5
- ✅ Acessibilidade WCAG AA
- ✅ Responsividade Mobile
- ✅ Segurança CSRF
```

## 🔄 Fluxo de Trabalho

### 1. Receber Solicitação
- Analisar o que o usuário pediu
- Identificar tipo de funcionalidade

### 2. Criar Explicação
- Criar arquivo em `leitura obrigatória/`
- Seguir estrutura obrigatória
- Usar nomenclatura padronizada

### 3. Implementar Código
- Seguir templates universais
- Aplicar master rules
- Documentar no README.html

### 4. Validar
- Verificar 9 métricas de qualidade
- Testar funcionalidade
- Confirmar explicação clara

## 🎯 Exemplos Práticos

### Exemplo 1: Sistema de Login
**Arquivo**: `leitura obrigatória/EXPLICA_sistema_login.md`

### Exemplo 2: Correção de Menu
**Arquivo**: `leitura obrigatória/EXPLICA_correcao_menu_navigation.md`

### Exemplo 3: Novo Módulo
**Arquivo**: `leitura obrigatória/EXPLICA_novo_modulo_pagamento.md`

## 🚨 Verificação Obrigatória

Antes de finalizar, verificar:
- [ ] Explicação criada em `leitura obrigatória/`
- [ ] Nome segue padrão `EXPLICA_...md`
- [ ] Conteúdo segue estrutura obrigatória
- [ ] Nenhum arquivo de explicação na raiz
- [ ] README.html atualizado (se aplicável)

## 🔗 Integração com Outras Regras

Esta regra complementa:
- **Master Rules**: Ciclo de leitura obrigatório
- **Templates**: Uso obrigatório de templates universais
- **Qualidade**: Validação 100% das métricas

## 📈 Benefícios

- **Organização**: Explicações centralizadas
- **Manutenibilidade**: Fácil localização
- **Documentação**: Histórico claro
- **Padronização**: Consistência no projeto

**ESTA REGRA É OBRIGATÓRIA E SOBREPÕE QUALQUER OUTRA PRÁTICA!**
