<?php
/**
 * Template Combobox com Tokenização e Modal
 * 
 * Componente avançado de combobox com:
 * - Tokenização de itens selecionados
 * - Modal com fundo semitransparente e efeito blur
 * - Consistência visual com Bootstrap Morph
 * - Navegação por teclado e mouse
 * - Suporte a busca em tempo real
 * 
 * Variáveis de Template (use {{variavel}} em prompts de LLM):
 * - {{combobox_title}}: Título da página
 * - {{combobox_label}}: Rótulo do campo
 * - {{combobox_placeholder}}: Texto placeholder do input
 * - {{combobox_data_source}}: URL ou array de dados
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
  <title>{{combobox_title}}</title>

  <!-- Bootstrap 5 - Bootswatch Morph Theme -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootswatch/5.2.0/morph/bootstrap.min.css" rel="stylesheet">

  <!-- CSS para Combobox Tokenizado com Modal -->
  <style>
    /* ============================================
       ESTILOS BASE - Consistência com Bootstrap
       ============================================ */
    
    :root {
      --combobox_primary_color: #0d6efd;
      --combobox_primary_hover: #0b5ed7;
      --combobox_border_color: #ced4da;
      --combobox_text_color: #212529;
      --combobox_light_bg: #f8f9fa;
      --combobox_shadow_sm: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
      --combobox_shadow_md: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
      --combobox_shadow_lg: 0 4px 12px rgba(0, 0, 0, 0.1);
      --combobox_blur_intensity: 8px;
      --combobox_overlay_opacity: 0.5;
    }

    /* ============================================
       MODAL OVERLAY - Fundo Semitransparente
       ============================================ */
    
    .combobox_modal_overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, var(--combobox_overlay_opacity));
      backdrop-filter: blur(var(--combobox_blur_intensity));
      -webkit-backdrop-filter: blur(var(--combobox_blur_intensity));
      display: none;
      z-index: 1040;
      animation: combobox_fadeIn 0.2s ease-in-out;
    }

    .combobox_modal_overlay.combobox_active {
      display: block;
    }

    @keyframes combobox_fadeIn {
      from {
        opacity: 0;
      }
      to {
        opacity: 1;
      }
    }

    /* ============================================
       WRAPPER DO TOKEN - Container Principal
       ============================================ */
    
    .combobox_token_wrapper {
      display: flex;
      flex-wrap: wrap;
      align-items: center;
      border: 1px solid var(--combobox_border_color);
      border-radius: 0.375rem;
      padding: 0.375rem 0.625rem;
      cursor: text;
      position: relative;
      min-height: 44px;
      background-color: #fff;
      transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
      box-shadow: var(--combobox_shadow_sm);
    }

    .combobox_token_wrapper:focus-within {
      border-color: var(--combobox_primary_color);
      box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    }

    .combobox_token_wrapper.combobox_modal_active {
      border-color: var(--combobox_primary_color);
    }

    /* ============================================
       INPUT DO COMBOBOX
       ============================================ */
    
    .combobox_token_wrapper input {
      border: none;
      flex: 1;
      min-width: 120px;
      outline: none;
      font-size: 1rem;
      padding: 0.375rem 0.5rem;
      background-color: transparent;
      color: var(--combobox_text_color);
      font-family: inherit;
    }

    .combobox_token_wrapper input::placeholder {
      color: #6c757d;
    }

    /* ============================================
       TOKENS - Itens Selecionados
       ============================================ */
    
    .combobox_token {
      background: linear-gradient(135deg, var(--combobox_primary_color), var(--combobox_primary_hover));
      color: #fff;
      padding: 0.375rem 0.75rem;
      margin: 0.25rem;
      border-radius: 999px;
      display: inline-flex;
      align-items: center;
      font-size: 0.875rem;
      font-weight: 500;
      box-shadow: var(--combobox_shadow_sm);
      animation: combobox_slideIn 0.2s ease-out;
      gap: 0.5rem;
    }

    @keyframes combobox_slideIn {
      from {
        opacity: 0;
        transform: scale(0.8);
      }
      to {
        opacity: 1;
        transform: scale(1);
      }
    }

    .combobox_token .combobox_token_label {
      max-width: 200px;
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
    }

    .combobox_token .combobox_token_remove {
      cursor: pointer;
      font-weight: bold;
      font-size: 1.25rem;
      line-height: 1;
      opacity: 0.8;
      transition: opacity 0.15s ease-in-out;
      display: flex;
      align-items: center;
      justify-content: center;
      width: 20px;
      height: 20px;
    }

    .combobox_token .combobox_token_remove:hover {
      opacity: 1;
    }

    /* ============================================
       DROPDOWN MENU - Autocomplete
       ============================================ */
    
    .combobox_autocomplete_menu {
      position: absolute;
      top: calc(100% + 0.5rem);
      left: 0;
      right: 0;
      width: 100%;
      max-height: 320px;
      overflow-y: auto;
      display: none;
      border: 1px solid var(--combobox_border_color);
      border-radius: 0.375rem;
      box-shadow: var(--combobox_shadow_lg);
      z-index: 1050;
      background-color: #fff;
      list-style: none;
      padding: 0;
      margin: 0;
      animation: combobox_slideDown 0.2s ease-out;
    }

    @keyframes combobox_slideDown {
      from {
        opacity: 0;
        transform: translateY(-10px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .combobox_autocomplete_menu.combobox_active {
      display: block;
    }

    .combobox_autocomplete_menu li {
      list-style: none;
      padding: 0;
      margin: 0;
    }

    .combobox_autocomplete_menu li a {
      display: block;
      padding: 0.625rem 1rem;
      cursor: pointer;
      text-decoration: none;
      color: var(--combobox_text_color);
      transition: background-color 0.15s ease-in-out, color 0.15s ease-in-out;
      border-left: 3px solid transparent;
    }

    .combobox_autocomplete_menu li a:hover {
      background-color: var(--combobox_light_bg);
      border-left-color: var(--combobox_primary_color);
    }

    .combobox_autocomplete_menu li a.combobox_active {
      background-color: var(--combobox_primary_color);
      color: #fff;
      border-left-color: var(--combobox_primary_hover);
    }

    .combobox_autocomplete_menu mark {
      background-color: rgba(13, 110, 253, 0.2);
      color: inherit;
      font-weight: 600;
      padding: 0 2px;
      border-radius: 2px;
    }

    /* ============================================
       SCROLLBAR CUSTOMIZADO
       ============================================ */
    
    .combobox_autocomplete_menu::-webkit-scrollbar {
      width: 6px;
    }

    .combobox_autocomplete_menu::-webkit-scrollbar-track {
      background: transparent;
    }

    .combobox_autocomplete_menu::-webkit-scrollbar-thumb {
      background: #ccc;
      border-radius: 3px;
    }

    .combobox_autocomplete_menu::-webkit-scrollbar-thumb:hover {
      background: #999;
    }

    /* ============================================
       ESTADOS VAZIOS E CARREGAMENTO
       ============================================ */
    
    .combobox_empty {
      padding: 1rem;
      text-align: center;
      color: #6c757d;
      font-size: 0.875rem;
    }

    .combobox_loading {
      padding: 1rem;
      text-align: center;
      color: #6c757d;
    }

    .combobox_loading::after {
      content: '';
      display: inline-block;
      width: 12px;
      height: 12px;
      margin-left: 0.5rem;
      border: 2px solid #f3f3f3;
      border-top: 2px solid var(--combobox_primary_color);
      border-radius: 50%;
      animation: combobox_spin 0.6s linear infinite;
    }

    @keyframes combobox_spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }

    /* ============================================
       RESPONSIVIDADE
       ============================================ */
    
    @media (max-width: 576px) {
      .combobox_token_wrapper {
        min-height: 40px;
        padding: 0.25rem 0.5rem;
      }

      .combobox_token {
        padding: 0.25rem 0.5rem;
        font-size: 0.8125rem;
      }

      .combobox_autocomplete_menu {
        max-height: 240px;
      }

      .combobox_autocomplete_menu li a {
        padding: 0.5rem 0.75rem;
      }
    }

    /* ============================================
       ACESSIBILIDADE
       ============================================ */
    
    .combobox_token_wrapper:focus-visible {
      outline: 2px solid var(--combobox_primary_color);
      outline-offset: 2px;
    }

    .combobox_autocomplete_menu li a:focus-visible {
      outline: 2px solid var(--combobox_primary_color);
      outline-offset: -2px;
    }
  </style>
</head>

<body>
  <div class="container mt-5 mb-5">
    <div class="row">
      <div class="col-md-8 mx-auto">
        <h1 class="mb-4">{{combobox_title}}</h1>

        <!-- Label -->
        <label class="form-label fw-bold" for="combobox_input">
          {{combobox_label}}
        </label>

        <!-- Combobox Tokenizada -->
        <div class="combobox_token_wrapper position-relative" id="combobox_wrapper" role="combobox" aria-expanded="false">
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
          <ul class="combobox_autocomplete_menu" id="combobox_menu" role="listbox"></ul>
        </div>

        <!-- Modal Overlay (Fundo Semitransparente) -->
        <div class="combobox_modal_overlay" id="combobox_modal_overlay"></div>

        <!-- Informações dos Tokens Selecionados -->
        <div class="mt-3">
          <small class="text-muted">
            <strong>Tokens selecionados:</strong> <span id="combobox_token_count">0</span>
          </small>
        </div>

        <!-- Hidden Input para Submissão de Formulário -->
        <input type="hidden" id="combobox_selected_tokens" name="combobox_selected_items" value="">
      </div>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Script de Inicialização -->
  <script type="module">
    import { initCombobox } from '{{combobox_js_path}}';

    document.addEventListener('DOMContentLoaded', () => {
      initCombobox({
        inputSelector: '#combobox_input',
        menuSelector: '#combobox_menu',
        wrapperSelector: '#combobox_wrapper',
        overlaySelector: '#combobox_modal_overlay',
        tokenCountSelector: '#combobox_token_count',
        selectedTokensSelector: '#combobox_selected_tokens',
        placeholder: '{{combobox_placeholder}}',
        dataSource: '{{combobox_data_source}}'
      });
    });
  </script>
</body>
</html>
