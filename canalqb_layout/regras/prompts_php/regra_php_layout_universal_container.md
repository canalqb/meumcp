# 🧩 Prompt Padrão - Layout Universal com Container Centralizado
> Versão: 1.1 (Consolidada) | Padrão Obrigatório para TODAS as Páginas (Admin e Usuário)

## 📋 Descrição
Este prompt define o padrão único e obrigatório para criação de TODAS as páginas filhas do CanalQB, garantindo layout profissional com container centralizado, responsividade e consistência visual em todo o sistema. **Substitui a regra específica de Admin.**

## 🎯 Objetivo
Criar páginas que:
- Usem container centralizado com largura limitada (`.cqb-container`)
- Sejam responsivas com breakpoints inteligentes
- Mantenham consistência com o design system do CanalQB
- Sigam as diretrizes do master_rules.md
- Implementem acessibilidade WCAG AA e semântica HTML5

## 🎨 Regras de Layout Obrigatórias

### Container Centralizado
- **Classe CSS**: `.cqb-container`
- **Largura base**: `max-width: 1200px` (Desktop Standard)
- **Centralização**: `margin: 0 auto`
- **Espaçamento**: `padding: 0 20px`

### Breakpoints Responsivos
- **1400px+**: `max-width: 1280px`
- **1600px+**: `max-width: 1440px`
- **Mobile (< 768px)**: 100% de largura com padding de 15px/20px nas laterais.

### Estrutura HTML Obrigatória (Injeção AJAX)
```html
<div class="page-wrapper [admin-wrapper]">
    <style>
        .cqb-container { max-width: 1200px; margin: 0 auto; padding: 0 20px; }
        @media (min-width: 1400px) { .cqb-container { max-width: 1280px; } }
        @media (min-width: 1600px) { .cqb-container { max-width: 1440px; } }
    </style>
    
    <div class="cqb-container">
        <!-- Conteúdo: Header -> Main -> Section -> Footer (Modal Context) -->
    </div>
</div>
```

## 📋 Estrutura Obrigatória da Página

### 1. Cabeçalho PHP
```php
<?php
/**
 * [Nome da Página]
 * Página com layout centralizado responsivo
 */

// Verificar se sessão já foi iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once(__DIR__ . '/../config/database.php');

// Lógica específica da página aqui
?>
```

### 2. Container Centralizado
- Aplicar CSS do container conforme acima
- Incluir dentro de `.page-wrapper`
- Fechar tags corretamente

### 3. Header da Página (Opcional)
```html
<!-- Header da Página -->
<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="mb-1">
            <i class="fas fa-[icone] [cor]"></i>
            [Título da Página]
        </h1>
        <p class="text-muted mb-0">[Descrição da página]</p>
    </div>
    <button class="btn btn-outline-secondary btn-sm" onclick="history.back()">
        <i class="fas fa-arrow-left"></i> Voltar
    </button>
</div>
```

### 4. Conteúdo Principal
- Cards Bootstrap para agrupar conteúdo
- Tabelas responsivas com `table-responsive`
- Formulários com validação HTML5
- Botões com ícones FontAwesome

### 5. JavaScript
- Usar `showConfirmation()` para confirmações (se necessário)
- `showToast()` para feedback (se necessário)
- AJAX com fetch API (se necessário)

## 🎨 Diretrizes de Design

### Cores e Temas
- Usar variáveis CSS do tema: `--cqb-*`
- Respeitar tema claro/escuro
- Contraste WCAG AA obrigatório
- Sem cores fixas (amarelo proibido)

### Tipografia
- Font-family: Outfit (definido no styles.css)
- Hierarquia clara: h1 > h2 > h3 > h4 > h5 > h6
- Tamanhos responsivos com clamp()

### Espaçamento
- Usar classes Bootstrap: `mb-4`, `mt-3`, `p-4`
- Consistência em todo o layout
- Grid system Bootstrap 5

### Ícones
- FontAwesome 6 exclusivamente
- Ícones semânticos para ações
- Tamanhos consistentes: `fa-sm`, `fa-lg`

## 📋 Componentes Obrigatórios

### Cards
```html
<div class="card mb-4">
    <div class="card-header bg-gradient-[primary|success|warning|danger|info]">
        <h6 class="mb-0">
            <i class="fas fa-[icone]"></i>
            [Título do Card]
        </h6>
    </div>
    <div class="card-body">
        [Conteúdo do card]
    </div>
</div>
```

### Tabelas
```html
<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th>[Coluna 1]</th>
                <th>[Coluna 2]</th>
                <th class="text-end">Ações</th>
            </tr>
        </thead>
        <tbody>
            <!-- Linhas da tabela -->
        </tbody>
    </table>
</div>
```

### Botões de Ação
```html
<button 
    type="button" 
    class="btn btn-sm btn-[primary|success|warning|danger|info|secondary]"
    onclick="[funcao]([id])"
    title="[Tooltip descritivo]"
>
    <i class="fas fa-[icone]"></i>
    [Texto opcional]
</button>
```

