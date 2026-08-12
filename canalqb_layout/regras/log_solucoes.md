### [2026-02-28 20:41:05] Integração de Diretrizes UI WCAG e Estrutura PHP Index
- **Arquivo criado**: regras/php_ui_rules.md
- **Tarefa**: Adição de novo regulamento normativo WCAG 2.2 e design (ABNT/Material/Bootstrap 5), obrigando que a injeção do HTML Head/Body seja centralizada apenas no `index.php`. O master_rules.md e os manuais GUIA_CRIACAO_PAGINAS.md e VALIDACAO_ESTRUTURA.md foram atualizados.
- **Código gerado**:
```markdown
## 🏗️ 0. REGRA ESTRUTURAL DO SISTEMA (PHP)
- **O ÚNICO arquivo do sistema que pode conter as tags `<html>`, `<head>` e `<body>` é o `index.php`.**
... (Consultar php_ui_rules.md)
```
- **Dependências**: regras/master_rules.md, regras/GUIA_CRIACAO_PAGINAS.md, regras/VALIDACAO_ESTRUTURA.md

### [2026-02-28 20:48:41] Remodelagem de Acessibilidade UI do Componente Webhook Manager
- **Arquivo Editado**: pages/admin/webhook_manager.php
- **Tarefa**: Adequação de `<section>`/`<header>`/`<article>`, links do tipo id/for cruzando os inputs com as labels. Injeção de `aria-label` e `aria-hidden` para que a tela obedeça inteiramente o checklist do `php_ui_rules.md` e WCAG 2.2.
- **Código gerado**:
```php
<section class="container py-4">
    <header class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 fw-bold mb-0">Integração Webhook (GAS)</h1>...
...
<label for="integration_name" class="form-label small fw-bold">NOME DA INTEGRAÇÃO</label>
<input type="text" id="integration_name" name="name" class="form-control" placeholder="Ex: Planilha Principal" required>
```
- **Dependências**: regras/php_ui_rules.md

### [2026-03-02 10:20:00] Refatoração de Perfil, Gamificação e Prefixos de Banco
- **Arquivos criados/modificados**: 
    - `pages/perfil.php` (Refeito com Premium UI)
    - `pages/admin/ajax/save_profile.php` (Lógica de salvamento multitarefa)
    - `pages/admin/ajax/register_activity.php` (Novo endpoint de XP)
    - +40 arquivos PHP refatorados para novos nomes de tabelas.
- **Tarefa**: Implementar sistema de perfil robusto com redes sociais, níveis por atividade, sistema de indicações e padronização total de prefixos de tabelas SQL conforme regras.
- **Código gerado**: Refatoração atômica de SQL e Renomeação Massiva via Script PHP.
- **Dependências**: `config.php`, `js/scripts.js`, `css/styles.css`

### [2026-03-02 10:35:00] Implementação de Padrões Universais Web, Acessibilidade e SEO
- **Arquivos criados**: `regras/web_standards_rules.md`, `prompt/web_standards_rules.md`
- **Arquivo editado**: `regras/master_rules.md`
- **Tarefa**: Instituição de normas obrigatórias baseadas em ARIA, WCAG, W3C e Schema.org para forçar conformidade técnica em gerações de código frontend.
- **Resumo**: Atualização das Master Rules para obrigar leitura e aplicação de acessibilidade e SEO estruturado em todas as tarefas.
- **Dependências**: `regras/master_rules.md`

### [2026-03-02 11:00:00] Reorganização Logística e Norma ABNT 2026
- **Arquivos criados**: `regras/prompts_php/abnt_document_rules_2026.md`, `regras/templates/Prompt/abnt_document_rules_2026.md`
- **Arquivo editado**: `regras/master_rules.md`
- **Tarefa**: Reestruturação de pastas em `regras/` por categorias (`prompts_php`, `templates_php`, `prompt_de_llms`, `templates/Prompt`) para automação de leitura recursiva e implementação da Norma ABNT 2026.
- **Resumo**: Movimentação massiva de manuais e templates para subpastas organizadas. O `master_rules.md` agora obriga varredura recursiva em todas as subpastas.
- **Dependências**: `regras/master_rules.md`, `regras/prompts_php/*`

