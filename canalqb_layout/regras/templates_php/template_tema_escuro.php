<?php
/**
 * Template: Componente de Tema Escuro (MD3)
 * Descrição: Protótipo de componente respeitando tokens de cor, camadas de elevação e contraste WCAG para o tema escuro.
 */
?>

<style>
    .component-dark-base {
        background-color: var(--cqb-layer-base); /* Fundo #121212 */
        padding: 3rem;
        border-radius: var(--cqb-radius);
    }
    .component-dark-card {
        background-color: var(--cqb-layer-1); /* Elevação L1 #1e1e1e */
        border: 1px solid var(--cqb-border);
        border-radius: var(--cqb-radius);
        padding: 2rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.5);
    }
    .component-dark-card h2 {
        color: var(--cqb-text-primary); /* Texto #e8eaed */
        font-size: 1.5rem;
        margin-bottom: 1rem;
    }
    .component-dark-card p {
        color: var(--cqb-text-secondary); /* Texto #9aa0a6 */
        line-height: 1.6;
    }
    .component-dark-card .accent-btn {
        background-color: var(--cqb-primary); /* Verde desaturado */
        color: var(--cqb-bg-base); /* Texto escuro sobre botão claro no dark */
        padding: 10px 20px;
        border: none;
        border-radius: 8px;
        font-weight: 700;
        cursor: pointer;
        transition: var(--cqb-transition);
    }
    .component-dark-card .accent-btn:hover {
        opacity: 0.9;
        transform: scale(1.02);
    }
</style>

<div class="component-dark-base">
    <div class="component-dark-card">
        <h2>Card Elevado L1 (Dark)</h2>
        <p>Este card demonstra contraste AA (4.5:1) em dark mode usando o sistema de camadas Material Design 3.</p>
        <button class="accent-btn">Ação Primária</button>
    </div>
</div>
