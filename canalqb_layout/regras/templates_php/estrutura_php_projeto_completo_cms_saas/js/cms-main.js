/**
 * CMS SaaS Admin Panel - Main JavaScript
 * Prefixo: cms-*
 * Descrição: Funcionalidades principais do painel administrativo
 * Autor: CMS Generator
 * Data: 2026-02-22
 */

// ============================================
// 1. CONFIGURAÇÃO GLOBAL DO CMS
// ============================================

const CMS = {
  // {{cms_base_url}} - URL base da aplicação (ex: /cms ou /admin)
  baseUrl: '/cms',
  
  // {{cms_api_endpoint}} - Endpoint da API (ex: /api/v1)
  apiEndpoint: '/api/v1',
  
  // {{cms_version}} - Versão do CMS
  version: '1.0.0',
  
  // {{cms_debug_mode}} - Modo debug (true/false)
  debugMode: false,
  
  // {{cms_theme}} - Tema atual (light/dark)
  theme: localStorage.getItem('cms_theme') || 'light',
  
  // {{cms_language}} - Idioma da interface (pt-BR/en-US)
  language: localStorage.getItem('cms_language') || 'pt-BR',
  
  // {{cms_user_data}} - Dados do usuário logado
  userData: null,
  
  // {{cms_settings}} - Configurações globais
  settings: {},
  
  // {{cms_notifications}} - Fila de notificações
  notifications: [],
};

// ============================================
// 2. INICIALIZAÇÃO DO CMS
// ============================================

/**
 * Inicializa o CMS ao carregar a página
 * {{cms_init_function}} - Função de inicialização
 */
document.addEventListener('DOMContentLoaded', function () {
  console.log('[CMS] Inicializando CMS v' + CMS.version);
  
  // Aplicar tema salvo
  CMS.applyTheme(CMS.theme);
  
  // Inicializar componentes
  CMS.initializeComponents();
  
  // Carregar configurações
  CMS.loadSettings();
  
  // Configurar event listeners
  CMS.setupEventListeners();
  
  // Verificar autenticação
  CMS.checkAuthentication();
  
  console.log('[CMS] Inicialização concluída');
});

// ============================================
// 3. GERENCIAMENTO DE TEMAS
// ============================================

/**
 * Aplica o tema selecionado
 * {{cms_theme_name}} - Nome do tema (light/dark)
 */
CMS.applyTheme = function (themeName) {
  const body = document.body;
  
  if (themeName === 'dark') {
    body.classList.add('cms-dark-mode');
  } else {
    body.classList.remove('cms-dark-mode');
  }
  
  CMS.theme = themeName;
  localStorage.setItem('cms_theme', themeName);
  
  if (CMS.debugMode) {
    console.log('[CMS] Tema aplicado: ' + themeName);
  }
};

/**
 * Alterna entre temas claro e escuro
 * {{cms_toggle_theme}} - Função para alternar tema
 */
CMS.toggleTheme = function () {
  const newTheme = CMS.theme === 'light' ? 'dark' : 'light';
  CMS.applyTheme(newTheme);
};

// ============================================
// 4. GERENCIAMENTO DE COMPONENTES
// ============================================

/**
 * Inicializa todos os componentes da página
 * {{cms_initialize_components}} - Função de inicialização de componentes
 */
CMS.initializeComponents = function () {
  // Inicializar abas (tabs)
  CMS.initializeTabs();
  
  // Inicializar modais
  CMS.initializeModals();
  
  // Inicializar tooltips
  CMS.initializeTooltips();
  
  // Inicializar formulários
  CMS.initializeForms();
  
  // Inicializar upload de arquivos
  CMS.initializeFileUploads();
};

/**
 * Inicializa as abas (tabs)
 * {{cms_tab_id}} - ID da aba
 * {{cms_tab_content_id}} - ID do conteúdo da aba
 */
CMS.initializeTabs = function () {
  const tabButtons = document.querySelectorAll('[data-cms-tab]');
  
  tabButtons.forEach(function (button) {
    button.addEventListener('click', function () {
      const tabName = this.getAttribute('data-cms-tab');
      CMS.switchTab(tabName);
    });
  });
};

