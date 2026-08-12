# Comandos para LLMs - Correção e Otimização de Sistema UI/UX

Este documento contém um conjunto abrangente de prompts e comandos estruturados para serem utilizados com Large Language Models (LLMs). O objetivo é guiar o LLM na correção, otimização e refinamento da interface de usuário (UI) e experiência do usuário (UX) de um sistema, com foco em paletas de cores, tipografia, estilos de componentes e estrutura de layout. Utilize os placeholders `{{valor}}` para inserir suas informações específicas.

## 1. Análise e Geração da Paleta de Cores

### 1.1. Paleta Base e Cores Gerais

**Prompt para LLM:**

```
Com base na cor principal do meu logo, que é o verde `srgb(77.3693%,89.7978%,73.8415%)` (aproximadamente #C5E5BC), e considerando a paleta de cores atual fornecida abaixo, gere uma nova paleta de cores geral que seja moderna, otimizada para baixo consumo de memória, com alto contraste e que harmonize com o verde do logo. Inclua cores para `--primary`, `--secondary`, `--success`, `--info`, `--warning`, `--danger`, e 5 cores adicionais para uso geral, seguindo uma progressão harmoniosa. Forneça o nome da cor e o código hexadecimal.

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

### 1.2. Paleta de Cores para Modo Dia (Light Mode)

**Prompt para LLM:**

```
Considerando a nova paleta geral gerada (com o verde do logo como base) e as recomendações profissionais para o modo dia (texto escuro sobre fundo claro, sombras suaves, sem preto puro), refine a paleta para o modo DIA. Inclua variáveis CSS para:
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

Baseie-se na seguinte estrutura e nos princípios de contraste AA/AAA. Garanta que a paleta seja otimizada para um layout limpo e rápido.

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

### 1.3. Paleta de Cores para Modo Noite (Dark Mode)

**Prompt para LLM:**

```
Considerando a nova paleta geral gerada (com o verde do logo como base) e as recomendações profissionais para o modo noite (texto claro sobre fundo escuro, contraste alto, bordas discretas, diminuição da saturação, sem branco puro), refine a paleta para o modo NOITE. Inclua variáveis CSS para:
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

Baseie-se na seguinte estrutura e nos princípios de contraste AA/AAA. Garanta que a paleta seja otimizada para um layout limpo e rápido.

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

### 1.4. Paleta de Cores para Modal (Modo Dia e Noite)

**Prompt para LLM:**

```
Com base nas paletas de modo dia e noite refinadas, crie paletas específicas para Modais, tanto para o modo DIA quanto para o modo NOITE. Garanta que as cores sigam as recomendações de contraste e usabilidade, otimizando para um layout limpo e rápido. Inclua as seguintes variáveis:
- Fundo overlay (`--modal-overlay-bg`)
- Fundo principal do Modal (`--modal-bg`)
- Fundo do Header do Modal (`--modal-header-bg`)
- Fundo do Body do Modal (`--modal-body-bg`)
- Fundo do Footer do Modal (`--modal-footer-bg`)
- Cor do Título do Modal (`--modal-title-color`)
- Cor do Texto normal do Modal (`--modal-text-color`)
- Cor da Borda do Modal (`--modal-border-color`)
- Cores para Botões (Primário, Secundário, Sucesso, Perigo) dentro do Modal, seguindo a nova paleta geral.

**Paleta Modal - Modo DIA (Light) atual para referência:**
Fundo overlay: rgba(0,0,0,0.5)
Modal background: #FFFFFF
Header background: #F8F9FA
Body background: #FFFFFF
Footer background: #F1F3F5
Título: #212529
Texto normal: #495057
Borda: #DEE2E6
Botão Primário: #0D6EFD
Botão Secundário: #6C757D
Botão Sucesso: #198754
Botão Perigo: #DC3545

**Paleta Modal - Modo NOITE (Dark) atual para referência:**
Fundo overlay: rgba(0,0,0,0.75)
Modal background: #1E1E2F
Header background: #25273C
Body background: #1E1E2F
Footer background: #191A2B
Título: #F1F3F5
Texto normal: #CED4DA
Borda: #343A40
Botão Primário: #3B82F6
Botão Secundário: #6C757D
Botão Sucesso: #22C55E
Botão Perigo: #EF4444
```

### 1.5. Cores de Fonte para Modo Dia e Noite

**Prompt para LLM:**

```
Com base nas paletas de modo dia e noite refinadas, gere as cores de fonte ideais para cada modo, garantindo legibilidade, alto contraste e otimização para baixo consumo de memória. Inclua cores para:
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

## 2. Padronização de Tipografia

**Prompt para LLM:**

