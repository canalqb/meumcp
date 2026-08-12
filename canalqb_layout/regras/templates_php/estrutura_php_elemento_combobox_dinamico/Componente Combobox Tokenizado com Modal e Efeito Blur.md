# Componente Combobox Tokenizado com Modal e Efeito Blur

Este documento descreve um componente de combobox avançado, projetado para oferecer uma experiência de usuário rica e interativa, com foco em **tokenização de itens selecionados**, um **modal com fundo semitransparente e efeito blur**, e **consistência visual** com o tema Bootstrap Morph. É otimizado para integração em aplicações web e serve como um template robusto para geração por Large Language Models (LLMs).

## 1. Introdução

O componente `Combobox Tokenizado` é uma solução moderna para seleção múltipla de itens em um campo de entrada. Ele transforma cada item selecionado em um "token" visualmente distinto, que pode ser facilmente removido. Além disso, a interação com o combobox ativa um modal com um efeito de desfoque no fundo, melhorando o foco do usuário e a estética da interface.

### Principais Características:

*   **Tokenização**: Itens selecionados são convertidos em tokens visuais dentro do campo de entrada.
*   **Modal com Blur**: Um overlay semitransparente com efeito de desfoque (backdrop-filter) é ativado ao interagir com o combobox, focando a atenção do usuário.
*   **Autocomplete Inteligente**: Sugestões de itens são filtradas em tempo real e exibidas em um menu dropdown.
*   **Navegação Acessível**: Suporte completo para navegação via teclado (setas, Enter, Backspace) e mouse.
*   **Consistência Visual**: Estilos CSS projetados para se integrar perfeitamente com temas Bootstrap, como o Bootswatch Morph.
*   **Modularidade**: Lógica JavaScript separada para fácil manutenção e reuso.

## 2. Funcionalidades Detalhadas

### 2.1. Tokenização

Quando um usuário seleciona um item da lista de sugestões, ele é adicionado como um "token" dentro do `combobox_token_wrapper`. Cada token exibe o rótulo do item e um botão para remoção (`×`). A remoção de um token o torna novamente disponível na lista de sugestões.

**Comportamento:**

*   **Adição**: Clique em um item da lista ou pressione `Enter` com um item ativo.
*   **Remoção**: Clique no `×` do token ou pressione `Backspace` quando o campo de entrada estiver vazio.

### 2.2. Modal com Efeito Blur

Ao focar no campo de entrada do combobox ou clicar no `combobox_token_wrapper`, um overlay (`combobox_modal_overlay`) é ativado. Este overlay cobre a tela com um fundo semitransparente e aplica um `backdrop-filter: blur()` ao conteúdo abaixo, criando um efeito visual elegante que destaca o combobox e suas sugestões.

**Estilos Chave:**

```css
.combobox_modal_overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, var(--combobox_overlay_opacity)); /* Semitransparente */
  backdrop-filter: blur(var(--combobox_blur_intensity)); /* Efeito de desfoque */
  -webkit-backdrop-filter: blur(var(--combobox_blur_intensity)); /* Compatibilidade Safari */
  display: none; /* Escondido por padrão */
  z-index: 1040; /* Acima da maioria dos elementos, abaixo de modais principais */
}

.combobox_modal_overlay.combobox_active {
  display: block; /* Ativado via JavaScript */
}
```

### 2.3. Acessibilidade (ARIA)

O componente incorpora atributos ARIA para melhorar a acessibilidade, tornando-o utilizável por tecnologias assistivas:

*   `role="combobox"` no `combobox_token_wrapper`.
*   `aria-expanded` para indicar o estado do dropdown.
*   `role="searchbox"` e `aria-controls` no campo de entrada (`combobox_input`).
*   `role="listbox"` para o menu de sugestões (`combobox_autocomplete_menu`).

## 3. Estrutura do Código

O componente é dividido em três partes principais: HTML (PHP), CSS e JavaScript.