/**
 * Alterna para uma aba específica
 * {{cms_tab_name}} - Nome da aba
 */
CMS.switchTab = function (tabName) {
  // Remover classe active de todas as abas
  const allTabs = document.querySelectorAll('.cms_tab-button');
  allTabs.forEach(function (tab) {
    tab.classList.remove('active');
  });
  
  // Remover classe active de todo o conteúdo
  const allContents = document.querySelectorAll('.cms_tab-content');
  allContents.forEach(function (content) {
    content.classList.remove('active');
  });
  
  // Adicionar classe active à aba clicada
  const activeButton = document.querySelector('[data-cms-tab="' + tabName + '"]');
  if (activeButton) {
    activeButton.classList.add('active');
  }
  
  // Adicionar classe active ao conteúdo
  const activeContent = document.getElementById('cms_content_' + tabName);
  if (activeContent) {
    activeContent.classList.add('active');
  }
  
  if (CMS.debugMode) {
    console.log('[CMS] Aba alterada para: ' + tabName);
  }
};

/**
 * Inicializa os modais
 * {{cms_modal_id}} - ID do modal
 */
CMS.initializeModals = function () {
  const closeButtons = document.querySelectorAll('[data-cms-modal-close]');
  
  closeButtons.forEach(function (button) {
    button.addEventListener('click', function () {
      const modalId = this.getAttribute('data-cms-modal-close');
      CMS.closeModal(modalId);
    });
  });
};

/**
 * Abre um modal
 * {{cms_modal_id}} - ID do modal
 */
CMS.openModal = function (modalId) {
  const modal = document.getElementById(modalId);
  
  if (modal) {
    modal.classList.add('show');
    document.body.style.overflow = 'hidden';
    
    if (CMS.debugMode) {
      console.log('[CMS] Modal aberto: ' + modalId);
    }
  }
};

/**
 * Fecha um modal
 * {{cms_modal_id}} - ID do modal
 */
CMS.closeModal = function (modalId) {
  const modal = document.getElementById(modalId);
  
  if (modal) {
    modal.classList.remove('show');
    document.body.style.overflow = 'auto';
    
    if (CMS.debugMode) {
      console.log('[CMS] Modal fechado: ' + modalId);
    }
  }
};

/**
 * Inicializa tooltips
 */
CMS.initializeTooltips = function () {
  const tooltips = document.querySelectorAll('[data-cms-tooltip]');
  
  tooltips.forEach(function (element) {
    element.addEventListener('mouseenter', function () {
      const tooltipText = this.getAttribute('data-cms-tooltip');
      CMS.showTooltip(this, tooltipText);
    });
    
    element.addEventListener('mouseleave', function () {
      CMS.hideTooltip(this);
    });
  });
};

/**
 * Mostra um tooltip
 * {{cms_element}} - Elemento HTML
 * {{cms_tooltip_text}} - Texto do tooltip
 */
CMS.showTooltip = function (element, tooltipText) {
  let tooltip = element.querySelector('.cms_tooltip-text');
  
  if (!tooltip) {
    tooltip = document.createElement('div');
    tooltip.className = 'cms_tooltip-text';
    tooltip.textContent = tooltipText;
    element.appendChild(tooltip);
  }
  
  tooltip.style.display = 'block';
};

/**
 * Oculta um tooltip
 * {{cms_element}} - Elemento HTML
 */
CMS.hideTooltip = function (element) {
  const tooltip = element.querySelector('.cms_tooltip-text');
  
  if (tooltip) {
    tooltip.style.display = 'none';
  }
};

/**
 * Inicializa formulários
 */
CMS.initializeForms = function () {
  const forms = document.querySelectorAll('[data-cms-form]');
  
  forms.forEach(function (form) {
    form.addEventListener('submit', function (e) {
      e.preventDefault();
      CMS.submitForm(this);
    });
  });
};

/**
 * Envia um formulário via AJAX
 * {{cms_form_element}} - Elemento do formulário
 */
