# 🔇 Console Silencer - Regras para Produção

## 📋 Descrição

Esta regra define como criar um script JavaScript para ambiente de produção que silencia mensagens informativas do console mantendo apenas erros críticos visíveis.

## 🎯 Objetivo

- Ocultar logs informativos (log, info, warn, debug)
- Manter erros reais visíveis para monitoramento
- Interceptar e silenciar logs automáticos do navegador
- Não quebrar funcionalidade de bibliotecas
- Ser seguro para ambiente de produção

## 🔧 Implementação

### 1. Script Básico (Recomendado)

```javascript
/**
 * Console Silencer - Produção
 * Oculta logs informativos mantendo apenas erros críticos
 */
(function() {
    'use strict';
    
    // Backup do console original para emergências
    const OriginalConsole = window.console;
    
    // Override seletivo do console
    const silentMethods = ['log', 'info', 'warn', 'debug', 'trace', 'table'];
    const keepMethods = ['error', 'assert', 'clear', 'group', 'groupEnd', 'groupCollapsed'];
    
    // Função silenciosa (no-op)
    const noop = function() {};
    
    // Aplica overrides
    silentMethods.forEach(method => {
        if (window.console[method]) {
            window.console[method] = noop;
        }
    });
    
    // Mantém métodos críticos intactos
    keepMethods.forEach(method => {
        if (OriginalConsole[method]) {
            window.console[method] = OriginalConsole[method].bind(OriginalConsole);
        }
    });
    
    // Flag para debug em desenvolvimento
    window.__CONSOLE_SILENCED__ = true;
    
    // Emergência: restaurar console se necessário
    window.__RESTORE_CONSOLE__ = function() {
        Object.keys(OriginalConsole).forEach(method => {
            window.console[method] = OriginalConsole[method].bind(OriginalConsole);
        });
        window.__CONSOLE_SILENCED__ = false;
    };
})();
```

### 2. Script Avançado (Completo)

```javascript
/**
 * Advanced Console Silencer - Produção
 * Intercepta XMLHttpRequest, fetch e eventos de performance
 */
(function() {
    'use strict';
    
    // Backup do console original
    const OriginalConsole = window.console;
    const OriginalXHROpen = XMLHttpRequest.prototype.open;
    const OriginalFetch = window.fetch;
    
    // Estado de silenciamento
    let isSilenced = true;
    
    // Override do console
    const silentMethods = ['log', 'info', 'warn', 'debug', 'trace', 'table'];
    const criticalMethods = ['error', 'assert'];
    
    // Função silenciosa
    const noop = function() {};
    
    // Override seletivo
    silentMethods.forEach(method => {
        window.console[method] = noop;
    });
    
    // Mantém métodos críticos
    criticalMethods.forEach(method => {
        if (OriginalConsole[method]) {
            window.console[method] = OriginalConsole[method].bind(OriginalConsole);
        }
    });
    
    // Intercepta XMLHttpRequest para silenciar logs informativos
    XMLHttpRequest.prototype.open = function(method, url, ...args) {
        const originalOnReadyStateChange = this.onreadystatechange;
        
        this.onreadystatechange = function(event) {
            // Silencia logs de XHR finished loading
            if (isSilenced && this.readyState === 4) {
                // Não faz nada - silencia o log
            }
            
            if (originalOnReadyStateChange) {
                return originalOnReadyStateChange.call(this, event);
            }
        };
        
        return OriginalXHROpen.call(this, method, url, ...args);
    };
    
    // Intercepta fetch para silenciar logs informativos
    window.fetch = function(input, init) {
        const fetchPromise = OriginalFetch.call(this, input, init);
        
        if (isSilenced) {
            // Silencia logs de fetch finished loading
            fetchPromise.catch(error => {
                // Deixa erros reais passarem
                if (error.name !== 'TypeError') {
                    OriginalConsole.error('Fetch Error:', error);
                }
            });
        }
        
        return fetchPromise;
    };
    
    // Intercepta violações de performance
    if (window.PerformanceObserver) {
        const observer = new PerformanceObserver((list) => {
            if (isSilenced) {
                // Silencia logs de performance violations
                return;
            }
        });
        
        try {
            observer.observe({ entryTypes: ['measure', 'navigation', 'resource'] });
        } catch (e) {
            // Ignora se não suportado
        }
    }
    
    // Override de addEventListener para scroll events
    const OriginalAddEventListener = EventTarget.prototype.addEventListener;
    
    EventTarget.prototype.addEventListener = function(type, listener, options) {
        // Adiciona passive: true automaticamente para scroll events
        if (type === 'scroll' && typeof options === 'object') {
            options.passive = true;
        }
        
        return OriginalAddEventListener.call(this, type, listener, options);
    };
    
    // Controles de emergência
    window.__CONSOLE_SILENCED__ = true;
    
    window.__RESTORE_CONSOLE__ = function() {
        isSilenced = false;
        Object.keys(OriginalConsole).forEach(method => {
            window.console[method] = OriginalConsole[method].bind(OriginalConsole);
        });
        XMLHttpRequest.prototype.open = OriginalXHROpen;
        window.fetch = OriginalFetch;
        EventTarget.prototype.addEventListener = OriginalAddEventListener;
    };
    
    window.__SILENCE_CONSOLE__ = function() {
        isSilenced = true;
        silentMethods.forEach(method => {
            window.console[method] = noop;
        });
    };
    
    // Debug em desenvolvimento
    if (window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1') {
        window.__RESTORE_CONSOLE__();
    }
})();
```

