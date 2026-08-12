/**
 * Menu Core - menu-core.js
 * Lógica principal do componente de menu responsivo
 * 
 * Funcionalidades:
 * - Colapsar/expandir menu em mobile
 * - Gerenciar subitens (expandir pai sem fechar menu)
 * - Carregar conteúdo em <main>
 * - Fechar menu ao clicar em item/subitem
 */

class MenuManager {
  constructor(config = {}) {
    // Configurações padrão
    this.config = {
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
      breakpoint: 992,
      ...config
    };

    // Elementos do DOM
    this.container = document.querySelector(this.config.containerSelector);
    this.overlay = document.querySelector(this.config.overlaySelector);
    this.toggle = document.querySelector(this.config.toggleSelector);
    this.closeBtn = document.querySelector(this.config.closeSelector);
    this.contentArea = document.querySelector(this.config.contentSelector);
    this.contentLoader = document.querySelector(this.config.contentLoaderSelector);

    // Estado
    this.isMobileOpen = false;
    this.isLoading = false;

    this.init();
  }

  /**
   * Inicializa o menu
   */
  init() {
    this.attachEventListeners();
    this.handleResize();
    window.addEventListener('resize', () => this.handleResize());
  }

  /**
   * Anexa event listeners
   */
  attachEventListeners() {
    // Toggle menu mobile
    if (this.toggle) {
      this.toggle.addEventListener('click', () => this.toggleMobileMenu());
    }

    // Fechar menu mobile
    if (this.closeBtn) {
      this.closeBtn.addEventListener('click', () => this.closeMobileMenu());
    }

    // Overlay para fechar menu
    if (this.overlay) {
      this.overlay.addEventListener('click', () => this.closeMobileMenu());
    }

    // Itens do menu principal
    document.querySelectorAll(this.config.itemSelector).forEach((item) => {
      item.addEventListener('click', (e) => this.handleMenuItemClick(e, item));
    });

    // Itens do submenu
    document.querySelectorAll(this.config.submenuItemSelector).forEach((item) => {
      item.addEventListener('click', (e) => this.handleSubmenuItemClick(e, item));
    });
  }

  /**
   * Alterna o menu mobile
   */
  toggleMobileMenu() {
    if (this.isMobileOpen) {
      this.closeMobileMenu();
    } else {
      this.openMobileMenu();
    }
  }

  /**
   * Abre o menu mobile
   */
  openMobileMenu() {
    if (this.isMobileOpen || this.isDesktop()) return;

    this.isMobileOpen = true;
    this.container.classList.add(this.config.mobileOpenClass);
    this.overlay.classList.add(this.config.overlayActiveClass);
    document.body.style.overflow = 'hidden';
  }

  /**
   * Fecha o menu mobile
   */
  closeMobileMenu() {
    if (!this.isMobileOpen) return;

    this.isMobileOpen = false;
    this.container.classList.remove(this.config.mobileOpenClass);
    this.overlay.classList.remove(this.config.overlayActiveClass);
    document.body.style.overflow = '';
  }

  /**
   * Verifica se está em desktop
   */
  isDesktop() {
    return window.innerWidth >= this.config.breakpoint;
  }

  /**
   * Manipula clique em item do menu principal
   */
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
    const target = item.getAttribute('target');

    if (href && href !== '#') {
      // Se é link externo ou nova aba, abre normalmente
      if (target === '_blank' || target === '_top') {
        window.open(href, target);
      } else {
        // Carrega conteúdo
        this.loadContent(href);
      }

      // Fecha menu mobile
      if (!this.isDesktop()) {
        this.closeMobileMenu();
      }

      // Atualiza item ativo
      this.setActiveItem(item);
    }
  }

  /**
   * Manipula clique em item do submenu
   */
  handleSubmenuItemClick(e, item) {
    e.preventDefault();
    const href = item.getAttribute('href');
    const target = item.getAttribute('target');

    if (href && href !== '#') {
      // Se é link externo ou nova aba, abre normalmente
      if (target === '_blank' || target === '_top') {
        window.open(href, target);
      } else {
        // Carrega conteúdo
        this.loadContent(href);
      }

      // Fecha menu mobile
      if (!this.isDesktop()) {
        this.closeMobileMenu();
      }

      // Atualiza item ativo
      this.setActiveItem(item);
    }
  }

  /**
   * Alterna submenu
   */
  toggleSubmenu(parentItem) {
    const isOpen = parentItem.classList.contains(this.config.openClass);

    if (isOpen) {
      parentItem.classList.remove(this.config.openClass);
    } else {
      parentItem.classList.add(this.config.openClass);
    }
  }

  /**
   * Carrega conteúdo
   */
  loadContent(url) {
    if (this.isLoading) return;

    this.isLoading = true;

    // Mostra loader
    if (this.contentLoader) {
      this.contentLoader.innerHTML = '<div class="text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Carregando...</span></div></div>';
    }

    fetch(url)
      .then((response) => {
        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.text();
      })
      .then((html) => {
        if (this.contentLoader) {
          this.contentLoader.innerHTML = html;
        }
        this.isLoading = false;
      })
      .catch((error) => {
        console.error('Erro ao carregar conteúdo:', error);
        if (this.contentLoader) {
          this.contentLoader.innerHTML = `<div class="alert alert-danger" role="alert">Erro ao carregar o conteúdo. Tente novamente.</div>`;
        }
        this.isLoading = false;
      });
  }

  /**
   * Define item ativo
   */
  setActiveItem(item) {
    // Remove classe ativa de todos os itens
    document.querySelectorAll(this.config.itemSelector).forEach((el) => {
      el.classList.remove(this.config.itemActiveClass);
    });
    document.querySelectorAll(this.config.submenuItemSelector).forEach((el) => {
      el.classList.remove(this.config.itemActiveClass);
    });

    // Adiciona classe ativa ao item clicado
    item.classList.add(this.config.itemActiveClass);
  }

  /**
   * Manipula redimensionamento da janela
   */
  handleResize() {
    if (this.isDesktop()) {
      this.closeMobileMenu();
    }
  }

  /**
   * Método público para carregar conteúdo externo
   */
  load(url) {
    this.loadContent(url);
  }

  /**
   * Método público para fechar menu
   */
  close() {
    this.closeMobileMenu();
  }

  /**
   * Método público para abrir menu
   */
  open() {
    this.openMobileMenu();
  }
}

// Inicializa o menu quando o DOM estiver pronto
document.addEventListener('DOMContentLoaded', () => {
  window.menuManager = new MenuManager();
});

// Exporta para uso em módulos
export { MenuManager };