CMS.submitForm = function (formElement) {
  const formData = new FormData(formElement);
  const action = formElement.getAttribute('action');
  const method = formElement.getAttribute('method') || 'POST';
  
  // Mostrar loading
  CMS.showLoading();
  
  fetch(action, {
    method: method,
    body: formData,
  })
    .then(function (response) {
      return response.json();
    })
    .then(function (data) {
      CMS.hideLoading();
      
      if (data.success) {
        CMS.showNotification('Sucesso!', data.message, 'success');
        
        // Recarregar página após 2 segundos
        setTimeout(function () {
          location.reload();
        }, 2000);
      } else {
        CMS.showNotification('Erro!', data.message, 'danger');
      }
    })
    .catch(function (error) {
      CMS.hideLoading();
      CMS.showNotification('Erro!', 'Erro ao enviar formulário', 'danger');
      console.error('[CMS] Erro:', error);
    });
};

/**
 * Inicializa upload de arquivos
 * {{cms_file_upload_id}} - ID do elemento de upload
 */
CMS.initializeFileUploads = function () {
  const fileUploads = document.querySelectorAll('[data-cms-file-upload]');
  
  fileUploads.forEach(function (element) {
    // Drag and drop
    element.addEventListener('dragover', function (e) {
      e.preventDefault();
      this.classList.add('drag-over');
    });
    
    element.addEventListener('dragleave', function () {
      this.classList.remove('drag-over');
    });
    
    element.addEventListener('drop', function (e) {
      e.preventDefault();
      this.classList.remove('drag-over');
      
      const files = e.dataTransfer.files;
      CMS.handleFileUpload(this, files);
    });
    
    // Click to upload
    element.addEventListener('click', function () {
      const input = document.createElement('input');
      input.type = 'file';
      input.multiple = true;
      
      input.addEventListener('change', function () {
        CMS.handleFileUpload(element, this.files);
      });
      
      input.click();
    });
  });
};

/**
 * Manipula upload de arquivos
 * {{cms_element}} - Elemento de upload
 * {{cms_files}} - Lista de arquivos
 */
CMS.handleFileUpload = function (element, files) {
  const uploadId = element.getAttribute('data-cms-file-upload');
  
  if (CMS.debugMode) {
    console.log('[CMS] Arquivos selecionados:', files.length);
  }
  
  // Aqui você pode adicionar lógica para enviar os arquivos
  // por exemplo, usando fetch ou XMLHttpRequest
};

// ============================================
// 5. GERENCIAMENTO DE NOTIFICAÇÕES
// ============================================

/**
 * Mostra uma notificação
 * {{cms_notification_title}} - Título da notificação
 * {{cms_notification_message}} - Mensagem da notificação
 * {{cms_notification_type}} - Tipo (success/danger/warning/info)
 */
CMS.showNotification = function (title, message, type) {
  type = type || 'info';
  
  const notification = document.createElement('div');
  notification.className = 'cms_alert cms_alert-' + type;
  notification.innerHTML =
    '<strong>' +
    title +
    '</strong> ' +
    message +
    '<button class="cms_alert-close" onclick="this.parentElement.style.display=\'none\';">&times;</button>';
  
  const container = document.getElementById('cms_notifications_container');
  
  if (container) {
    container.appendChild(notification);
    
    // Auto-remover após 5 segundos
    setTimeout(function () {
      notification.style.display = 'none';
    }, 5000);
  }
};

// ============================================
// 6. GERENCIAMENTO DE CARREGAMENTO
// ============================================

/**
 * Mostra indicador de carregamento
 */
CMS.showLoading = function () {
  let loader = document.getElementById('cms_loader');
  
  if (!loader) {
    loader = document.createElement('div');
    loader.id = 'cms_loader';
    loader.className = 'cms_loader';
    loader.innerHTML =
      '<div class="cms_spinner"><div class="cms_spinner-border cms_animate-spin"></div></div>';
    document.body.appendChild(loader);
  }
  
  loader.style.display = 'flex';
};

/**
 * Oculta indicador de carregamento
 */
CMS.hideLoading = function () {
  const loader = document.getElementById('cms_loader');
  
  if (loader) {
    loader.style.display = 'none';
  }
};

// ============================================
// 7. CONFIGURAÇÕES E DADOS
// ============================================

/**
 * Carrega as configurações do CMS
 * {{cms_settings_endpoint}} - Endpoint para carregar configurações
 */