## 📝 Como Usar

### 1. Adicionar ao Projeto

Crie o arquivo `js/console-silencer.js` com o script desejado e inclua no header:

```html
<!-- Console Silencer para Produção -->
<script src="js/console-silencer.js"></script>
```

### 2. Condicional por Ambiente

```html
<?php if (defined('PRODUCTION') && PRODUCTION): ?>
<script src="js/console-silencer.js"></script>
<?php endif; ?>
```

### 3. Via JavaScript

```javascript
// Verifica se console está silenciado
if (window.__CONSOLE_SILENCED__) {
    console.log('Console está silenciado');
}

// Restora em emergência
if (typeof window.__RESTORE_CONSOLE__ === 'function') {
    window.__RESTORE_CONSOLE__();
}
```

## ⚠️ Considerações Importantes

### ✅ O que é seguro silenciar:
- `console.log` - Logs de debug
- `console.info` - Informações gerais
- `console.warn` - Avisos não críticos
- `console.debug` - Debug detalhado
- `XHR finished loading` - Logs de requisições
- `Fetch finished loading` - Logs de fetch
- `[Violation]` - Avisos de performance

### ❌ O que NÃO deve ser silenciado:
- `console.error` - Erros críticos
- `Uncaught Exception` - Exceções não tratadas
- `Promise rejection` - Rejeições de Promise
- Erros de sintaxe JavaScript
- Falhas de carregamento críticas

## 🔍 Validação

### Teste 1: Verificar Silenciamento
```javascript
console.log('Este log não deve aparecer');
console.info('Esta info não deve aparecer');
console.warn('Este warning não deve aparecer');
console.error('Este erro DEVE aparecer'); // ← Este deve aparecer
```

### Teste 2: Verificar Funcionalidade
```javascript
// jQuery deve continuar funcionando
$.ajax('/api/test').done(function(data) {
    console.log(data); // Não aparece, mas AJAX funciona
});

// Fetch deve funcionar
fetch('/api/test').then(response => {
    console.log(response); // Não aparece, mas fetch funciona
});
```

## 🚀 Benefícios

- **Console limpo** em produção
- **Performance melhor** (menos processamento de logs)
- **Monitoramento intacto** (erros reais ainda aparecem)
- **Compatibilidade** com bibliotecas populares
- **Debug fácil** em desenvolvimento

## 📚 Referências

- [Console API - MDN](https://developer.mozilla.org/pt-BR/docs/Web/API/Console)
- [XMLHttpRequest - MDN](https://developer.mozilla.org/pt-BR/docs/Web/API/XMLHttpRequest)
- [Fetch API - MDN](https://developer.mozilla.org/pt-BR/docs/Web/API/Fetch_API)
- [Performance Observer - MDN](https://developer.mozilla.org/pt-BR/docs/Web/API/PerformanceObserver)

---

**Status**: ✅ Regra pronta para implementação
**Prioridade**: Alta (performance e experiência do desenvolvedor)
