# Diretrizes de Contraste e Acessibilidade para LLMs - Elementos HTML

Este documento fornece prompts detalhados para Large Language Models (LLMs) com o objetivo de corrigir e otimizar o contraste de cores em todos os elementos HTML, garantindo a conformidade com os padrões de acessibilidade (WCAG AA/AAA) nos modos dia (light) e noite (dark). O foco é eliminar problemas de visualização causados por baixo contraste entre fundos, bordas, linhas e fontes.

Utilize os placeholders `{{valor}}` para inserir suas informações específicas.

## 1. Princípios de Contraste e Acessibilidade

**Prompt para LLM:**

```
Explique a importância do contraste de cores para a acessibilidade digital, referenciando as diretrizes WCAG (Web Content Accessibility Guidelines) nos níveis AA e AAA. Descreva como o contraste inadequado em elementos como bordas, linhas e fontes pode prejudicar a usabilidade, especialmente em modos claro e escuro. Forneça exemplos de proporções mínimas de contraste para texto normal, texto grande e elementos gráficos.
```

## 2. Refinamento da Paleta de Cores com Foco em Contraste

### 2.1. Paleta Base e Cores Gerais (Revisada para Contraste)

**Prompt para LLM:**

```
Com base na cor principal do meu logo, que é o verde `srgb(77.3693%,89.7978%,73.8415%)` (aproximadamente #C5E5BC), e considerando a paleta de cores atual fornecida abaixo, gere uma nova paleta de cores geral. O foco principal deve ser garantir que todas as cores geradas permitam um contraste WCAG AA/AAA adequado quando usadas em combinação para texto, bordas e fundos. Inclua cores para `--primary`, `--secondary`, `--success`, `--info`, `--warning`, `--danger`, e 5 cores adicionais para uso geral, seguindo uma progressão harmoniosa. Forneça o nome da cor e o código hexadecimal, juntamente com sugestões de combinações de alto contraste.

**Paleta atual para referência:**
--primary: Azul Primário (#007bff)
--secondary: Cinza Secundário (#6c757d)
--success: Verde Sucesso (#28a745)
--info: Azul Info (#17a2b8)
--warning: Amarelo Alerta (#ffc107)
--danger: Vermelho Perigo (#ff005b)
--color-1: Salmão Claro (#FFCDB2)
--color-2: Salmão Médio (#FFB4A2)
--color-3: Rosa Antigo (#E5989B)
--color-4: Rosa Escuro (#B5838D)
--color-5: Roxo Acinzentado (#6D6875)
```

### 2.2. Paleta de Cores para Modo Dia (Light Mode) - Otimizada para Contraste

**Prompt para LLM:**

```
Considerando a nova paleta geral gerada e as diretrizes WCAG AA/AAA, refine a paleta para o modo DIA. Otimize especificamente para garantir alto contraste entre:
- `--body-text-color` e `--background`
- `--header-bg-color` e o texto/ícones do cabeçalho
- `--border-color` e os elementos adjacentes
- `--main-color` e o texto/elementos que a utilizam

Evite combinações de cores claras sobre fundos claros que causem problemas de visualização. Inclua variáveis CSS para:
- Cor Principal (`--main-color`)
- Fundo do Cabeçalho (`--header-bg-color`)
- Texto do Corpo (`--body-text-color`)
- Links do Corpo (`--body-link-color`)
- Título do Site (`--site-title-color`)
- Menu Principal (`--main-menu-color`)
- Bordas (`--border-color`)
- Fundo de Comentários (`--comments-bg-color`)
- Ícone do Sol (`--sun-color`)
- Ícone da Lua (`--moon-color`)

**Paleta atual do modo dia para referência:**
Cor Principal: #B5838D
Fundo do Cabeçalho: #ffffff
Texto do Corpo: #000000
Links do Corpo: #000000
Título do Site: #333333
Menu Principal: #000000
Bordas: #eeeeee
Fundo de Comentários: #f6f6f6
Ícone do Sol: #eeff00
Ícone da Lua: #999999
```

### 2.3. Paleta de Cores para Modo Noite (Dark Mode) - Otimizada para Contraste

**Prompt para LLM:**

