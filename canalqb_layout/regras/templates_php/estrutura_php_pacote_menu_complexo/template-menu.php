<?php
/**
 * Template Menu com Navegação e Subitens
 * 
 * Componente de menu responsivo com:
 * - Colapsar/expandir em mobile
 * - Subitens inteligentes (expande pai, não colapsa até clicar em subitem)
 * - Carregamento de conteúdo em <main>
 * - Prefixo menu_ em todos os elementos HTML
 * - Variáveis de template {{variavel}} para LLMs
 * 
 * @author Sistema
 * @version 2.0
 */
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{menu_page_title}}</title>

  <!-- Bootstrap 5 -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <!-- Menu CSS -->
  <link href="{{menu_css_path}}" rel="stylesheet">
</head>

<body>
  <div class="d-flex">
    <!-- Navbar Mobile Toggle -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark w-100 d-lg-none">
      <div class="container-fluid">
        <span class="navbar-brand">{{menu_brand_title}}</span>
        <button class="navbar-toggler" type="button" id="menu_mobile_toggle" aria-controls="menu_container" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
      </div>
    </nav>

    <!-- Sidebar Menu -->
    <nav class="menu_container" id="menu_container">
      <!-- Menu Toggle Close Button (Mobile) -->
      <div class="menu_header d-lg-none">
        <button class="menu_close_btn" id="menu_close_btn" aria-label="Fechar menu">
          <i class="fas fa-times"></i>
        </button>
      </div>

      <!-- Menu Items Container -->
      <div class="menu_items_wrapper">
        <!-- Main Menu Items -->
        {{menu_items_main}}

        <!-- Featured Items Section -->
        {{menu_items_featured}}

        <!-- Bottom Menu Items (Logout, etc) -->
        {{menu_items_bottom}}
      </div>
    </nav>

    <!-- Overlay para Mobile -->
    <div class="menu_overlay" id="menu_overlay"></div>

    <!-- Main Content Area -->
    <main class="menu_main_content" id="menu_main_content">
      <div class="container my-5">
        <!-- Conteúdo carregado aqui -->
        <div id="menu_content_loader">
          <p class="text-center text-muted">Selecione um item do menu para começar</p>
        </div>
      </div>
    </main>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
  <!-- Menu JS -->
  <script src="{{menu_js_path}}" type="module"></script>
</body>
</html>