### 3.1. `template_combobox.php` (HTML com Variáveis de Template)

Este arquivo contém a estrutura HTML básica do componente, incluindo o campo de entrada, o contêiner para os tokens e o menu de sugestões. Ele utiliza o formato `{{variavel}}` para permitir a personalização dinâmica de títulos, rótulos e placeholders, ideal para prompts de LLMs.

**Variáveis de Template Disponíveis:**

| Variável                  | Descrição                                  | Exemplo de Uso             |
| :------------------------ | :----------------------------------------- | :------------------------- |
| `{{combobox_title}}`      | Título da página ou seção do combobox.     | `Combobox de Seleção`      |
| `{{combobox_label}}`      | Rótulo exibido acima do campo de entrada.  | `Selecione os itens:`      |
| `{{combobox_placeholder}}`| Texto placeholder do campo de entrada.     | `Buscar ou adicionar...`   |
| `{{combobox_data_source}}`| Fonte de dados para as sugestões (array JSON ou URL de API). | `api/items` ou `[{...}]` |
| `{{combobox_js_path}}`    | Caminho para o arquivo JavaScript do combobox. | `./js/combobox.js`         |

**Exemplo de Uso no Template:**

```html
<title>{{combobox_title}}</title>
<label class="form-label fw-bold" for="combobox_input">
  {{combobox_label}}
</label>
<input 
  type="text" 
  id="combobox_input" 
  placeholder="{{combobox_placeholder}}" 
  autocomplete="off" 
  spellcheck="false"
  role="searchbox"
  aria-controls="combobox_menu"
  aria-label="Campo de busca para seleção de itens"
>
```

### 3.2. CSS (`<style>` no `template_combobox.php`)

Os estilos CSS são incorporados diretamente no arquivo PHP para facilitar a distribuição. Todas as classes e variáveis CSS agora utilizam o prefixo `combobox_` para evitar conflitos e garantir clareza. Eles definem a aparência dos tokens, do menu de sugestões, do overlay do modal e garantem a responsividade. As variáveis CSS (`--combobox_primary_color`, `--combobox_blur_intensity`, etc.) permitem fácil personalização.

### 3.3. JavaScript (`combobox.js`)

A lógica interativa do combobox é encapsulada em um arquivo JavaScript modular (`combobox.js`). Este script gerencia:

*   Adição e remoção de tokens.
*   Filtragem e exibição de sugestões.
*   Interação com o modal overlay (ativar/desativar blur).
*   Navegação por teclado e mouse.
*   Atualização de um campo `hidden` para submissão de formulário.

**Estrutura da Função `initCombobox`:**

```javascript
export function initCombobox(options) {
  const { 
    inputSelector, 
    menuSelector, 
    wrapperSelector, 
    overlaySelector, 
    tokenCountSelector, 
    selectedTokensSelector, 
    placeholder, 
    dataSource 
  } = options;

  // ... lógica de inicialização e manipulação de eventos ...

  // Exemplo de como o overlay é ativado/desativado
  function activateOverlay() {
    if (overlay) {
      overlay.classList.add(\'combobox_active\');
      wrapper.classList.add(\'combobox_modal_active\');
    }
  }

  function deactivateOverlay() {
    if (overlay) {
      overlay.classList.remove(\'combobox_active\');
      wrapper.classList.remove(\'combobox_modal_active\');
    }
  }
}
```

## 4. Uso e Integração

Para utilizar o componente, siga os passos:

1.  **Inclua o `template_combobox.php`**: Incorpore o conteúdo deste arquivo PHP em sua página onde o combobox deve aparecer.
2.  **Crie o arquivo `combobox.js`**: Salve o código JavaScript fornecido (ou gerado por LLM) em `js/combobox.js` (ou ajuste o caminho em `{{combobox_js_path}}`).
3.  **Inicialize o Componente**: Certifique-se de que o script de inicialização no `template_combobox.php` esteja correto, passando os seletores e a fonte de dados (`dataSource`).