## ♿ Acessibilidade Obrigatória

### Semântica HTML5
- `<main>` para conteúdo principal
- `<section>` para seções
- `<header>` para cabeçalhos
- `<nav>` para navegação
- `<footer>` para rodapés

### ARIA
- `aria-label` para botões sem texto
- `aria-describedby` para descrições
- `role="dialog"` para modais
- `aria-modal="true"` para modais ativos

### Teclado
- Tab order lógico
- Enter/Space para ativação
- Esc para fechar modais
- Focus visível em todos os elementos

## 🔧 Integrações Obrigatórias

### CSS
- Variáveis CSS do tema: `--cqb-primary`, `--cqb-bg-white`, etc.
- Media queries para responsividade

### JavaScript (Opcional)
- `showConfirmation()` para confirmações críticas
- `showToast()` para feedback ao usuário
- CSRF tokens em requisições POST

### Backend
- Sessão validada (se necessário)
- Prepared statements para SQL
- Sanitização de inputs
- Respostas AJAX em JSON (se necessário)

## 📋 Validação Obrigatória

### Checklist de Implementação
- [ ] Container centralizado implementado
- [ ] Breakpoints responsivos definidos
- [ ] Semântica HTML5 correta
- [ ] Atributos ARIA presentes
- [ ] Cores do tema respeitadas
- [ ] Contraste WCAG AA
- [ ] Ícones FontAwesome 6
- [ ] Validação HTML5 (se formulário)
- [ ] CSRF tokens implementados (se necessário)
- [ ] Feedback visual adequado
- [ ] Responsividade testada

## 🚨 Proibições Estritas

1. **NUNCA** usar largura 100% sem container
2. **NUNCA** usar cores fixas (amarelo, vermelho, etc.)
3. **NUNCA** usar `alert()` ou `confirm()`
4. **NUNCA** ignorar tema claro/escuro
5. **NUNCA** omitir atributos ARIA
6. **NUNCA** esquecer validação de sessão (se necessário)
7. **NUNCA** usar SQL sem prepared statements
8. **NUNCA** esquecer sanitização de inputs

## 📚 Referências Obrigatórias
- master_rules.md - Regras gerais do projeto
- regra_php_sistema_confirmacao_centralizado.md - Sistema de confirmação
- regra_php_web_standards_aria_wcag.md - Acessibilidade
- regra_php_ajax_interacoes.md - Interações AJAX
- regra_php_csrf_tokens.md - Proteção CSRF

## 🎯 Exemplo de Uso

### Para Criar Nova Página:
1. Ler este prompt completamente
2. Copiar estrutura HTML obrigatória
3. Implementar container centralizado
4. Seguir checklist de validação
5. Testar responsividade
6. Validar acessibilidade
7. Integrar com sistema existente

### Resultado Esperado:
Página profissional, responsiva, acessível e consistente com todo o CanalQB.

## 📂 Tipos de Páginas Aplicáveis

### Páginas Administrativas
- `admin-*-content.php`
- Painéis de controle
- Relatórios e estatísticas
- Configurações do sistema

### Páginas de Usuário
- `meu-perfil.php`
- `extrato.php`
- `comentar-videos.php`
- Dashboard pessoal

### Páginas Públicas
- `index.php`
- Páginas de conteúdo
- Landings e promoções
- Páginas informativas

### Páginas de Sistema
- `{landing-login}.php` (Ex: `index.php`, `inicio-visitante.php`, `login.php`)
- `{registro}.php`
- `{recuperar-senha}.php`
- Páginas de erro (404, 500)

## 🎯 Benefícios Universais

### Para Usuários
- **Experiência Consistente**: Mesmo layout em todas as páginas
- **Legibilidade**: Texto com largura ideal para leitura
- **Responsividade**: Funciona perfeitamente em qualquer dispositivo
- **Acessibilidade**: Totalmente acessível

### Para Desenvolvedores
- **Padronização**: Código consistente e previsível
- **Manutenibilidade**: Fácil manter e atualizar
- **Escalabilidade**: Simples adicionar novas páginas
- **Qualidade**: Garantia de padrões elevados

### Para o Sistema
- **Identidade Visual**: Forte e consistente
- **Performance**: Otimizado para diferentes telas
- **SEO**: Estrutura semântica favorável
- **Futuro-Proof**: Preparado para evoluções

## 🚨 OBRIGATORIEDADE ABSOLUTA

**TODAS as páginas filhas criadas no CanalQB DEVERÃO OBRIGATORIAMENTE seguir este padrão de container centralizado.**

Não há exceções. Seja página administrativa, de usuário, pública ou de sistema - todas devem usar o mesmo padrão de layout.

**Esta regra é mais importante que qualquer outra conveniência ou atalho de desenvolvimento.**
