# 🌐 Web Standards, Acessibilidade e SEO (Normas Obrigatórias)

Este documento define as diretrizes absolutas para o desenvolvimento frontend do projeto @CanalQb. O não cumprimento de qualquer um destes pontos é considerado um **erro estrutural grave**.

---

## 1. ♿ ARIA & Acessibilidade (Obrigatório)

Todo componente interativo deve ser totalmente acessível para tecnologias assistivas.

### Diretrizes de Implementação:
- **Botões Icônicos**: É proibido o uso de `<button>` apenas com ícone. Deve conter `aria-label`.
- **Modais**: Devem possuir `role="dialog"`, `aria-modal="true"` e um `aria-labelledby` apontando para o título.
- **Navegação**: Menus devem estar contidos em `<nav role="navigation">`.
- **Estados Dinâmicos**: Elementos que expandem/colapsam devem usar `aria-expanded="true/false"`.

**Exemplo Base:**
```html
<button aria-label="Menu principal" aria-expanded="false" onclick="toggleMenu()">
  <i class="fas fa-bars"></i>
</button>
```

---

## 2. 🎨 WCAG AA — Contraste e Navegação

Garantir que a interface seja consumível por todos os usuários.

- **Contraste**: Mínimo de **4.5:1** para texto normal e **3:1** para texto grande.
- **Foco**: O contorno de foco (`outline`) nunca deve ser removido sem uma substituição visual clara e acessível (`:focus-visible`).
- **Teclado**: Todo elemento clicável deve ser alcançável via tecla `TAB` e ativado via `ENTER` ou `SPACE`.

---

## 3. 🏗️ HTML Semântico (W3C)

O layout deve ser estruturado com elementos semânticos. É proibido o uso excessivo de `<div>`.

- **Estrutura**: `<header>`, `<nav>`, `<main>`, `<section>`, `<article>`, `<footer>`.
- **Hierarquia**: Usar apenas um `<h1>` por página e respeitar a ordem lógica `<h2>` → `<h6>`.

---

## 4. 🔗 Schema.org & SEO Estruturado

Todas as páginas públicas devem conter meta-dados em formato JSON-LD para indexação rica (Rich Snippets).

**Exemplo WebSite:**
```json
{
  "@context": "https://schema.org",
  "@type": "WebSite",
  "name": "Nome do Site",
  "url": "https://exemplo.com"
}
```

**Exemplo Item Airdrop (Product/Article):**
```json
{
  "@context": "https://schema.org",
  "@type": "Service",
  "name": "Airdrop XYZ",
  "description": "Participe do airdrop...",
  "provider": { "@type": "Organization", "name": "CanalQb" }
}
```

---

## 5. 📏 Design System & Grid

- **Tipografia**: Utilizar escala modular (ex: Inter, 16px base).
- **Espaçamento**: Múltiplos de **8px** (8, 16, 24, 32...).
- **Cores**: Variáveis CSS para temas claro/escuro.

---

## 🔴 CRITÉRIO DE FALHA AUTOMÁTICA
Qualquer código gerado que ignore atributos ARIA em elementos interativos ou falhe na semântica HTML W3C deve ser rejeitado para produção imediatamente.
