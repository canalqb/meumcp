# Componente Menu Responsivo com Subitens

Este documento descreve um componente de menu moderno e responsivo, projetado para funcionar perfeitamente em dispositivos móveis e desktop. O componente oferece suporte a subitens inteligentes, carregamento dinâmico de conteúdo e uma experiência de usuário fluida. É otimizado para integração em aplicações web e serve como um template robusto para geração por Large Language Models (LLMs).

## 1. Introdução

O componente `Menu Responsivo` é uma solução completa para navegação em aplicações web. Ele combina um menu lateral (sidebar) em desktop com um menu colapsável em mobile, oferecendo uma experiência de navegação intuitiva e acessível.

### Principais Características:

*   **Responsividade**: Adapta-se automaticamente entre desktop e mobile (breakpoint em 992px).
*   **Subitens Inteligentes**: Ao clicar em um item pai com subitens, o menu expande sem fechar. Apenas ao clicar em um subitem o menu fecha (mobile).
*   **Carregamento Dinâmico**: Conteúdo é carregado via AJAX em `<main class="container my-5">`.
*   **Acessibilidade**: Suporte completo a navegação por teclado e atributos ARIA.
*   **Prefixação Consistente**: Todos os elementos HTML utilizam prefixo `menu_` e arquivos CSS/JS utilizam prefixo `menu-`.
*   **Variáveis de Template**: Formato `{{variavel}}` para facilitar uso em prompts de LLMs.
*   **Modularidade**: Código JavaScript separado e reutilizável.

## 2. Estrutura de Arquivos

O componente é composto por três arquivos principais:

```
projeto/
├── template-menu.php          # HTML template com variáveis {{variavel}}
├── css/
│   └── menu-styles.css        # Estilos CSS com prefixo menu-*
├── js/
│   └── menu-core.js           # Lógica JavaScript com prefixo menu-*
└── pages/
    └── (conteúdo carregado aqui)
```

## 3. Funcionalidades Detalhadas

### 3.1. Responsividade e Colapso em Mobile

O menu utiliza um breakpoint de **992px** (Bootstrap lg). Abaixo deste tamanho, o menu é transformado em um drawer (gaveta) que desliza da esquerda, com um overlay semitransparente ao fundo.

**Desktop (≥992px):**
*   Menu lateral fixo (sticky) na esquerda
*   Navbar mobile escondida
*   Conteúdo principal ocupa o espaço restante

**Mobile (<992px):**
*   Navbar com botão toggle para abrir/fechar menu
*   Menu desliza da esquerda com overlay
*   Conteúdo principal ocupa tela inteira

### 3.2. Subitens Inteligentes

O comportamento dos subitens é cuidadosamente projetado:

*   **Clique em item pai com subitens**: Expande o submenu sem fechar o menu principal (mobile ou desktop).
*   **Clique em subitem**: Carrega o conteúdo e fecha o menu (apenas mobile).
*   **Clique em item sem subitens**: Carrega o conteúdo e fecha o menu (apenas mobile).

**Implementação:**

```javascript
handleMenuItemClick(e, item) {
  const parentItem = item.closest(this.config.parentSelector);

  // Se tem submenu, expande/colapsa
  if (parentItem) {
    e.preventDefault();
    this.toggleSubmenu(parentItem);
    return;
  }

  // Se não tem submenu, carrega conteúdo
  e.preventDefault();
  const href = item.getAttribute('href');
  this.loadContent(href);
  
  // Fecha menu mobile
  if (!this.isDesktop()) {
    this.closeMobileMenu();
  }
}
```

### 3.3. Carregamento Dinâmico de Conteúdo

Quando um item é clicado, o conteúdo é carregado via AJAX (Fetch API) e inserido em `<main id="menu_main_content">`.

**Fluxo:**

1. Usuário clica em um item do menu
2. URL é extraída do atributo `href`
3. Requisição AJAX é feita para a URL
4. Resposta HTML é inserida em `#menu_content_loader`
5. Menu mobile é fechado (se aplicável)

**Exemplo de Uso:**

```html
<a class="menu_item_link" href="pages/dashboard.php">
  <i class="fas fa-chart-line"></i>
  <span>Dashboard</span>
</a>
```

### 3.4. Estrutura de Elementos HTML

Todos os elementos HTML utilizam o prefixo `menu_`:

