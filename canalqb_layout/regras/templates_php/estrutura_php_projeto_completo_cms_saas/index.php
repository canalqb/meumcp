<!DOCTYPE html>
<html lang="{{cms_language}}">
  <head>
    <!-- {{cms_charset}} - Definir o charset da página -->
    <meta charset="UTF-8" />
    
    <!-- {{cms_viewport}} - Meta tag de viewport para responsividade -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    
    <!-- {{cms_page_title}} - Título da página -->
    <title>{{cms_site_name}} - Painel Administrativo</title>
    
    <!-- {{cms_page_description}} - Descrição da página -->
    <meta name="description" content="{{cms_site_description}}" />
    
    <!-- {{cms_page_keywords}} - Palavras-chave da página -->
    <meta name="keywords" content="{{cms_site_keywords}}" />
    
    <!-- {{cms_author}} - Autor da página -->
    <meta name="author" content="{{cms_author}}" />
    
    <!-- {{cms_favicon}} - Favicon da página -->
    <link rel="icon" type="image/x-icon" href="{{cms_favicon_url}}" />
    
    <!-- {{cms_apple_touch_icon}} - Apple Touch Icon -->
    <link rel="apple-touch-icon" href="{{cms_apple_touch_icon_url}}" />
    
    <!-- {{cms_bootstrap_css}} - Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    
    <!-- {{cms_bootstrap_icons}} - Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet" />
    
    <!-- {{cms_custom_styles}} - Estilos customizados do CMS -->
    <link rel="stylesheet" href="css/cms-styles.css" />
    <link rel="stylesheet" href="css/cms-theme.css" />
    
    <!-- {{cms_custom_head_scripts}} - Scripts customizados no head -->
    {{cms_custom_head_scripts}}
    
    <!-- {{cms_schema_markup}} - Schema Markup JSON-LD -->
    <script type="application/ld+json">
      {
        "@context": "https://schema.org",
        "@type": "WebSite",
        "name": "{{cms_site_name}}",
        "description": "{{cms_site_description}}",
        "url": "{{cms_site_url}}",
        "potentialAction": {
          "@type": "SearchAction",
          "target": {
            "@type": "EntryPoint",
            "urlTemplate": "{{cms_site_url}}/search?q={search_term_string}"
          },
          "query-input": "required name=search_term_string"
        }
      }
    </script>
  </head>
  
  <body>
    <!-- {{cms_layout_wrapper}} - Container principal do layout -->
    <div class="cms_layout">
      <!-- ============================================
           SIDEBAR - NAVEGAÇÃO LATERAL
           ============================================ -->
      <aside class="cms_sidebar">
        <!-- {{cms_sidebar_header}} - Cabeçalho da sidebar -->
        <div class="cms_card cms_mb-3">
          <!-- {{cms_site_logo}} - Logo do site -->
          <div class="cms_text-center cms_mb-2">
            <img src="{{cms_logo_url}}" alt="{{cms_site_name}}" style="max-width: 100%; height: auto; max-height: 60px;" />
          </div>
          
          <!-- {{cms_site_name_display}} - Nome do site na sidebar -->
          <h5 class="cms_text-center cms_mb-0" data-cms-site-name>{{cms_site_name}}</h5>
          <p class="cms_text-center cms_text-muted cms_font-size-sm" data-cms-site-slogan>{{cms_site_slogan}}</p>
        </div>
        
        <!-- {{cms_navigation_menu}} - Menu de navegação -->
        <nav class="cms_nav">
          <!-- {{cms_nav_dashboard}} - Item: Dashboard -->
          <li class="cms_nav-item">
            <a href="#dashboard" class="cms_nav-link active" data-cms-tab="dashboard">
              <i class="bi bi-speedometer2"></i> Dashboard
            </a>
          </li>
          
          <!-- {{cms_nav_identity}} - Item: Identidade do Site -->
          <li class="cms_nav-item">
            <a href="#identity" class="cms_nav-link" data-cms-tab="identity">
              <i class="bi bi-building"></i> Identidade
            </a>
          </li>
          
          <!-- {{cms_nav_seo}} - Item: SEO -->
          <li class="cms_nav-item">
            <a href="#seo" class="cms_nav-link" data-cms-tab="seo">
              <i class="bi bi-search"></i> SEO
            </a>
          </li>
          
          <!-- {{cms_nav_ads}} - Item: Ads & Pixels -->
          <li class="cms_nav-item">
            <a href="#ads" class="cms_nav-link" data-cms-tab="ads">
              <i class="bi bi-megaphone"></i> Ads & Pixels
            </a>
          </li>
          
          <!-- {{cms_nav_layout}} - Item: Layout & UI -->
          <li class="cms_nav-item">
            <a href="#layout" class="cms_nav-link" data-cms-tab="layout">
              <i class="bi bi-palette"></i> Layout & UI
            </a>
          </li>
          
          <!-- {{cms_nav_images}} - Item: Imagens -->
          <li class="cms_nav-item">
            <a href="#images" class="cms_nav-link" data-cms-tab="images">
              <i class="bi bi-image"></i> Imagens
            </a>
          </li>
          
          <!-- {{cms_nav_performance}} - Item: Performance -->
          <li class="cms_nav-item">
            <a href="#performance" class="cms_nav-link" data-cms-tab="performance">
              <i class="bi bi-lightning"></i> Performance
            </a>
          </li>
          
          <!-- {{cms_nav_security}} - Item: Segurança -->
          <li class="cms_nav-item">
            <a href="#security" class="cms_nav-link" data-cms-tab="security">
              <i class="bi bi-shield-lock"></i> Segurança
            </a>
          </li>
          
          <!-- {{cms_nav_extras}} - Item: Configurações Extras -->
          <li class="cms_nav-item">
            <a href="#extras" class="cms_nav-link" data-cms-tab="extras">
              <i class="bi bi-gear"></i> Extras
            </a>
          </li>
        </nav>
        
        <!-- {{cms_sidebar_footer}} - Rodapé da sidebar -->
        <div class="cms_card cms_mt-3 cms_p-2">
          <button class="cms_btn cms_btn-outline cms_btn-sm cms_w-100 cms_mb-2" id="cms_theme_toggle">
            <i class="bi bi-moon"></i> Tema Escuro
          </button>
          <button class="cms_btn cms_btn-danger cms_btn-sm cms_w-100" id="cms_logout_btn">
            <i class="bi bi-box-arrow-right"></i> Sair
          </button>
        </div>
      </aside>
      
      <!-- ============================================
           MAIN CONTENT - CONTEÚDO PRINCIPAL
           ============================================ -->
      <main class="cms_main-content">
        <!-- {{cms_header}} - Cabeçalho da página -->
        <div class="cms_header">
          <div>
            <h1 class="cms_header-title">Painel Administrativo</h1>
            <p class="cms_header-subtitle">Gerencie todas as configurações do seu site</p>
          </div>
          <div>
            <span class="cms_badge cms_badge-info">v1.0.0</span>
          </div>
        </div>
        
        <!-- {{cms_notifications_container}} - Container de notificações -->
        <div id="cms_notifications_container"></div>
        
        <!-- {{cms_modified_indicator}} - Indicador de modificações -->
        <div id="cms_settings_modified_indicator" class="cms_alert cms_alert-warning" style="display: none;">
          <strong>Atenção!</strong> Você tem alterações não salvas. Clique em "Salvar" para confirmar.
        </div>
        
        <!-- {{cms_tabs_container}} - Container de abas -->
        <div class="cms_tabs">
          <!-- {{cms_tab_dashboard}} - Aba: Dashboard -->
          <button class="cms_tab-button active" data-cms-tab="dashboard">
            <i class="bi bi-speedometer2"></i> Dashboard
          </button>
          
          <!-- {{cms_tab_identity}} - Aba: Identidade -->
          <button class="cms_tab-button" data-cms-tab="identity">
            <i class="bi bi-building"></i> Identidade
          </button>
          
          <!-- {{cms_tab_seo}} - Aba: SEO -->
          <button class="cms_tab-button" data-cms-tab="seo">
            <i class="bi bi-search"></i> SEO
          </button>
          
          <!-- {{cms_tab_ads}} - Aba: Ads & Pixels -->
          <button class="cms_tab-button" data-cms-tab="ads">
            <i class="bi bi-megaphone"></i> Ads & Pixels
          </button>
          
          <!-- {{cms_tab_layout}} - Aba: Layout & UI -->
          <button class="cms_tab-button" data-cms-tab="layout">
            <i class="bi bi-palette"></i> Layout & UI
          </button>
          
          <!-- {{cms_tab_images}} - Aba: Imagens -->
          <button class="cms_tab-button" data-cms-tab="images">
            <i class="bi bi-image"></i> Imagens
          </button>
          
          <!-- {{cms_tab_performance}} - Aba: Performance -->
          <button class="cms_tab-button" data-cms-tab="performance">
            <i class="bi bi-lightning"></i> Performance
          </button>
          
          <!-- {{cms_tab_security}} - Aba: Segurança -->
          <button class="cms_tab-button" data-cms-tab="security">
            <i class="bi bi-shield-lock"></i> Segurança
          </button>
          
          <!-- {{cms_tab_extras}} - Aba: Extras -->
          <button class="cms_tab-button" data-cms-tab="extras">
            <i class="bi bi-gear"></i> Extras
          </button>
        </div>
        
        <!-- ============================================
             CONTEÚDO DAS ABAS
             ============================================ -->
        
        <!-- {{cms_content_dashboard}} - Conteúdo: Dashboard -->
        <div id="cms_content_dashboard" class="cms_tab-content active">
          <div class="cms_card">
            <div class="cms_card-header">
              <h3 class="cms_card-title">
                <i class="bi bi-speedometer2"></i> Dashboard
              </h3>
              <p class="cms_card-subtitle">Visão geral do sistema</p>
            </div>
            <div class="cms_card-body">
              <div class="row">
                <!-- {{cms_stat_pages}} - Estatística: Páginas -->
                <div class="col-md-3 cms_mb-3">
                  <div class="cms_card cms_text-center">
                    <div class="cms_p-3">
                      <i class="bi bi-file-text" style="font-size: 2rem; color: #0d6efd;"></i>
                      <h4 class="cms_mt-2">0</h4>
                      <p class="cms_text-muted cms_mb-0">Páginas</p>
                    </div>
                  </div>
                </div>
                
                <!-- {{cms_stat_posts}} - Estatística: Posts -->
                <div class="col-md-3 cms_mb-3">
                  <div class="cms_card cms_text-center">
                    <div class="cms_p-3">
                      <i class="bi bi-newspaper" style="font-size: 2rem; color: #198754;"></i>
                      <h4 class="cms_mt-2">0</h4>
                      <p class="cms_text-muted cms_mb-0">Posts</p>
                    </div>
                  </div>
                </div>
                
                <!-- {{cms_stat_users}} - Estatística: Usuários -->
                <div class="col-md-3 cms_mb-3">
                  <div class="cms_card cms_text-center">
                    <div class="cms_p-3">
                      <i class="bi bi-people" style="font-size: 2rem; color: #0dcaf0;"></i>
                      <h4 class="cms_mt-2">0</h4>
                      <p class="cms_text-muted cms_mb-0">Usuários</p>
                    </div>
                  </div>
                </div>
                
                <!-- {{cms_stat_comments}} - Estatística: Comentários -->
                <div class="col-md-3 cms_mb-3">
                  <div class="cms_card cms_text-center">
                    <div class="cms_p-3">
                      <i class="bi bi-chat-dots" style="font-size: 2rem; color: #ffc107;"></i>
                      <h4 class="cms_mt-2">0</h4>
                      <p class="cms_text-muted cms_mb-0">Comentários</p>
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="cms_mt-3">
                <p class="cms_text-muted">
                  Bem-vindo ao painel administrativo do CMS. Use as abas acima para gerenciar as configurações do seu site.
                </p>
              </div>
            </div>
          </div>
        </div>
        
        <!-- {{cms_content_identity}} - Conteúdo: Identidade do Site -->
        <div id="cms_content_identity" class="cms_tab-content">
          <div class="cms_card">
            <div class="cms_card-header">
              <h3 class="cms_card-title">
                <i class="bi bi-building"></i> Identidade do Site
              </h3>
              <p class="cms_card-subtitle">Configure o nome, slogan e informações básicas do seu site</p>
            </div>
            <div class="cms_card-body">
              <form data-cms-form="identity">
                <!-- {{cms_form_site_name}} - Campo: Nome do Site -->
                <div class="cms_form-group">
                  <label for="cms_site_name" class="cms_form-label required">Nome do Site</label>
                  <input
                    type="text"
                    class="cms_form-control"
                    id="cms_site_name"
                    name="site_name"
                    data-cms-setting="site_name"
                    placeholder="Ex: Meu Site"
                    required
                  />
                  <small class="cms_form-text">Nome principal do seu site</small>
                </div>
                
                <!-- {{cms_form_site_slogan}} - Campo: Slogan -->
                <div class="cms_form-group">
                  <label for="cms_site_slogan" class="cms_form-label">Slogan</label>
                  <input
                    type="text"
                    class="cms_form-control"
                    id="cms_site_slogan"
                    name="site_slogan"
                    data-cms-setting="site_slogan"
                    placeholder="Ex: Seu slogan aqui"
                  />
                  <small class="cms_form-text">Slogan ou tagline do seu site</small>
                </div>
                
                <!-- {{cms_form_site_description}} - Campo: Descrição -->
                <div class="cms_form-group">
                  <label for="cms_site_description" class="cms_form-label">Descrição</label>
                  <textarea
                    class="cms_form-control"
                    id="cms_site_description"
                    name="site_description"
                    data-cms-setting="site_description"
                    rows="3"
                    placeholder="Descrição do seu site"
                  ></textarea>
                  <small class="cms_form-text">Descrição breve do seu site</small>
                </div>
                
                <!-- {{cms_form_site_keywords}} - Campo: Palavras-chave -->
                <div class="cms_form-group">
                  <label for="cms_site_keywords" class="cms_form-label">Palavras-chave</label>
                  <input
                    type="text"
                    class="cms_form-control"
                    id="cms_site_keywords"
                    name="site_keywords"
                    data-cms-setting="site_keywords"
                    placeholder="Ex: palavra1, palavra2, palavra3"
                  />
                  <small class="cms_form-text">Palavras-chave separadas por vírgula</small>
                </div>
                
                <!-- {{cms_form_author}} - Campo: Autor -->
                <div class="cms_form-group">
                  <label for="cms_author" class="cms_form-label">Autor</label>
                  <input
                    type="text"
                    class="cms_form-control"
                    id="cms_author"
                    name="author"
                    data-cms-setting="author"
                    placeholder="Ex: Seu Nome"
                  />
                  <small class="cms_form-text">Autor padrão do site</small>
                </div>
                
                <!-- {{cms_form_language}} - Campo: Idioma -->
                <div class="cms_form-group">
                  <label for="cms_language" class="cms_form-label">Idioma</label>
                  <select class="cms_form-control" id="cms_language" name="language" data-cms-setting="language">
                    <option value="pt-BR">Português (Brasil)</option>
                    <option value="en-US">English (United States)</option>
                    <option value="es-ES">Español (España)</option>
                  </select>
                  <small class="cms_form-text">Idioma padrão do site</small>
                </div>
              </form>
            </div>
            <div class="cms_card-footer">
              <button class="cms_btn cms_btn-secondary" id="cms_settings_reset_btn">
                <i class="bi bi-arrow-counterclockwise"></i> Descartar
              </button>
              <button class="cms_btn cms_btn-primary" id="cms_settings_save_btn">
                <i class="bi bi-check-circle"></i> Salvar
              </button>
            </div>
          </div>
        </div>
        
        <!-- {{cms_content_seo}} - Conteúdo: SEO -->
        <div id="cms_content_seo" class="cms_tab-content">
          <div class="cms_card">
            <div class="cms_card-header">
              <h3 class="cms_card-title">
                <i class="bi bi-search"></i> Configurações de SEO
              </h3>
              <p class="cms_card-subtitle">Otimize seu site para mecanismos de busca</p>
            </div>
            <div class="cms_card-body">
              <form data-cms-form="seo">
                <!-- {{cms_form_meta_description}} - Campo: Meta Description -->
                <div class="cms_form-group">
                  <label for="cms_meta_description" class="cms_form-label">Meta Description</label>
                  <textarea
                    class="cms_form-control"
                    id="cms_meta_description"
                    name="meta_description"
                    data-cms-setting="meta_description"
                    rows="2"
                    placeholder="Descrição para mecanismos de busca"
                    maxlength="160"
                  ></textarea>
                  <small class="cms_form-text">Máximo 160 caracteres</small>
                </div>
                
                <!-- {{cms_form_og_title}} - Campo: Open Graph Title -->
                <div class="cms_form-group">
                  <label for="cms_og_title" class="cms_form-label">Open Graph - Título</label>
                  <input
                    type="text"
                    class="cms_form-control"
                    id="cms_og_title"
                    name="og_title"
                    data-cms-setting="og_title"
                    placeholder="Título para redes sociais"
                  />
                  <small class="cms_form-text">Título exibido ao compartilhar em redes sociais</small>
                </div>
                
                <!-- {{cms_form_og_description}} - Campo: Open Graph Description -->
                <div class="cms_form-group">
                  <label for="cms_og_description" class="cms_form-label">Open Graph - Descrição</label>
                  <textarea
                    class="cms_form-control"
                    id="cms_og_description"
                    name="og_description"
                    data-cms-setting="og_description"
                    rows="2"
                    placeholder="Descrição para redes sociais"
                  ></textarea>
                  <small class="cms_form-text">Descrição exibida ao compartilhar em redes sociais</small>
                </div>
                
                <!-- {{cms_form_twitter_card}} - Campo: Twitter Card -->
                <div class="cms_form-group">
                  <label for="cms_twitter_card" class="cms_form-label">Twitter Card</label>
                  <select class="cms_form-control" id="cms_twitter_card" name="twitter_card" data-cms-setting="twitter_card">
                    <option value="summary">Summary</option>
                    <option value="summary_large_image">Summary Large Image</option>
                    <option value="app">App</option>
                  </select>
                  <small class="cms_form-text">Tipo de card do Twitter</small>
                </div>
                
                <!-- {{cms_form_canonical_url}} - Campo: Canonical URL -->
                <div class="cms_form-group">
                  <label for="cms_canonical_url" class="cms_form-label">Canonical URL</label>
                  <input
                    type="url"
                    class="cms_form-control"
                    id="cms_canonical_url"
                    name="canonical_url"
                    data-cms-setting="canonical_url"
                    placeholder="https://seu-site.com"
                  />
                  <small class="cms_form-text">URL canônica do seu site</small>
                </div>
                
                <!-- {{cms_form_robots_txt}} - Campo: Robots.txt -->
                <div class="cms_form-group">
                  <label for="cms_robots_txt" class="cms_form-label">Robots.txt</label>
                  <textarea
                    class="cms_form-control cms_font-mono"
                    id="cms_robots_txt"
                    name="robots_txt"
                    data-cms-setting="robots_txt"
                    rows="4"
                    placeholder="User-agent: *&#10;Disallow: /admin"
                  ></textarea>
                  <small class="cms_form-text">Conteúdo do arquivo robots.txt</small>
                </div>
              </form>
            </div>
            <div class="cms_card-footer">
              <button class="cms_btn cms_btn-secondary" id="cms_settings_reset_btn">
                <i class="bi bi-arrow-counterclockwise"></i> Descartar
              </button>
              <button class="cms_btn cms_btn-primary" id="cms_settings_save_btn">
                <i class="bi bi-check-circle"></i> Salvar
              </button>
            </div>
          </div>
        </div>
        
        <!-- {{cms_content_ads}} - Conteúdo: Ads & Pixels -->
        <div id="cms_content_ads" class="cms_tab-content">
          <div class="cms_card">
            <div class="cms_card-header">
              <h3 class="cms_card-title">
                <i class="bi bi-megaphone"></i> Ads & Pixels
              </h3>
              <p class="cms_card-subtitle">Configure rastreamento e publicidade</p>
            </div>
            <div class="cms_card-body">
              <form data-cms-form="ads">
                <!-- {{cms_form_google_analytics}} - Campo: Google Analytics -->
                <div class="cms_form-group">
                  <label for="cms_google_analytics" class="cms_form-label">Google Analytics ID</label>
                  <input
                    type="text"
                    class="cms_form-control"
                    id="cms_google_analytics"
                    name="google_analytics"
                    data-cms-setting="google_analytics"
                    placeholder="Ex: G-XXXXXXXXXX"
                  />
                  <small class="cms_form-text">ID do Google Analytics</small>
                </div>
                
                <!-- {{cms_form_google_tag_manager}} - Campo: Google Tag Manager -->
                <div class="cms_form-group">
                  <label for="cms_google_tag_manager" class="cms_form-label">Google Tag Manager ID</label>
                  <input
                    type="text"
                    class="cms_form-control"
                    id="cms_google_tag_manager"
                    name="google_tag_manager"
                    data-cms-setting="google_tag_manager"
                    placeholder="Ex: GTM-XXXXXXX"
                  />
                  <small class="cms_form-text">ID do Google Tag Manager</small>
                </div>
                
                <!-- {{cms_form_facebook_pixel}} - Campo: Facebook Pixel -->
                <div class="cms_form-group">
                  <label for="cms_facebook_pixel" class="cms_form-label">Facebook Pixel ID</label>
                  <input
                    type="text"
                    class="cms_form-control"
                    id="cms_facebook_pixel"
                    name="facebook_pixel"
                    data-cms-setting="facebook_pixel"
                    placeholder="Ex: 123456789"
                  />
                  <small class="cms_form-text">ID do Facebook Pixel</small>
                </div>
                
                <!-- {{cms_form_bing_webmaster}} - Campo: Bing Webmaster -->
                <div class="cms_form-group">
                  <label for="cms_bing_webmaster" class="cms_form-label">Bing Webmaster Verification</label>
                  <input
                    type="text"
                    class="cms_form-control"
                    id="cms_bing_webmaster"
                    name="bing_webmaster"
                    data-cms-setting="bing_webmaster"
                    placeholder="Ex: XXXXXXXXXXXXXXXX"
                  />
                  <small class="cms_form-text">Código de verificação do Bing Webmaster</small>
                </div>
                
                <!-- {{cms_form_custom_head_scripts}} - Campo: Scripts Customizados no Head -->
                <div class="cms_form-group">
                  <label for="cms_custom_head_scripts" class="cms_form-label">Scripts Customizados (Head)</label>
                  <textarea
                    class="cms_form-control cms_font-mono"
                    id="cms_custom_head_scripts"
                    name="custom_head_scripts"
                    data-cms-setting="custom_head_scripts"
                    rows="4"
                    placeholder="&lt;script&gt;...&lt;/script&gt;"
                  ></textarea>
                  <small class="cms_form-text">Scripts adicionais a serem incluídos no &lt;head&gt;</small>
                </div>
              </form>
            </div>
            <div class="cms_card-footer">
              <button class="cms_btn cms_btn-secondary" id="cms_settings_reset_btn">
                <i class="bi bi-arrow-counterclockwise"></i> Descartar
              </button>
              <button class="cms_btn cms_btn-primary" id="cms_settings_save_btn">
                <i class="bi bi-check-circle"></i> Salvar
              </button>
            </div>
          </div>
        </div>
        
        <!-- {{cms_content_layout}} - Conteúdo: Layout & UI -->
        <div id="cms_content_layout" class="cms_tab-content">
          <div class="cms_card">
            <div class="cms_card-header">
              <h3 class="cms_card-title">
                <i class="bi bi-palette"></i> Layout & UI
              </h3>
              <p class="cms_card-subtitle">Customize cores e aparência do site</p>
            </div>
            <div class="cms_card-body">
              <form data-cms-form="layout">
                <!-- {{cms_form_primary_color}} - Campo: Cor Primária -->
                <div class="cms_form-group">
                  <label for="cms_primary_color" class="cms_form-label">Cor Primária</label>
                  <div class="cms_form-row">
                    <input
                      type="color"
                      class="cms_form-control"
                      id="cms_primary_color"
                      name="primary_color"
                      data-cms-setting="primary_color"
                      value="#0d6efd"
                      style="max-width: 100px; height: 45px; cursor: pointer;"
                    />
                    <input
                      type="text"
                      class="cms_form-control"
                      id="cms_primary_color_hex"
                      placeholder="#0d6efd"
                      style="flex: 1;"
                    />
                  </div>
                  <small class="cms_form-text">Cor primária do tema</small>
                </div>
                
                <!-- {{cms_form_secondary_color}} - Campo: Cor Secundária -->
                <div class="cms_form-group">
                  <label for="cms_secondary_color" class="cms_form-label">Cor Secundária</label>
                  <div class="cms_form-row">
                    <input
                      type="color"
                      class="cms_form-control"
                      id="cms_secondary_color"
                      name="secondary_color"
                      data-cms-setting="secondary_color"
                      value="#6c757d"
                      style="max-width: 100px; height: 45px; cursor: pointer;"
                    />
                    <input
                      type="text"
                      class="cms_form-control"
                      id="cms_secondary_color_hex"
                      placeholder="#6c757d"
                      style="flex: 1;"
                    />
                  </div>
                  <small class="cms_form-text">Cor secundária do tema</small>
                </div>
                
                <!-- {{cms_form_font_family}} - Campo: Família de Fonte -->
                <div class="cms_form-group">
                  <label for="cms_font_family" class="cms_form-label">Família de Fonte</label>
                  <select class="cms_form-control" id="cms_font_family" name="font_family" data-cms-setting="font_family">
                    <option value="Arial, sans-serif">Arial</option>
                    <option value="'Helvetica Neue', sans-serif">Helvetica Neue</option>
                    <option value="'Times New Roman', serif">Times New Roman</option>
                    <option value="'Courier New', monospace">Courier New</option>
                    <option value="Georgia, serif">Georgia</option>
                  </select>
                  <small class="cms_form-text">Fonte padrão do site</small>
                </div>
                
                <!-- {{cms_form_layout_type}} - Campo: Tipo de Layout -->
                <div class="cms_form-group">
                  <label class="cms_form-label">Tipo de Layout</label>
                  <div>
                    <div class="form-check">
                      <input
                        class="form-check-input"
                        type="radio"
                        name="layout_type"
                        id="cms_layout_boxed"
                        value="boxed"
                        data-cms-setting="layout_type"
                      />
                      <label class="form-check-label" for="cms_layout_boxed">
                        Boxed (Centralizado)
                      </label>
                    </div>
                    <div class="form-check">
                      <input
                        class="form-check-input"
                        type="radio"
                        name="layout_type"
                        id="cms_layout_fullwidth"
                        value="fullwidth"
                        data-cms-setting="layout_type"
                      />
                      <label class="form-check-label" for="cms_layout_fullwidth">
                        Full Width (Largura Completa)
                      </label>
                    </div>
                  </div>
                  <small class="cms_form-text">Layout do site</small>
                </div>
              </form>
            </div>
            <div class="cms_card-footer">
              <button class="cms_btn cms_btn-secondary" id="cms_settings_reset_btn">
                <i class="bi bi-arrow-counterclockwise"></i> Descartar
              </button>
              <button class="cms_btn cms_btn-primary" id="cms_settings_save_btn">
                <i class="bi bi-check-circle"></i> Salvar
              </button>
            </div>
          </div>
        </div>
        
        <!-- {{cms_content_images}} - Conteúdo: Imagens -->
        <div id="cms_content_images" class="cms_tab-content">
          <div class="cms_card">
            <div class="cms_card-header">
              <h3 class="cms_card-title">
                <i class="bi bi-image"></i> Imagens
              </h3>
              <p class="cms_card-subtitle">Gerencie logos, favicon e imagens padrão</p>
            </div>
            <div class="cms_card-body">
              <form data-cms-form="images">
                <!-- {{cms_form_logo}} - Campo: Logo Principal -->
                <div class="cms_form-group">
                  <label for="cms_logo" class="cms_form-label">Logo Principal</label>
                  <div class="cms_form-control-file" data-cms-file-upload="logo">
                    <i class="bi bi-cloud-upload" style="font-size: 2rem;"></i>
                    <p>Clique ou arraste a imagem aqui</p>
                    <small>PNG, JPG, SVG (máx. 5MB)</small>
                  </div>
                  <img id="cms_logo_preview" src="" alt="Logo Preview" style="display: none; max-width: 200px; margin-top: 10px;" />
                </div>
                
                <!-- {{cms_form_favicon}} - Campo: Favicon -->
                <div class="cms_form-group">
                  <label for="cms_favicon" class="cms_form-label">Favicon</label>
                  <div class="cms_form-control-file" data-cms-file-upload="favicon">
                    <i class="bi bi-cloud-upload" style="font-size: 2rem;"></i>
                    <p>Clique ou arraste a imagem aqui</p>
                    <small>ICO, PNG (máx. 1MB)</small>
                  </div>
                  <img id="cms_favicon_preview" src="" alt="Favicon Preview" style="display: none; max-width: 100px; margin-top: 10px;" />
                </div>
                
                <!-- {{cms_form_og_image}} - Campo: Open Graph Image -->
                <div class="cms_form-group">
                  <label for="cms_og_image" class="cms_form-label">Open Graph Image</label>
                  <div class="cms_form-control-file" data-cms-file-upload="og_image">
                    <i class="bi bi-cloud-upload" style="font-size: 2rem;"></i>
                    <p>Clique ou arraste a imagem aqui</p>
                    <small>PNG, JPG (1200x630px, máx. 5MB)</small>
                  </div>
                  <img id="cms_og_image_preview" src="" alt="OG Image Preview" style="display: none; max-width: 200px; margin-top: 10px;" />
                </div>
              </form>
            </div>
            <div class="cms_card-footer">
              <button class="cms_btn cms_btn-secondary" id="cms_settings_reset_btn">
                <i class="bi bi-arrow-counterclockwise"></i> Descartar
              </button>
              <button class="cms_btn cms_btn-primary" id="cms_settings_save_btn">
                <i class="bi bi-check-circle"></i> Salvar
              </button>
            </div>
          </div>
        </div>
        
        <!-- {{cms_content_performance}} - Conteúdo: Performance -->
        <div id="cms_content_performance" class="cms_tab-content">
          <div class="cms_card">
            <div class="cms_card-header">
              <h3 class="cms_card-title">
                <i class="bi bi-lightning"></i> Performance
              </h3>
              <p class="cms_card-subtitle">Otimize a velocidade e performance do seu site</p>
            </div>
            <div class="cms_card-body">
              <form data-cms-form="performance">
                <!-- {{cms_form_enable_cache}} - Campo: Ativar Cache -->
                <div class="cms_form-group">
                  <div class="form-check">
                    <input
                      class="form-check-input"
                      type="checkbox"
                      id="cms_enable_cache"
                      name="enable_cache"
                      data-cms-setting="enable_cache"
                    />
                    <label class="form-check-label" for="cms_enable_cache">
                      Ativar Cache
                    </label>
                  </div>
                  <small class="cms_form-text">Ativar sistema de cache para melhorar performance</small>
                </div>
                
                <!-- {{cms_form_cache_time}} - Campo: Tempo de Cache -->
                <div class="cms_form-group">
                  <label for="cms_cache_time" class="cms_form-label">Tempo de Cache (horas)</label>
                  <input
                    type="number"
                    class="cms_form-control"
                    id="cms_cache_time"
                    name="cache_time"
                    data-cms-setting="cache_time"
                    value="24"
                    min="1"
                    max="720"
                  />
                  <small class="cms_form-text">Tempo em horas para manter o cache</small>
                </div>
                
                <!-- {{cms_form_minify_html}} - Campo: Minificar HTML -->
                <div class="cms_form-group">
                  <div class="form-check">
                    <input
                      class="form-check-input"
                      type="checkbox"
                      id="cms_minify_html"
                      name="minify_html"
                      data-cms-setting="minify_html"
                    />
                    <label class="form-check-label" for="cms_minify_html">
                      Minificar HTML
                    </label>
                  </div>
                  <small class="cms_form-text">Remover espaços em branco desnecessários do HTML</small>
                </div>
                
                <!-- {{cms_form_minify_css}} - Campo: Minificar CSS -->
                <div class="cms_form-group">
                  <div class="form-check">
                    <input
                      class="form-check-input"
                      type="checkbox"
                      id="cms_minify_css"
                      name="minify_css"
                      data-cms-setting="minify_css"
                    />
                    <label class="form-check-label" for="cms_minify_css">
                      Minificar CSS
                    </label>
                  </div>
                  <small class="cms_form-text">Remover espaços em branco desnecessários do CSS</small>
                </div>
                
                <!-- {{cms_form_minify_js}} - Campo: Minificar JavaScript -->
                <div class="cms_form-group">
                  <div class="form-check">
                    <input
                      class="form-check-input"
                      type="checkbox"
                      id="cms_minify_js"
                      name="minify_js"
                      data-cms-setting="minify_js"
                    />
                    <label class="form-check-label" for="cms_minify_js">
                      Minificar JavaScript
                    </label>
                  </div>
                  <small class="cms_form-text">Remover espaços em branco desnecessários do JavaScript</small>
                </div>
                
                <!-- {{cms_form_lazy_load}} - Campo: Lazy Load de Imagens -->
                <div class="cms_form-group">
                  <div class="form-check">
                    <input
                      class="form-check-input"
                      type="checkbox"
                      id="cms_lazy_load"
                      name="lazy_load"
                      data-cms-setting="lazy_load"
                    />
                    <label class="form-check-label" for="cms_lazy_load">
                      Lazy Load de Imagens
                    </label>
                  </div>
                  <small class="cms_form-text">Carregar imagens apenas quando necessário</small>
                </div>
                
                <!-- {{cms_form_cdn_url}} - Campo: URL do CDN -->
                <div class="cms_form-group">
                  <label for="cms_cdn_url" class="cms_form-label">URL do CDN</label>
                  <input
                    type="url"
                    class="cms_form-control"
                    id="cms_cdn_url"
                    name="cdn_url"
                    data-cms-setting="cdn_url"
                    placeholder="https://cdn.seu-site.com"
                  />
                  <small class="cms_form-text">URL do seu CDN para servir assets</small>
                </div>
                
                <!-- {{cms_button_clear_cache}} - Botão: Limpar Cache -->
                <div class="cms_form-group">
                  <button type="button" class="cms_btn cms_btn-warning" onclick="CMSSettings.clearCache()">
                    <i class="bi bi-trash"></i> Limpar Cache Agora
                  </button>
                </div>
              </form>
            </div>
            <div class="cms_card-footer">
              <button class="cms_btn cms_btn-secondary" id="cms_settings_reset_btn">
                <i class="bi bi-arrow-counterclockwise"></i> Descartar
              </button>
              <button class="cms_btn cms_btn-primary" id="cms_settings_save_btn">
                <i class="bi bi-check-circle"></i> Salvar
              </button>
            </div>
          </div>
        </div>
        
        <!-- {{cms_content_security}} - Conteúdo: Segurança -->
        <div id="cms_content_security" class="cms_tab-content">
          <div class="cms_card">
            <div class="cms_card-header">
              <h3 class="cms_card-title">
                <i class="bi bi-shield-lock"></i> Segurança
              </h3>
              <p class="cms_card-subtitle">Configure opções de segurança do seu site</p>
            </div>
            <div class="cms_card-body">
              <form data-cms-form="security">
                <!-- {{cms_form_force_https}} - Campo: Forçar HTTPS -->
                <div class="cms_form-group">
                  <div class="form-check">
                    <input
                      class="form-check-input"
                      type="checkbox"
                      id="cms_force_https"
                      name="force_https"
                      data-cms-setting="force_https"
                    />
                    <label class="form-check-label" for="cms_force_https">
                      Forçar HTTPS
                    </label>
                  </div>
                  <small class="cms_form-text">Redirecionar todo tráfego para HTTPS</small>
                </div>
                
                <!-- {{cms_form_enable_hsts}} - Campo: Ativar HSTS -->
                <div class="cms_form-group">
                  <div class="form-check">
                    <input
                      class="form-check-input"
                      type="checkbox"
                      id="cms_enable_hsts"
                      name="enable_hsts"
                      data-cms-setting="enable_hsts"
                    />
                    <label class="form-check-label" for="cms_enable_hsts">
                      Ativar HSTS
                    </label>
                  </div>
                  <small class="cms_form-text">HTTP Strict Transport Security</small>
                </div>
                
                <!-- {{cms_form_enable_recaptcha}} - Campo: Ativar reCAPTCHA -->
                <div class="cms_form-group">
                  <div class="form-check">
                    <input
                      class="form-check-input"
                      type="checkbox"
                      id="cms_enable_recaptcha"
                      name="enable_recaptcha"
                      data-cms-setting="enable_recaptcha"
                    />
                    <label class="form-check-label" for="cms_enable_recaptcha">
                      Ativar reCAPTCHA
                    </label>
                  </div>
                  <small class="cms_form-text">Proteger formulários com reCAPTCHA</small>
                </div>
                
                <!-- {{cms_form_recaptcha_key}} - Campo: reCAPTCHA Site Key -->
                <div class="cms_form-group">
                  <label for="cms_recaptcha_key" class="cms_form-label">reCAPTCHA Site Key</label>
                  <input
                    type="text"
                    class="cms_form-control"
                    id="cms_recaptcha_key"
                    name="recaptcha_key"
                    data-cms-setting="recaptcha_key"
                    placeholder="Sua chave do site"
                  />
                  <small class="cms_form-text">Chave pública do reCAPTCHA</small>
                </div>
                
                <!-- {{cms_form_recaptcha_secret}} - Campo: reCAPTCHA Secret Key -->
                <div class="cms_form-group">
                  <label for="cms_recaptcha_secret" class="cms_form-label">reCAPTCHA Secret Key</label>
                  <input
                    type="password"
                    class="cms_form-control"
                    id="cms_recaptcha_secret"
                    name="recaptcha_secret"
                    data-cms-setting="recaptcha_secret"
                    placeholder="Sua chave secreta"
                  />
                  <small class="cms_form-text">Chave secreta do reCAPTCHA</small>
                </div>
                
                <!-- {{cms_form_csp_header}} - Campo: Content Security Policy -->
                <div class="cms_form-group">
                  <label for="cms_csp_header" class="cms_form-label">Content Security Policy</label>
                  <textarea
                    class="cms_form-control cms_font-mono"
                    id="cms_csp_header"
                    name="csp_header"
                    data-cms-setting="csp_header"
                    rows="3"
                    placeholder="default-src 'self'; script-src 'self' 'unsafe-inline';"
                  ></textarea>
                  <small class="cms_form-text">Política de segurança de conteúdo</small>
                </div>
              </form>
            </div>
            <div class="cms_card-footer">
              <button class="cms_btn cms_btn-secondary" id="cms_settings_reset_btn">
                <i class="bi bi-arrow-counterclockwise"></i> Descartar
              </button>
              <button class="cms_btn cms_btn-primary" id="cms_settings_save_btn">
                <i class="bi bi-check-circle"></i> Salvar
              </button>
            </div>
          </div>
        </div>
        
        <!-- {{cms_content_extras}} - Conteúdo: Configurações Extras -->
        <div id="cms_content_extras" class="cms_tab-content">
          <div class="cms_card">
            <div class="cms_card-header">
              <h3 class="cms_card-title">
                <i class="bi bi-gear"></i> Configurações Extras
              </h3>
              <p class="cms_card-subtitle">Configurações avançadas e integrações</p>
            </div>
            <div class="cms_card-body">
              <form data-cms-form="extras">
                <!-- {{cms_form_maintenance_mode}} - Campo: Modo Manutenção -->
                <div class="cms_form-group">
                  <div class="form-check">
                    <input
                      class="form-check-input"
                      type="checkbox"
                      id="cms_maintenance_mode"
                      name="maintenance_mode"
                      data-cms-setting="maintenance_mode"
                    />
                    <label class="form-check-label" for="cms_maintenance_mode">
                      Modo Manutenção
                    </label>
                  </div>
                  <small class="cms_form-text">Ativar modo de manutenção do site</small>
                </div>
                
                <!-- {{cms_form_maintenance_message}} - Campo: Mensagem de Manutenção -->
                <div class="cms_form-group">
                  <label for="cms_maintenance_message" class="cms_form-label">Mensagem de Manutenção</label>
                  <textarea
                    class="cms_form-control"
                    id="cms_maintenance_message"
                    name="maintenance_message"
                    data-cms-setting="maintenance_message"
                    rows="3"
                    placeholder="Site em manutenção. Voltaremos em breve!"
                  ></textarea>
                  <small class="cms_form-text">Mensagem exibida quando o site está em manutenção</small>
                </div>
                
                <!-- {{cms_form_timezone}} - Campo: Fuso Horário -->
                <div class="cms_form-group">
                  <label for="cms_timezone" class="cms_form-label">Fuso Horário</label>
                  <select class="cms_form-control" id="cms_timezone" name="timezone" data-cms-setting="timezone">
                    <option value="America/Sao_Paulo">São Paulo (GMT-3)</option>
                    <option value="America/New_York">Nova York (GMT-5)</option>
                    <option value="Europe/London">Londres (GMT+0)</option>
                    <option value="Asia/Tokyo">Tóquio (GMT+9)</option>
                  </select>
                  <small class="cms_form-text">Fuso horário padrão do site</small>
                </div>
                
                <!-- {{cms_form_smtp_host}} - Campo: SMTP Host -->
                <div class="cms_form-group">
                  <label for="cms_smtp_host" class="cms_form-label">SMTP Host</label>
                  <input
                    type="text"
                    class="cms_form-control"
                    id="cms_smtp_host"
                    name="smtp_host"
                    data-cms-setting="smtp_host"
                    placeholder="smtp.gmail.com"
                  />
                  <small class="cms_form-text">Servidor SMTP para envio de emails</small>
                </div>
                
                <!-- {{cms_form_smtp_port}} - Campo: SMTP Port -->
                <div class="cms_form-group">
                  <label for="cms_smtp_port" class="cms_form-label">SMTP Port</label>
                  <input
                    type="number"
                    class="cms_form-control"
                    id="cms_smtp_port"
                    name="smtp_port"
                    data-cms-setting="smtp_port"
                    placeholder="587"
                    value="587"
                  />
                  <small class="cms_form-text">Porta SMTP (geralmente 587 ou 465)</small>
                </div>
                
                <!-- {{cms_form_webhook_url}} - Campo: Webhook URL -->
                <div class="cms_form-group">
                  <label for="cms_webhook_url" class="cms_form-label">Webhook URL</label>
                  <input
                    type="url"
                    class="cms_form-control"
                    id="cms_webhook_url"
                    name="webhook_url"
                    data-cms-setting="webhook_url"
                    placeholder="https://seu-site.com/webhook"
                  />
                  <small class="cms_form-text">URL para receber webhooks</small>
                </div>
                
                <!-- {{cms_form_export_import}} - Seção: Export/Import -->
                <div class="cms_form-group cms_mt-3 cms_pt-3" style="border-top: 1px solid #dee2e6;">
                  <h5>Export/Import de Configurações</h5>
                  <div class="cms_form-row">
                    <button type="button" class="cms_btn cms_btn-info" onclick="CMSSettings.exportSettings()">
                      <i class="bi bi-download"></i> Exportar
                    </button>
                    <button type="button" class="cms_btn cms_btn-info" onclick="document.getElementById('cms_import_file').click()">
                      <i class="bi bi-upload"></i> Importar
                    </button>
                    <input type="file" id="cms_import_file" accept=".json" style="display: none;" onchange="CMSSettings.importSettings(this)" />
                  </div>
                  <small class="cms_form-text">Faça backup ou restaure suas configurações</small>
                </div>
              </form>
            </div>
            <div class="cms_card-footer">
              <button class="cms_btn cms_btn-secondary" id="cms_settings_reset_btn">
                <i class="bi bi-arrow-counterclockwise"></i> Descartar
              </button>
              <button class="cms_btn cms_btn-primary" id="cms_settings_save_btn">
                <i class="bi bi-check-circle"></i> Salvar
              </button>
            </div>
          </div>
        </div>
      </main>
    </div>
    
    <!-- {{cms_bootstrap_js}} - Bootstrap 5 JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- {{cms_main_script}} - Script principal do CMS -->
    <script src="js/cms-main.js"></script>
    
    <!-- {{cms_settings_script}} - Script de gerenciamento de configurações -->
    <script src="js/cms-settings.js"></script>
    
    <!-- {{cms_custom_body_scripts}} - Scripts customizados no body -->
    {{cms_custom_body_scripts}}
  </body>
</html>