```
Sugira uma família de fonte padrão para a interface (`--font-family-base`), considerando modernidade, legibilidade e otimização para baixo consumo de memória. Além disso, crie uma escala tipográfica fixa para títulos (h1 a h6), parágrafos e outros elementos de texto, definindo tamanhos de fonte (`font-size`) e pesos (`font-weight`) ideais para cada um. Considere que a família da fonte e os tamanhos não mudam entre os modos dia e noite, mas o peso pode raramente mudar. Garanta que a tipografia contribua para um layout limpo e rápido.

**Família de fonte atual para referência:** 'Inter', sans-serif
```

## 3. Estilização de Componentes UI

### 3.1. Botões

**Prompt para LLM:**

```
Com base na nova paleta de cores e na padronização de tipografia, gere estilos CSS completos para botões (`<button>`, `<input type="submit">`). Inclua estilos para estados normal, hover, active e disabled. Considere os tipos de botão (primário, secundário, sucesso, perigo) e suas respectivas cores da paleta. Forneça exemplos de classes Bootstrap equivalentes, se aplicável, e garanta um design moderno, limpo e com alto contraste.

**Exemplo Bootstrap para referência:** `class="btn btn-primary"`
```

### 3.2. ComboBox (Select)

**Prompt para LLM:**

```
Com base na nova paleta de cores e na padronização de tipografia, gere estilos CSS completos para o componente ComboBox (`<select>`). Inclua estilos para estados normal, hover, focus e disabled. Garanta um layout limpo e moderno, com alto contraste e otimização para baixo consumo de memória. Forneça exemplos de classes Bootstrap equivalentes, se aplicável.

**Exemplo Bootstrap para referência:** `class="form-select"`
```

### 3.3. Tabela

**Prompt para LLM:**

```
Com base na nova paleta de cores e na padronização de tipografia, gere estilos CSS completos para tabelas (`<table>`, `<thead>`, `<tbody>`, `<tr>`, `<th>`, `<td>`). Inclua estilos para linhas alternadas (`striped`), hover nas linhas e bordas discretas. Garanta legibilidade, um layout limpo, moderno e com alto contraste. Forneça exemplos de classes Bootstrap equivalentes, se aplicável.

**Exemplos Bootstrap para referência:** `class="table"`, `class="table-striped"`, `class="table-hover"`
```

### 3.4. Outros Inputs Comuns (Text, Email, Password, Number, Date, Checkbox, Radio, File, Textarea)

**Prompt para LLM:**

```
Com base na nova paleta de cores e na padronização de tipografia, gere estilos CSS completos para os seguintes tipos de input: `text`, `email`, `password`, `number`, `date`, `checkbox`, `radio`, `file` e `textarea`. Inclua estilos para estados normal, focus e disabled. Garanta um layout limpo e moderno, com alto contraste e otimização para baixo consumo de memória. Forneça exemplos de classes Bootstrap equivalentes, se aplicável.

**Exemplos Bootstrap para referência:** `class="form-control"`, `class="form-check-input"`
```

### 3.5. Label

**Prompt para LLM:**

```
Com base na padronização de tipografia e na nova paleta de cores, gere estilos CSS para o elemento `<label>`, garantindo que seja legível, alinhado com o design geral e otimizado para um layout limpo.
```

### 3.6. Modais (Janelas Pop-up)

**Prompt para LLM:**

```
Com base nas paletas de cores para modal (dia e noite) e na padronização de tipografia, gere a estrutura HTML e os estilos CSS completos para um componente Modal (`<div class="modal">`). Inclua estilos para o overlay, background do modal, header, body e footer, bem como para os botões internos (primário, secundário, sucesso, perigo). Garanta um design moderno, limpo, com alto contraste e otimizado para baixo consumo de memória. Forneça a estrutura HTML e os estilos CSS para ambos os modos (dia e noite).

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

### 3.7. Cards

**Prompt para LLM:**

```
Com base na nova paleta de cores e na padronização de tipografia, gere estilos CSS completos para o componente Card (`<div class="card">`). Inclua estilos para o background, bordas, sombras e espaçamento interno. Garanta um design moderno, limpo e com alto contraste, otimizado para baixo consumo de memória.
```

### 3.8. Alerts

**Prompt para LLM:**

```
Com base na nova paleta de cores (especialmente as cores de sucesso, info, warning e danger) e na padronização de tipografia, gere estilos CSS completos para o componente Alert (`<div class="alert">`). Inclua estilos para diferentes tipos de alerta (sucesso, informação, aviso, perigo), com cores de fundo e texto apropriadas. Garanta um design moderno, limpo e com alto contraste.
```

### 3.9. Navbar

**Prompt para LLM:**

```
Com base na nova paleta de cores e na padronização de tipografia, gere estilos CSS completos para o componente Navbar (`<nav>`). Inclua estilos para o background, links de navegação (`<a>`), e um botão de toggle para dispositivos móveis (`<button>`). Garanta um design responsivo, moderno, limpo e com alto contraste.
```

### 3.10. Dropdown

**Prompt para LLM:**

```
Com base na nova paleta de cores e na padronização de tipografia, gere estilos CSS completos para o componente Dropdown (`<div>` com `<button>` e `<ul><li><a>`). Inclua estilos para o botão de toggle, o menu dropdown e os itens do menu, incluindo estados hover e active. Garanta um design moderno, limpo e com alto contraste.
```

### 3.11. Collapse

**Prompt para LLM:**

```
Com base na nova paleta de cores e na padronização de tipografia, gere estilos CSS completos para o componente Collapse (`<div>` com `<button>`). Inclua estilos para o botão de toggle e o conteúdo colapsável. Garanta um design moderno, limpo e com alto contraste.
```

### 3.12. Links

**Prompt para LLM:**

```
Com base na nova paleta de cores e na padronização de tipografia, gere estilos CSS completos para links (`<a>`). Inclua estilos para estados normal, hover, active e visited. Considere o uso de links em diferentes contextos (texto, botões, navegação). Forneça exemplos de classes Bootstrap equivalentes, se aplicável.