| Elemento | Classe/ID | Descrição |
| :--- | :--- | :--- |
| Container | `menu_container` | Wrapper do menu sidebar |
| Header | `menu_header` | Cabeçalho com botão fechar (mobile) |
| Item | `menu_item` | Wrapper de um item do menu |
| Link | `menu_item_link` | Link do item |
| Item Pai | `menu_item_parent` | Item com subitens |
| Submenu | `menu_submenu` | Container dos subitens |
| Subitem | `menu_submenu_item` | Link do subitem |
| Seção | `menu_section_heading` | Título de seção |
| Overlay | `menu_overlay` | Overlay semitransparente (mobile) |
| Conteúdo | `menu_main_content` | Área principal de conteúdo |

## 4. Variáveis de Template

O arquivo `template-menu.php` utiliza variáveis no formato `{{variavel}}` para facilitar a personalização:

| Variável | Descrição | Exemplo |
| :--- | :--- | :--- |
| `{{menu_page_title}}` | Título da página | `Meu Aplicativo` |
| `{{menu_brand_title}}` | Título/marca na navbar mobile | `Meu App` |
| `{{menu_css_path}}` | Caminho para arquivo CSS | `css/menu-styles.css` |
| `{{menu_js_path}}` | Caminho para arquivo JS | `js/menu-core.js` |
| `{{menu_items_main}}` | Items do menu principal | (gerado dinamicamente) |
| `{{menu_items_featured}}` | Items destacados | (gerado dinamicamente) |
| `{{menu_items_bottom}}` | Items do rodapé (logout, etc) | (gerado dinamicamente) |

**Exemplo de Substituição:**

```php
<?php
$titulo = 'Dashboard';
$brand = 'MyApp';
$css_path = 'css/menu-styles.css';
$js_path = 'js/menu-core.js';

// Substituir variáveis
$template = file_get_contents('template-menu.php');
$template = str_replace('{{menu_page_title}}', $titulo, $template);
$template = str_replace('{{menu_brand_title}}', $brand, $template);
$template = str_replace('{{menu_css_path}}', $css_path, $template);
$template = str_replace('{{menu_js_path}}', $js_path, $template);

echo $template;
?>
```

## 5. Estrutura de Dados do Menu

O menu pode ser gerado dinamicamente a partir de um array ou banco de dados. A estrutura esperada é:

```php
$menu_items = [
  [
    'id' => 'dashboard',
    'label' => 'Dashboard',
    'href' => 'pages/dashboard.php',
    'icon' => 'fa-chart-line',
    'target' => '_self',
    'children' => []
  ],
  [
    'id' => 'settings',
    'label' => 'Configurações',
    'href' => '#',
    'icon' => 'fa-cog',
    'target' => '_self',
    'children' => [
      [
        'id' => 'profile',
        'label' => 'Perfil',
        'href' => 'pages/profile.php',
        'icon' => 'fa-user',
        'target' => '_self'
      ],
      [
        'id' => 'security',
        'label' => 'Segurança',
        'href' => 'pages/security.php',
        'icon' => 'fa-lock',
        'target' => '_self'
      ]
    ]
  ]
];
```

## 6. Geração de HTML do Menu

Para gerar o HTML do menu a partir dos dados, utilize a seguinte lógica:

```php
<?php
function renderMenuItems($items) {
  $html = '';
  foreach ($items as $item) {
    $has_children = !empty($item['children']);
    $class = $has_children ? 'menu_item menu_item_parent' : 'menu_item';
    
    $html .= "<div class=\"{$class}\">";
    $html .= "<a class=\"menu_item_link\" href=\"{$item['href']}\" target=\"{$item['target']}\">";
    $html .= "<i class=\"fas {$item['icon']}\"></i>";
    $html .= "<span>{$item['label']}</span>";
    
    if ($has_children) {
      $html .= "<i class=\"fas fa-chevron-down menu_item_chevron\"></i>";
    }
    
    $html .= "</a>";
    
    if ($has_children) {
      $html .= "<div class=\"menu_submenu\">";
      foreach ($item['children'] as $child) {
        $html .= "<a class=\"menu_submenu_item\" href=\"{$child['href']}\" target=\"{$child['target']}\">";
        $html .= "<i class=\"fas {$child['icon']}\"></i>";
        $html .= "<span>{$child['label']}</span>";
        $html .= "</a>";
      }
      $html .= "</div>";
    }
    
    $html .= "</div>";
  }
  return $html;
}
?>
```

