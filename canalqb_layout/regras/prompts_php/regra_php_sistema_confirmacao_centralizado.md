# 🧩 Prompt Padrão - Sistema de Confirmação Centralizado com Temas

## 📋 Descrição
Este prompt define o padrão obrigatório para implementação de sistemas de confirmação centralizados que respeitam as regras de temas claro/escuro do CanalQB.

## 🎯 Objetivo
Criar sistemas de confirmação (showToast com callbacks) que:
- Sejam centralizados na página (não fixos no canto)
- Respeitem as regras de temas claro/escuro com contraste WCAG AA
- Nunca usem cores fixas como amarelo
- Sejam acessíveis e responsivos
- Sigam as diretrizes do master_rules.md

## 🎨 Regras de Cores Obrigatórias
- **Tema Claro**: Fundo claro com fonte escura
- **Tema Escuro**: Fundo escuro com fonte clara
- **Contraste**: WCAG AA compliance obrigatório
- **Cores Proibidas**: Amarelo (#ffc107) e outras cores fixas
- **Cores Permitidas**: CSS variables do tema (--cqb-*, --cqb-*-rgb)

## 📐 Estrutura Obrigatória do Componente

### HTML Semântico
```html
<div class="cqb-confirmation-modal" role="dialog" aria-labelledby="confirmation-title" aria-modal="true">
    <div class="cqb-confirmation-backdrop" role="presentation"></div>
    <div class="cqb-confirmation-content">
        <div class="cqb-confirmation-header">
            <h3 id="confirmation-title">Título da Confirmação</h3>
        </div>
        <div class="cqb-confirmation-body">
            <p>Mensagem de confirmação detalhada</p>
        </div>
        <div class="cqb-confirmation-footer">
            <button type="button" class="cqb-btn cqb-btn-secondary" data-action="cancel">
                Cancelar
            </button>
            <button type="button" class="cqb-btn cqb-btn-primary" data-action="confirm">
                Confirmar
            </button>
        </div>
    </div>
</div>
```

### CSS com Variáveis de Tema
```css
/* Modal centralizado */
.cqb-confirmation-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Backdrop semitransparente */
.cqb-confirmation-backdrop {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(var(--cqb-bg-dark-rgb), 0.5);
}

/* Conteúdo centralizado */
.cqb-confirmation-content {
    position: relative;
    background: var(--cqb-bg-white);
    color: var(--cqb-text-main);
    border-radius: var(--cqb-border-radius);
    box-shadow: var(--cqb-shadow-lg);
    max-width: 400px;
    width: 90%;
    padding: 0;
    border: 1px solid var(--cqb-border);
}

/* Tema escuro */
[data-theme="dark"] .cqb-confirmation-content {
    background: var(--cqb-bg-dark);
    color: var(--cqb-text-dark);
}

/* Botões com contraste */
.cqb-btn {
    padding: 8px 16px;
    border-radius: var(--cqb-border-radius);
    border: 1px solid;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.2s ease;
}

.cqb-btn-primary {
    background: var(--cqb-primary);
    color: white;
    border-color: var(--cqb-primary);
}

.cqb-btn-secondary {
    background: var(--cqb-bg-soft);
    color: var(--cqb-text-main);
    border-color: var(--cqb-border);
}

[data-theme="dark"] .cqb-btn-secondary {
    background: var(--cqb-bg-dark-secondary);
    color: var(--cqb-text-dark);
}
```

### JavaScript Padrão
```javascript
function showConfirmation(options) {
    const {
        title = 'Confirmar Ação',
        message = 'Tem certeza que deseja continuar?',
        confirmText = 'Confirmar',
        cancelText = 'Cancelar',
        type = 'warning', // warning, danger, info
        onConfirm = () => {},
        onCancel = () => {}
    } = options;
    
    // Criar modal centralizado
    const modal = document.createElement('div');
    modal.className = 'cqb-confirmation-modal';
    modal.setAttribute('role', 'dialog');
    modal.setAttribute('aria-labelledby', 'confirmation-title');
    modal.setAttribute('aria-modal', 'true');
    
    // Conteúdo com tema apropriado
    modal.innerHTML = `
        <div class="cqb-confirmation-backdrop"></div>
        <div class="cqb-confirmation-content">
            <div class="cqb-confirmation-header">
                <h3 id="confirmation-title">${title}</h3>
            </div>
            <div class="cqb-confirmation-body">
                <p>${message}</p>
            </div>
            <div class="cqb-confirmation-footer">
                <button type="button" class="cqb-btn cqb-btn-secondary" data-action="cancel">
                    ${cancelText}
                </button>
                <button type="button" class="cqb-btn cqb-btn-primary" data-action="confirm">
                    ${confirmText}
                </button>
            </div>
        </div>
    `;
    
    // Event handlers
    modal.querySelector('[data-action="confirm"]').addEventListener('click', () => {
        document.body.removeChild(modal);
        onConfirm();
    });
    
    modal.querySelector('[data-action="cancel"]').addEventListener('click', () => {
        document.body.removeChild(modal);
        onCancel();
    });
    
    modal.querySelector('.cqb-confirmation-backdrop').addEventListener('click', () => {
        document.body.removeChild(modal);
        onCancel();
    });
    
    // Tecla ESC
    const handleEscape = (e) => {
        if (e.key === 'Escape') {
            document.body.removeChild(modal);
            onCancel();
            document.removeEventListener('keydown', handleEscape);
        }
    };
    document.addEventListener('keydown', handleEscape);
    
    // Adicionar ao DOM
    document.body.appendChild(modal);
    
    // Foco no primeiro botão
    modal.querySelector('.cqb-btn-primary').focus();
}
```

## 🎯 Exemplos de Uso Obrigatórios

### Confirmação de Remoção
```javascript
showConfirmation({
    title: 'Remover Canal Privilegiado',
    message: 'Tem certeza que deseja REMOVER este canal privilegiado? Esta ação não pode ser desfeita e o canal será permanentemente excluído do sistema.',
    type: 'danger',
    onConfirm: () => {
        // Lógica de remoção
        removerCanal(id);
    }
});
```

### Confirmação de Desativação
```javascript
showConfirmation({
    title: 'Desativar Canal',
    message: 'Tem certeza que deseja desativar este canal? Ele permanecerá no sistema mas não aparecerá para os usuários até ser reativado.',
    type: 'warning',
    onConfirm: () => {
        // Lógica de desativação
        desativarCanal(id);
    }
});
```

### Confirmação de Reativação
```javascript
showConfirmation({
    title: 'Reativar Canal',
    message: 'Tem certeza que deseja reativar este canal privilegiado? Ele voltará a aparecer para todos os usuários do sistema.',
    type: 'info',
    onConfirm: () => {
        // Lógica de reativação
        reativarCanal(id);
    }
});
```

## 🚨 Proibições Estritas

1. **NUNCA usar cores fixas** como amarelo (#ffc107), vermelho (#dc3545), etc.
2. **NUNCA usar posicionamento fixo** no canto da tela
3. **NUNCA ignorar temas claro/escuro**
4. **NUNCA usar alert() ou confirm() nativos**
5. **NUNCA omitir atributos ARIA** de acessibilidade
6. **NUNCA esquecer contraste WCAG AA**

## 📋 Checklist de Implementação

- [ ] Modal centralizado na tela
- [ ] Respeita tema claro/escuro
- [ ] Usa CSS variables (--cqb-*)
- [ ] Contraste WCAG AA compliance
- [ ] Semântica HTML5 correta
- [ ] Atributos ARIA presentes
- [ ] Suporte a teclado (ESC, Tab, Enter)
- [ ] Responsivo em mobile
- [ ] Foco automático no primeiro botão
- [ ] Feedback visual claro
- [ ] Callbacks funcionais

## 🎨 Cores Permitidas por Tipo

### Tipo 'warning'
- **Tema Claro**: Fundo `var(--cqb-warning-light)` + texto `var(--cqb-warning-dark)`
- **Tema Escuro**: Fundo `var(--cqb-warning-dark)` + texto `var(--cqb-warning-light)`

### Tipo 'danger'  
- **Tema Claro**: Fundo `var(--cqb-danger-light)` + texto `var(--cqb-danger-dark)`
- **Tema Escuro**: Fundo `var(--cqb-danger-dark)` + texto `var(--cqb-danger-light)`

### Tipo 'info'
- **Tema Claro**: Fundo `var(--cqb-info-light)` + texto `var(--cqb-info-dark)`
- **Tema Escuro**: Fundo `var(--cqb-info-dark)` + texto `var(--cqb-info-light)`

## 📚 Referências Obrigatórias
- master_rules.md - Regras gerais do projeto
- regra_php_componentes_ui.md - Componentes universais
- regra_llms_metricas_qualidade_severa.md - Métricas de qualidade
- regra_php_temas_claro_escuro.md - Implementação de temas