**Exemplos Bootstrap para referência:** `class="btn"`, `class="nav-link"`
```

### 3.13. Listas (ul, li)

**Prompt para LLM:**

```
Com base na nova paleta de cores e na padronização de tipografia, gere estilos CSS completos para listas não ordenadas (`<ul>`) e itens de lista (`<li>`). Inclua estilos para marcadores, espaçamento e, se aplicável, para uso em componentes como Navbar, Dropdown e List Group. Garanta um design moderno, limpo e com alto contraste.
```

## 4. Estrutura e Layout (Base do Bootstrap)

**Prompt para LLM:**

```
Considerando a necessidade de um layout limpo, rápido e otimizado para consumo de memória, e utilizando a nova paleta de cores e tipografia, gere exemplos de estrutura HTML/CSS para os seguintes componentes de layout, focando em um design moderno e de alto contraste:
- `<div>` (com classes como `container`, `row`, `col`, `card`, `modal`, `alert`)
- `<section>`
- `<header>`
- `<footer>`
- `<main>`
- `<nav>`

Para cada componente, forneça um snippet de código HTML e as classes CSS recomendadas, alinhadas com as novas paletas de cores e tipografia. Dê ênfase ao uso de `<div>` com classes Bootstrap para grid e componentes.
```

## 5. Elementos HTML Essenciais (Resumão)

**Prompt para LLM:**

```
Liste os 10 elementos HTML mais essenciais para a construção de interfaces, com uma breve descrição de seu uso principal e exemplos de como eles se integram com as classes Bootstrap e a nova paleta de cores/tipografia. Inclua:
- `<div>`
- `<input>`
- `<select>`
- `<textarea>`
- `<button>`
- `<label>`
- `<table>`
- `<tr>`
- `<td>`
- `<a>`
```

## 6. Recomendações Profissionais e Otimização

**Prompt para LLM:**

```
Com base nas melhores práticas de design UI/UX e otimização de performance, gere um resumo de recomendações profissionais para garantir um layout limpo, rápido e com baixo consumo de memória. Inclua pontos sobre:
- Contraste alto no dark mode
- Evitar preto puro (#000000) e branco puro (#FFFFFF no dark mode)
- Sombras suaves no light mode
- Bordas discretas no dark mode
- Diminuir saturação das cores no modo noite
- Uso de variáveis CSS para alternância de tema
- Importância de uma família de fonte padrão e escala tipográfica fixa
- Garantia de contraste AA ou AAA
- Otimização de imagens e outros ativos para performance
- Minificação de CSS/JS
- Uso de fontes otimizadas (ex: WOFF2)

Forneça também um exemplo de estrutura CSS com variáveis para alternância de tema, como:

```css
:root {
  --font-family-base: '{{nome_fonte_base}}', sans-serif;
  /* Light Mode Variables */
  --text-primary: {{cor_texto_primario_light}};
  --text-secondary: {{cor_texto_secundario_light}};
  --background: {{cor_fundo_light}};
  /* Adicione aqui outras variáveis do modo dia */
}

[data-theme="dark"] {
  /* Dark Mode Variables */
  --text-primary: {{cor_texto_primario_dark}};
  --text-secondary: {{cor_texto_secundario_dark}};
  --background: {{cor_fundo_dark}};
  /* Adicione aqui outras variáveis do modo noite */
}
```
```

---

**Observação:** Lembre-se de substituir `{{valor}}` pelos seus dados específicos antes de usar os prompts com o LLM. O verde do logo foi identificado como `srgb(77.3693%,89.7978%,73.8415%)` para ser usado como base na geração da paleta.