## 7. Classe MenuManager (JavaScript)

A classe `MenuManager` gerencia toda a lógica do menu. Pode ser instanciada com configurações personalizadas:

```javascript
// Uso padrão
const menu = new MenuManager();

// Uso com configurações personalizadas
const menu = new MenuManager({
  containerSelector: '#menu_container',
  breakpoint: 1024,
  // ... outras opções
});

// Métodos públicos
menu.load('pages/dashboard.php');  // Carrega conteúdo
menu.open();                        // Abre menu mobile
menu.close();                       // Fecha menu mobile
```

**Configurações Disponíveis:**

```javascript
{
  containerSelector: '#menu_container',
  overlaySelector: '#menu_overlay',
  toggleSelector: '#menu_mobile_toggle',
  closeSelector: '#menu_close_btn',
  contentSelector: '#menu_main_content',
  contentLoaderSelector: '#menu_content_loader',
  itemSelector: '.menu_item_link',
  submenuItemSelector: '.menu_submenu_item',
  parentSelector: '.menu_item_parent',
  openClass: 'menu_item_open',
  mobileOpenClass: 'menu_mobile_open',
  overlayActiveClass: 'menu_overlay_active',
  itemActiveClass: 'menu_item_active',
  breakpoint: 992
}
```

## 8. Estilos CSS (menu-styles.css)

O arquivo `menu-styles.css` contém todos os estilos do componente. Variáveis CSS permitem fácil personalização:

```css
:root {
  --menu-primary-color: #0d6efd;
  --menu-primary-hover: #0b5ed7;
  --menu-bg-color: #f8f9fa;
  --menu-text-color: #212529;
  --menu-border-color: #dee2e6;
  --menu-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
  --menu-transition: 0.3s ease-in-out;
  --menu-sidebar-width: 280px;
}
```

**Personalização:**

```css
/* Sobrescrever variáveis */
:root {
  --menu-primary-color: #ff6b6b;
  --menu-sidebar-width: 320px;
}
```

## 9. Para Large Language Models (LLMs)

Ao gerar ou modificar este componente, LLMs devem considerar:

*   **Manter Prefixos**: Todos os elementos HTML devem usar `menu_`, CSS deve usar `menu-*`, e JS deve usar `menu-*`.
*   **Variáveis de Template**: Utilizar sempre o formato `{{variavel}}` para facilitar substituição.
*   **Responsividade**: Garantir que o breakpoint de 992px seja mantido e o comportamento mobile/desktop seja preservado.
*   **Subitens Inteligentes**: A lógica de expandir pai sem fechar menu é crítica e deve ser mantida.
*   **Carregamento AJAX**: Usar Fetch API para carregar conteúdo e inserir em `#menu_content_loader`.
*   **Acessibilidade**: Manter atributos ARIA e suporte a navegação por teclado.
*   **Modularidade**: Manter a separação entre HTML, CSS e JS para facilitar manutenção.

## 10. Exemplo de Uso Completo

```html
<!-- Incluir template -->
<?php
$menu_items = [
  [
    'id' => 'home',
    'label' => 'Início',
    'href' => 'pages/home.php',
    'icon' => 'fa-home',
    'target' => '_self',
    'children' => []
  ],
  [
    'id' => 'docs',
    'label' => 'Documentação',
    'href' => '#',
    'icon' => 'fa-book',
    'target' => '_self',
    'children' => [
      [
        'id' => 'guide',
        'label' => 'Guia',
        'href' => 'pages/guide.php',
        'icon' => 'fa-file',
        'target' => '_self'
      ]
    ]
  ]
];

$menu_html = renderMenuItems($menu_items);
?>

<!-- Substituir variáveis no template -->
<?php
$template = file_get_contents('template-menu.php');
$template = str_replace('{{menu_page_title}}', 'Meu App', $template);
$template = str_replace('{{menu_brand_title}}', 'MyApp', $template);
$template = str_replace('{{menu_css_path}}', 'css/menu-styles.css', $template);
$template = str_replace('{{menu_js_path}}', 'js/menu-core.js', $template);
$template = str_replace('{{menu_items_main}}', $menu_html, $template);
$template = str_replace('{{menu_items_featured}}', '', $template);
$template = str_replace('{{menu_items_bottom}}', '', $template);

echo $template;
?>
```

Este template oferece uma base sólida e flexível para criar menus responsivos e intuitivos em aplicações web modernas.
