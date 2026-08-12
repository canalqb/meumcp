# Regra de Design: Sistema de Temas e Acessibilidade (WCAG 2.1 / MD3)

Esta regra estabelece os padrões técnicos para a implementação de temas (claro/escuro) e acessibilidade visual no projeto, seguindo as diretrizes de WCAG, Material Design 3 e ARIA.

## 📏 Padrões de Contraste (WCAG 2.1)

Todos os elementos de texto e componentes de interface devem respeitar as proporções de contraste de luminância relativa:

| Nível | Texto Normal | Texto Grande (18px+ ou 14px bold) |
| :--- | :--- | :--- |
| **AA (Mínimo)** | 4.5:1 | 3:1 |
| **AAA (Ideal)** | 7:1 | 4.5:1 |

> [!IMPORTANT]
> Nunca assuma que "claro sobre escuro" é suficiente. Use ferramentas de checagem (WebAIM, Chrome DevTools) para validar o contraste real.

## 🌗 Sistema de Camadas (Dark Mode)

Em ambientes escuros, a profundidade é comunicada pela luminosidade da superfície, e não apenas por sombras.

| Camada | Token CSS | Cor Base Sugerida | Uso |
| :--- | :--- | :--- | :--- |
| **Base** | `--cqb-layer-base` | `#121212` | Fundo principal da página |
| **L1** | `--cqb-layer-1` | `#1e1e1e` | Cards, painéis laterais |
| **L2** | `--cqb-layer-2` | `#222222` | Dropdowns, menus flutuantes |
| **L3** | `--cqb-layer-3` | `#252525` | Modais, caixas de diálogo |
| **L4** | `--cqb-layer-4` | `#272727` | Tooltips, popovers |

**Regra de Ouro:** Cada camada superior deve ser ~5-8% mais clara que a camada inferior.

## 🎨 Sistema de Tokens Semânticos

Evite cores hardcoded (ex: `#fff`). Use tokens que descrevam a **função** da cor:

```css
:root {
  /* Fundo */
  --cqb-bg-base: #ffffff;
  --cqb-bg-surface: #f8f9fa;
  
  /* Texto */
  --cqb-text-primary: #121212;
  --cqb-text-secondary: #555555;
  --cqb-text-inverse: #ffffff;
}

[data-theme="dark"] {
  --cqb-bg-base: #121212;
  --cqb-bg-surface: #1e1e1e;
  
  --cqb-text-primary: #e8eaed; /* Nunca branco puro em fundos escuros */
  --cqb-text-secondary: #9aa0a6;
  --cqb-text-inverse: #121212;
}
```

## ♿ Acessibilidade (ARIA & Media Queries)

1.  **Preferências do Sistema**: Respeite `prefers-color-scheme` via Media Queries.
2.  **Toggle de Tema**: O botão de troca deve usar `aria-pressed` e `aria-label` dinâmicos.
3.  **Redução de Movimento**: Respeite `prefers-reduced-motion` para desativar animações pesadas.

## ⚠️ Proibições

-   **Preto Puro (`#000000`)**: Proibido como fundo principal em dark mode (causa fadiga visual e blooming). Use `#121212`.
-   **Branco Puro (`#ffffff`)**: Proibido como texto principal em dark mode. Use `#e8eaed` ou similar.
-   **Cores de Acento Idênticas**: Cores de destaque (azul, verde) no dark mode devem ser versões desaturadas e levemente mais claras que no light mode para manter o conforto visual.
