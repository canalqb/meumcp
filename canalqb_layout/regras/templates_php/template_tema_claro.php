<?php
/**
 * Template: Componente de Tema Claro
 * Descrição: Protótipo de componente respeitando tokens de cor para o tema claro.
 */
?>

<style>
    .component-light {
        background-color: var(--cqb-bg-surface);
        border: 1px solid var(--cqb-border);
        border-radius: var(--cqb-radius);
        padding: 2rem;
        box-shadow: var(--cqb-shadow-sm);
    }
    .component-light h2 {
        color: var(--cqb-text-primary);
        font-size: 1.5rem;
        margin-bottom: 1rem;
    }
    .component-light p {
        color: var(--cqb-text-secondary);
        line-height: 1.6;
    }
    .component-light .accent-btn {
        background-color: var(--cqb-primary);
        color: var(--cqb-bg-white);
        padding: 10px 20px;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: var(--cqb-transition);
    }
    .component-light .accent-btn:hover {
        background-color: var(--cqb-primary-dark);
        box-shadow: 0 4px 12px rgba(var(--cqb-primary-rgb), 0.2);
    }
</style>

<div class="component-light">
    <h2>Título do Componente (Claro)</h2>
    <p>Este é um exemplo de texto secundário com contraste AA (4.5:1) garantido pelos tokens semânticos.</p>
    <button class="accent-btn">Ação Principal</button>
</div>