### [2026-03-02 11:45:00] Limpeza Final e Reestruturação Lógica
- **Arquivos reorganizados**: Movimentação total de `templates/`, `{{Readmes}}.md/`, `Prompt/` e arquivos da raiz para subpastas qualificadas (`prompts_php`, `templates_php`, `prompt_de_llms`).
- **Arquivo editado**: `regras/master_rules.md` (Simplificação e obrigatoriedade de leitura recursiva).
- **Tarefa**: Limpeza de pastas residuais e consolidação de diretrizes de desenvolvimento, exemplos e configurações de sistema operacional.
- **Resumo**: O diretório `regras/` agora contém apenas 3 subpastas lógicas e os logs/master_rules na raiz.
- **Dependências**: `regras/*`

### [2026-03-02 11:48:00] Formalização da Regra de Soluções no Master Rules
- **Arquivo editado**: `regras/master_rules.md`
- **Tarefa**: Reintrodução da regra inegociável que obriga LLMs a salvarem arquivos de teste, debug e fix na pasta `solucoes/` seguindo a estrutura modular do projeto.
- **Resumo**: Garantia de que arquivos temporários ou de diagnóstico não poluam a raiz e sejam devidamente documentados.
- **Dependências**: `regras/prompts_php/SISTEMA_MODULAR.md`

### [2026-03-02 11:55:00] Inclusão de Workflow de IA e Inventário Detalhado
- **Arquivo editado**: `regras/master_rules.md`
- **Tarefa**: Reestruturação da "Regra 1" para obrigar o ciclo de vida completo (Leitura -> Workflows -> Revisão -> Entrega). Inclusão de um inventário completo de todos os arquivos de regras e templates para facilitar a leitura por LLMs.
- **Resumo**: Garantia de que cada regra (.md) e template do projeto seja formalmente reconhecido e aplicado durante o desenvolvimento.
- **Dependências**: `regras/**/*`

### [2026-03-02 12:00:00] Padronização Global de Nomenclatura e Comando de Terminal
- **Arquivo editado**: `regras/master_rules.md`, `regras/**/*` (renomeação massiva).
- **Novo arquivo**: `regras/prompt_de_llms/regra_llms_comandos_proibidos.md`.
- **Tarefa**: Padronização total de nomes de arquivos para precisão (ex: `regra_llms_...`, `regra_php_...`, `estrutura_php_...`). Implementação da Regra 3 para registro obrigatório de falhas de comandos no terminal (incompatibilidade de ambiente).
- **Resumo**: O sistema agora possui uma nomenclatura auto-explicativa e um mecanismo de "memória de erros" para evitar que LLMs repitam comandos que falham no sistema Windows/XAMPP.
- **Dependências**: `regras/*`

### [2026-03-06 14:50:00] Implementação do Eggs Hunter V2 (IndexedDB)
- **Arquivo criado**: `js/eggs-hunter.js`, `docs/EGGS_HUNTER.md`
- **Tarefa**: Criar um sistema de verificação de saldo em lote (1000 WIFs) usando IndexedDB para alta performance e persistência local.
- **Resumo**: O sistema agora acumula WIFs geradas e as verifica em lotes de 20 via API blockchain.info ao atingir 1000 unidades. Inclui UI de progresso e modal de resultados.
- **Dependências**: `js/bitcoinjs.min.js`, `js/modal-manager.js`

### [2026-03-06 22:52:00] Implementação de Temas Universais (Claro/Escuro)
- **Arquivos editados**: `index.html`, `css/styles.css`
- **Novo arquivo**: `js/theme-manager.js`
- **Tarefa**: Implementar suporte nativo a temas claro e escuro seguindo a Regra 9 do `master_rules.md`.
- **Resumo**: Adicionadas variáveis CSS para controle de cores, botão de alternância na navbar e script de gerenciamento com persistência no `localStorage` e detecção de preferência do sistema.
- **Dependências**: `regras/master_rules.md`