```
Considerando a nova paleta geral gerada e as diretrizes WCAG AA/AAA, refine a paleta para o modo NOITE. Otimize especificamente para garantir alto contraste entre:
- `--body-text-color` e `--background`
- `--header-bg-color` e o texto/ícones do cabeçalho
- `--border-color` e os elementos adjacentes
- `--main-color` e o texto/elementos que a utilizam

Evite combinações de cores escuras sobre fundos escuros que causem problemas de visualização. Inclua variáveis CSS para:
- Cor Principal (`--main-color`)
- Fundo do Cabeçalho (`--header-bg-color`)
- Texto do Corpo (`--body-text-color`)
- Links do Corpo (`--body-link-color`)
- Título do Site (`--site-title-color`)
- Menu Principal (`--main-menu-color`)
- Bordas (`--border-color`)
- Fundo de Comentários (`--comments-bg-color`)
- Ícone do Sol (`--sun-color`)
- Ícone da Lua (`--moon-color`)

**Paleta atual do modo noite para referência:**
Cor Principal: #E5989B
Fundo do Cabeçalho: #000000
Texto do Corpo: #ffffff
Links do Corpo: #ffffff
Título do Site: #ffffff
Menu Principal: #ffffff
Bordas: #555555
Fundo de Comentários: #222222
Ícone do Sol: #999999
Ícone da Lua: #FEFCD7
```

### 2.4. Cores de Fonte para Modo Dia e Noite - Otimizadas para Contraste

**Prompt para LLM:**

```
Com base nas paletas de modo dia e noite refinadas e nas diretrizes WCAG AA/AAA, gere as cores de fonte ideais para cada modo. Garanta que todas as cores de fonte tenham um contraste adequado com seus respectivos fundos. Inclua cores para:
- Título principal (`--font-title-primary`)
- Texto normal (`--font-text-normal`)
- Texto secundário (`--font-text-secondary`)
- Texto desabilitado (`--font-text-disabled`)
- Link (`--font-link`)
- Erro (`--font-error`)

**Cores de Fonte - Modo DIA atual para referência:**
Título principal: #212529
Texto normal: #495057
Texto secundário: #6C757D
Texto desabilitado: #ADB5BD
Link: #0D6EFD
Erro: #DC3545

**Cores de Fonte - Modo NOITE atual para referência:**
Título principal: #F1F3F5
Texto normal: #CED4DA
Texto secundário: #ADB5BD
Texto desabilitado: #6C757D
Link: #3B82F6
Erro: #EF4444
```

## 3. Padronização de Tipografia e Contraste

**Prompt para LLM:**

```
Sugira uma família de fonte padrão para a interface (`--font-family-base`) que seja altamente legível e otimizada para contraste em diferentes tamanhos e pesos. Crie uma escala tipográfica fixa para títulos (h1 a h6), parágrafos e outros elementos de texto, definindo `font-size` e `font-weight` ideais. Garanta que a combinação de tamanho, peso e cor da fonte com o fundo atenda aos requisitos WCAG AA/AAA em ambos os modos (dia e noite).

**Família de fonte atual para referência:** 'Inter', sans-serif
```

## 4. Estilização de Componentes UI com Foco em Contraste

Para cada componente abaixo, o LLM deve gerar estilos CSS completos, garantindo que bordas, textos, ícones e fundos tenham contraste adequado em ambos os modos (dia e noite), seguindo as diretrizes WCAG AA/AAA. As cores devem ser baseadas na paleta refinada.

### 4.1. Botões

**Prompt para LLM:**

```
Com base na paleta de cores otimizada para contraste e na padronização de tipografia, gere estilos CSS completos para botões (`<button>`, `<input type="submit">`). Inclua estilos para estados normal, hover, active e disabled. O foco é garantir alto contraste para o texto do botão e suas bordas em relação ao fundo do botão e ao fundo da página, em ambos os modos (dia e noite). Forneça exemplos de classes Bootstrap equivalentes, se aplicável.

**Exemplo Bootstrap para referência:** `class="btn btn-primary"`
```

### 4.2. ComboBox (Select)

**Prompt para LLM:**

```
Com base na paleta de cores otimizada para contraste e na padronização de tipografia, gere estilos CSS completos para o componente ComboBox (`<select>`). Inclua estilos para estados normal, hover, focus e disabled. Garanta alto contraste para o texto, setas indicadoras e bordas do combobox em relação ao seu fundo e ao fundo da página, em ambos os modos (dia e noite). Forneça exemplos de classes Bootstrap equivalentes, se aplicável.

**Exemplo Bootstrap para referência:** `class="form-select"`
```

### 4.3. Tabela

**Prompt para LLM:**

```
Com base na paleta de cores otimizada para contraste e na padronização de tipografia, gere estilos CSS completos para tabelas (`<table>`, `<thead>`, `<tbody>`, `<tr>`, `<th>`, `<td>`). O foco é garantir alto contraste para o texto do cabeçalho e das células, as bordas das células e as linhas de separação, em relação aos fundos das linhas (incluindo linhas alternadas) em ambos os modos (dia e noite). Forneça exemplos de classes Bootstrap equivalentes, se aplicável.

**Exemplos Bootstrap para referência:** `class="table"`, `class="table-striped"`, `class="table-hover"`
```