**Exemplo de Inicialização (no `template_combobox.php`):**

```html
<script type="module">
  import { initCombobox } from '{{combobox_js_path}}'; // Ajuste o caminho conforme necessário

  document.addEventListener('DOMContentLoaded', () => {
    initCombobox({
      inputSelector: '#combobox_input',
      menuSelector: '#combobox_menu',
      wrapperSelector: '#combobox_wrapper',
      overlaySelector: '#combobox_modal_overlay',
      tokenCountSelector: '#combobox_token_count',
      selectedTokensSelector: '#combobox_selected_tokens',
      placeholder: '{{combobox_placeholder}}',
      dataSource: [
        { label: 'Opção A', value: 'A' },
        { label: 'Opção B', value: 'B' },
        // ... mais itens
      ] // Ou '{{combobox_data_source}}' para carregar de uma API
    });
  });
</script>
```

## 5. Personalização

### 5.1. Variáveis de Template

As variáveis de template (`{{combobox_title}}`, `{{combobox_label}}`, `{{combobox_placeholder}}`, `{{combobox_data_source}}`, `{{combobox_js_path}}`) devem ser substituídas pelos valores desejados antes da renderização final do HTML. Isso pode ser feito por um sistema de template PHP, um LLM, ou manualmente.

### 5.2. Variáveis CSS

As variáveis CSS definidas em `:root` permitem ajustar cores, intensidade do blur, sombras e opacidade do overlay globalmente. Todas elas agora possuem o prefixo `combobox_`:

```css
:root {
  --combobox_primary_color: #0d6efd; /* Cor principal */
  --combobox_blur_intensity: 12px; /* Intensidade do desfoque */
  --combobox_overlay_opacity: 0.6; /* Opacidade do fundo do modal */
}
```

### 5.3. Fonte de Dados (`dataSource`)

O `dataSource` na função `initCombobox` pode ser:

*   Um **array de objetos**: `[{ label: 'Nome', value: 'ID' }]`.
*   Uma **URL de API**: O script fará uma requisição `GET` para buscar os itens. A API deve retornar um JSON no formato `[{ label: 'Nome', value: 'ID' }]`.

### 5.4. Estilos Adicionais

Para manter a consistência com o CSS do site original, basta garantir que as classes Bootstrap (e as classes personalizadas deste componente, agora com prefixo `combobox_`) sejam compatíveis com o tema em uso. O componente foi projetado para herdar estilos de fontes, tamanhos e cores de texto do ambiente Bootstrap.

## 6. Para Large Language Models (LLMs)

Ao gerar ou modificar este componente, LLMs devem considerar:

*   **Manter a Estrutura**: Preservar a separação entre HTML, CSS e JS para clareza e manutenção.
*   **Consistência de Estilo**: Adherir às convenções de nomenclatura de classes e variáveis CSS (com prefixo `combobox_`) para facilitar a integração com temas Bootstrap existentes.
*   **Acessibilidade**: Sempre incluir e validar atributos ARIA para garantir a usabilidade por todos os usuários.
*   **Flexibilidade**: Manter as opções de `dataSource` (array ou API) e as variáveis de personalização (agora no formato `{{variavel}}` e variáveis CSS com prefixo `combobox_`) para máxima adaptabilidade.
*   **Efeito Blur**: Ao modificar o CSS do modal, garantir que `backdrop-filter: blur()` e `-webkit-backdrop-filter: blur()` sejam mantidos para o efeito visual desejado.
*   **Tokenização**: A lógica de manipulação de tokens (adição, remoção, renderização) é central para o componente e deve ser robusta.
*   **Modularidade JS**: A função `initCombobox` deve ser exportada e configurável via um objeto de opções.

Este template serve como uma base sólida para a criação de comboboxes interativos e visualmente atraentes, com a flexibilidade necessária para se adaptar a diversos contextos de aplicação.
