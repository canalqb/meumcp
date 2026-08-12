# Template: Web Standards & Accessibility Checklist

Use este checklist para validar toda geração de código frontend e garantir conformidade com `regras/web_standards_rules.md`.

## 📋 Checklist de Validação Final

### 1. ARIA & Acessibilidade
- [ ] Todo elemento interativo (button, a, input, select) tem um nome acessível (`aria-label` ou label associada)?
- [ ] Modais têm `role="dialog"` e `aria-modal="true"`?
- [ ] Ícones sozinhos dentro de botões têm `aria-label` descritivo?
- [ ] Estados de expansão estão marcados com `aria-expanded`?

### 2. Contraste e Visual
- [ ] O contraste de cores atende ao nível AA da WCAG (4.5:1 para texto normal)?
- [ ] O foco visual (`:focus` ou `:focus-visible`) está implementado e visível?
- [ ] O design suporta Temas Claro e Escuro consistentemente?

### 3. HTML Semântico (W3C)
- [ ] A página usa `<header>`, `<nav>`, `<main>`, `<section>`, `<article>`, `<footer>`?
- [ ] A hierarquia de títulos (H1-H6) é lógica e sequencial (sem pular níveis)?
- [ ] O layout não é feito exclusivamente com `<div>`?

### 4. Schema.org (SEO Estruturado)
- [ ] A página (se for pública) contém um bloco `<script type="application/ld+json">`?
- [ ] O Schema tipo `WebSite` está presente na home?
- [ ] Itens específicos (Airdrop, Usuário, Artigo) têm seus Schemas correspondentes (`Service`, `Person`, `Article`)?

## 🏗️ Estrutura Base HTML Acessível

```html
<!-- Exemplo de Estrutura Semântica + ARIA + Schema -->
<article class="airdrop-main-card" aria-labelledby="title-{{id}}">
  <header>
    <img src="{{logo}}" alt="Logo {{nome}}">
    <h2 id="title-{{id}}">{{nome}}</h2>
  </header>
  
  <main>
    <p>{{descricao}}</p>
  </main>
  
  <footer>
    <button class="btn-premium" aria-label="Ver detalhes de {{nome}}" onclick="verDetalhes({{id}})">
      Ver Detalhes
    </button>
  </footer>

  <!-- Schema.org -->
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "Service",
    "name": "{{nome}}",
    "description": "{{descricao}}"
  }
  </script>
</article>
```

---
**REGRA DE OURO:** Se houver falha em qualquer item deste checklist, a solução deve ser refatorada antes da entrega.