### 4.4. Outros Inputs Comuns (Text, Email, Password, Number, Date, Checkbox, Radio, File, Textarea)

**Prompt para LLM:**

```
Com base na paleta de cores otimizada para contraste e na padronização de tipografia, gere estilos CSS completos para os seguintes tipos de input: `text`, `email`, `password`, `number`, `date`, `checkbox`, `radio`, `file` e `textarea`. Inclua estilos para estados normal, focus e disabled. Garanta alto contraste para o texto digitado, placeholders, rótulos (labels), bordas dos campos e indicadores de estado (ex: checkbox/radio selecionado) em relação aos seus fundos, em ambos os modos (dia e noite). Forneça exemplos de classes Bootstrap equivalentes, se aplicável.

**Exemplos Bootstrap para referência:** `class="form-control"`, `class="form-check-input"`
```

### 4.5. Label

**Prompt para LLM:**

```
Com base na paleta de cores otimizada para contraste e na padronização de tipografia, gere estilos CSS para o elemento `<label>`. Garanta que a cor do texto do label tenha alto contraste com o fundo adjacente em ambos os modos (dia e noite).
```

### 4.6. Modais (Janelas Pop-up)

**Prompt para LLM:**

```
Com base nas paletas de cores para modal (dia e noite) otimizadas para contraste e na padronização de tipografia, gere a estrutura HTML e os estilos CSS completos para um componente Modal (`<div class="modal">`). Inclua estilos para o overlay, background do modal, header, body e footer, bem como para os botões internos. O foco é garantir alto contraste para todos os textos, ícones e bordas dentro do modal em relação aos seus respectivos fundos, em ambos os modos (dia e noite), seguindo WCAG AA/AAA.

**Estrutura básica para referência:**
```html
<div class="modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <!-- Título e botão de fechar -->
      </div>
      <div class="modal-body">
        <!-- Conteúdo do modal -->
      </div>
      <div class="modal-footer">
        <!-- Botões de ação -->
      </div>
    </div>
  </div>
</div>
```
```

### 4.7. Cards

**Prompt para LLM:**

```
Com base na paleta de cores otimizada para contraste e na padronização de tipografia, gere estilos CSS completos para o componente Card (`<div class="card">`). Inclua estilos para o background, bordas, sombras e espaçamento interno. Garanta alto contraste para o texto do card, títulos e quaisquer outros elementos visuais em relação ao fundo do card e às suas bordas, em ambos os modos (dia e noite).
```

### 4.8. Alerts

**Prompt para LLM:**

```
Com base na paleta de cores otimizada para contraste (especialmente as cores de sucesso, info, warning e danger) e na padronização de tipografia, gere estilos CSS completos para o componente Alert (`<div class="alert">`). Inclua estilos para diferentes tipos de alerta (sucesso, informação, aviso, perigo), com cores de fundo e texto apropriadas. Garanta alto contraste para o texto do alerta e ícones em relação ao fundo do alerta, em ambos os modos (dia e noite).
```

### 4.9. Navbar

**Prompt para LLM:**

```
Com base na paleta de cores otimizada para contraste e na padronização de tipografia, gere estilos CSS completos para o componente Navbar (`<nav>`). Inclua estilos para o background, links de navegação (`<a>`), e um botão de toggle para dispositivos móveis (`<button>`). Garanta alto contraste para o texto dos links, ícones e o botão de toggle em relação ao fundo da navbar, em ambos os modos (dia e noite).
```

### 4.10. Dropdown

**Prompt para LLM:**

```
Com base na paleta de cores otimizada para contraste e na padronização de tipografia, gere estilos CSS completos para o componente Dropdown (`<div>` com `<button>` e `<ul><li><a>`). Inclua estilos para o botão de toggle, o menu dropdown e os itens do menu, incluindo estados hover e active. Garanta alto contraste para o texto, ícones e bordas em todos os estados e elementos do dropdown, em ambos os modos (dia e noite).
```

### 4.11. Collapse

**Prompt para LLM:**

```
Com base na paleta de cores otimizada para contraste e na padronização de tipografia, gere estilos CSS completos para o componente Collapse (`<div>` com `<button>`). Inclua estilos para o botão de toggle e o conteúdo colapsável. Garanta alto contraste para o texto e ícones em relação aos seus fundos, em ambos os modos (dia e noite).
```

### 4.12. Links

**Prompt para LLM:**