CMS.loadSettings = function () {
  fetch(CMS.apiEndpoint + '/settings')
    .then(function (response) {
      return response.json();
    })
    .then(function (data) {
      CMS.settings = data;
      
      if (CMS.debugMode) {
        console.log('[CMS] Configurações carregadas:', CMS.settings);
      }
    })
    .catch(function (error) {
      console.error('[CMS] Erro ao carregar configurações:', error);
    });
};

/**
 * Verifica autenticação do usuário
 * {{cms_auth_endpoint}} - Endpoint para verificar autenticação
 */
CMS.checkAuthentication = function () {
  fetch(CMS.apiEndpoint + '/auth/check')
    .then(function (response) {
      return response.json();
    })
    .then(function (data) {
      if (data.authenticated) {
        CMS.userData = data.user;
        
        if (CMS.debugMode) {
          console.log('[CMS] Usuário autenticado:', CMS.userData);
        }
      } else {
        // Redirecionar para login
        window.location.href = CMS.baseUrl + '/login';
      }
    })
    .catch(function (error) {
      console.error('[CMS] Erro ao verificar autenticação:', error);
    });
};

// ============================================
// 8. CONFIGURAÇÃO DE EVENT LISTENERS
// ============================================

/**
 * Configura event listeners globais
 */
CMS.setupEventListeners = function () {
  // Botão de alternância de tema
  const themeToggle = document.getElementById('cms_theme_toggle');
  if (themeToggle) {
    themeToggle.addEventListener('click', function () {
      CMS.toggleTheme();
    });
  }
  
  // Botão de logout
  const logoutBtn = document.getElementById('cms_logout_btn');
  if (logoutBtn) {
    logoutBtn.addEventListener('click', function () {
      CMS.logout();
    });
  }
  
  // Fechar modais ao clicar fora
  const modals = document.querySelectorAll('.cms_modal');
  modals.forEach(function (modal) {
    modal.addEventListener('click', function (e) {
      if (e.target === this) {
        this.classList.remove('show');
        document.body.style.overflow = 'auto';
      }
    });
  });
};

// ============================================
// 9. AUTENTICAÇÃO
// ============================================

/**
 * Realiza logout do usuário
 * {{cms_logout_endpoint}} - Endpoint para logout
 */
CMS.logout = function () {
  if (confirm('Tem certeza que deseja sair?')) {
    fetch(CMS.apiEndpoint + '/auth/logout', {
      method: 'POST',
    })
      .then(function () {
        window.location.href = CMS.baseUrl + '/login';
      })
      .catch(function (error) {
        console.error('[CMS] Erro ao fazer logout:', error);
      });
  }
};

// ============================================
// 10. UTILITÁRIOS
// ============================================

/**
 * Formata uma data
 * {{cms_date}} - Data a formatar
 * {{cms_format}} - Formato (pt-BR/en-US)
 */
CMS.formatDate = function (date, format) {
  format = format || 'pt-BR';
  return new Date(date).toLocaleDateString(format);
};

/**
 * Formata uma moeda
 * {{cms_value}} - Valor a formatar
 * {{cms_currency}} - Moeda (BRL/USD)
 */
CMS.formatCurrency = function (value, currency) {
  currency = currency || 'BRL';
  return new Intl.NumberFormat('pt-BR', {
    style: 'currency',
    currency: currency,
  }).format(value);
};

/**
 * Valida um email
 * {{cms_email}} - Email a validar
 */
CMS.validateEmail = function (email) {
  const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return re.test(email);
};

/**
 * Copia texto para a área de transferência
 * {{cms_text}} - Texto a copiar
 */
CMS.copyToClipboard = function (text) {
  navigator.clipboard.writeText(text).then(function () {
    CMS.showNotification('Sucesso!', 'Texto copiado para a área de transferência', 'success');
  });
};

/**
 * Log de debug
 * {{cms_message}} - Mensagem a logar
 */
CMS.log = function (message) {
  if (CMS.debugMode) {
    console.log('[CMS] ' + message);
  }
};

// ============================================
// 11. EXPORTAR OBJETO CMS
// ============================================

// Tornar CMS disponível globalmente
window.CMS = CMS;