```
Com base na paleta de cores otimizada para contraste e na padronização de tipografia, gere estilos CSS completos para links (`<a>`). Inclua estilos para estados normal, hover, active e visited. Garanta alto contraste para o texto do link em relação ao fundo adjacente em todos os estados, em ambos os modos (dia e noite). Forneça exemplos de classes Bootstrap equivalentes, se aplicável.

**Exemplos Bootstrap para referência:** `class="btn"`, `class="nav-link"`
```

### 4.13. Listas (ul, li)

**Prompt para LLM:**

```
Com base na paleta de cores otimizada para contraste e na padronização de tipografia, gere estilos CSS completos para listas não ordenadas (`<ul>`) e itens de lista (`<li>`). Inclua estilos para marcadores, espaçamento e, se aplicável, para uso em componentes como Navbar, Dropdown e List Group. Garanta alto contraste para o texto e marcadores em relação ao fundo da lista, em ambos os modos (dia e noite).
```

## 5. Estrutura e Layout (Base do Bootstrap) com Contraste

**Prompt para LLM:**

```
Considerando a necessidade de um layout limpo, rápido e otimizado para consumo de memória, e utilizando a paleta de cores e tipografia otimizadas para contraste, gere exemplos de estrutura HTML/CSS para os seguintes componentes de layout, focando em um design moderno, de alto contraste e acessível:
- `<div>` (com classes como `container`, `row`, `col`, `card`, `modal`, `alert`)
- `<section>`
- `<header>`
- `<footer>`
- `<main>`
- `<nav>`

Para cada componente, forneça um snippet de código HTML e as classes CSS recomendadas, alinhadas com as novas paletas de cores e tipografia, garantindo que todos os elementos visuais (textos, bordas, fundos) atendam aos requisitos de contraste WCAG AA/AAA.
```

## 6. Recomendações Profissionais e Otimização para Acessibilidade

**Prompt para LLM:**

```
Com base nas melhores práticas de design UI/UX, otimização de performance e, crucialmente, acessibilidade (WCAG AA/AAA), gere um resumo de recomendações profissionais para garantir um layout limpo, rápido, com baixo consumo de memória e totalmente acessível. Inclua pontos sobre:
- A importância de testar o contraste de cores com ferramentas específicas.
- Contraste alto obrigatório no dark mode e light mode para todos os elementos visuais.
- Evitar preto puro (#000000) e branco puro (#FFFFFF no dark mode) para reduzir fadiga visual e melhorar contraste.
- Sombras suaves no light mode e bordas discretas no dark mode, com contraste adequado.
- Diminuir saturação das cores no modo noite para melhor legibilidade.
- Uso de variáveis CSS para alternância de tema, facilitando a manutenção do contraste.
- Importância de uma família de fonte padrão e escala tipográfica fixa que suporte acessibilidade.
- Garantia de contraste AA ou AAA para todos os textos e elementos gráficos.
- Otimização de imagens e outros ativos para performance e acessibilidade (ex: texto alternativo).
- Minificação de CSS/JS.
- Uso de fontes otimizadas (ex: WOFF2).
- Considerações sobre estados de foco (focus states) para navegação por teclado.

Forneça também um exemplo de estrutura CSS com variáveis para alternância de tema, com comentários sobre como garantir o contraste:

```css
:root {
  --font-family-base: '{{nome_fonte_base}}', sans-serif;
  /* Light Mode Variables - Garantir alto contraste com o fundo */
  --text-primary: {{cor_texto_primario_light}}; /* Deve ter contraste > 4.5:1 com --background */
  --text-secondary: {{cor_texto_secundario_light}}; /* Deve ter contraste > 4.5:1 com --background */
  --background: {{cor_fundo_light}};
  --border-color: {{cor_borda_light}}; /* Deve ter contraste > 3:1 com o fundo adjacente */
  /* Adicione aqui outras variáveis do modo dia com notas de contraste */
}

[data-theme="dark"] {
  /* Dark Mode Variables - Garantir alto contraste com o fundo */
  --text-primary: {{cor_texto_primario_dark}}; /* Deve ter contraste > 4.5:1 com --background */
  --text-secondary: {{cor_texto_secundario_dark}}; /* Deve ter contraste > 4.5:1 com --background */
  --background: {{cor_fundo_dark}};
  --border-color: {{cor_borda_dark}}; /* Deve ter contraste > 3:1 com o fundo adjacente */
  /* Adicione aqui outras variáveis do modo noite com notas de contraste */
}
```
```

---

**Observação:** Lembre-se de substituir `{{valor}}` pelos seus dados específicos antes de usar os prompts com o LLM. O verde do logo foi identificado como `srgb(77.3693%,89.7978%,73.8415%)` para ser usado como base na geração da paleta. É crucial que o LLM seja instruído a validar as proporções de contraste para cada combinação de cores gerada.
