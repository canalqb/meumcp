/**
 * CMS SaaS Admin Panel - Settings Management
 * Prefixo: cms-*
 * Descrição: Gerenciamento de configurações do CMS
 * Autor: CMS Generator
 * Data: 2026-02-22
 */

// ============================================
// 1. OBJETO SETTINGS MANAGER
// ============================================

const CMSSettings = {
  // {{cms_settings_data}} - Dados das configurações
  data: {},
  
  // {{cms_settings_modified}} - Flag de modificação
  modified: false,
  
  // {{cms_settings_endpoint}} - Endpoint da API
  endpoint: '/api/v1/settings',
  
  // {{cms_settings_categories}} - Categorias de configurações
  categories: [
    'identity',
    'seo',
    'ads',
    'layout',
    'images',
    'performance',
    'security',
    'extras',
  ],
};

// ============================================
// 2. INICIALIZAÇÃO
// ============================================

/**
 * Inicializa o gerenciador de configurações
 */
CMSSettings.init = function () {
  console.log('[CMSSettings] Inicializando gerenciador de configurações');
  
  // Carregar configurações
  CMSSettings.loadSettings();
  
  // Configurar event listeners
  CMSSettings.setupEventListeners();
};

/**
 * Configura event listeners para os formulários de configurações
 */
CMSSettings.setupEventListeners = function () {
  // Botão de salvar
  const saveBtn = document.getElementById('cms_settings_save_btn');
  if (saveBtn) {
    saveBtn.addEventListener('click', function () {
      CMSSettings.saveSettings();
    });
  }
  
  // Botão de resetar
  const resetBtn = document.getElementById('cms_settings_reset_btn');
  if (resetBtn) {
    resetBtn.addEventListener('click', function () {
      CMSSettings.resetSettings();
    });
  }
  
  // Monitorar mudanças nos inputs
  const inputs = document.querySelectorAll('[data-cms-setting]');
  inputs.forEach(function (input) {
    input.addEventListener('change', function () {
      CMSSettings.modified = true;
      CMSSettings.updateModifiedIndicator();
    });
  });
};

// ============================================
// 3. CARREGAMENTO DE CONFIGURAÇÕES
// ============================================

/**
 * Carrega as configurações do servidor
 * {{cms_settings_endpoint}} - Endpoint para carregar configurações
 */
CMSSettings.loadSettings = function () {
  CMS.showLoading();
  
  fetch(CMSSettings.endpoint)
    .then(function (response) {
      return response.json();
    })
    .then(function (data) {
      CMSSettings.data = data;
      CMSSettings.populateForm();
      CMS.hideLoading();
      
      console.log('[CMSSettings] Configurações carregadas com sucesso');
    })
    .catch(function (error) {
      CMS.hideLoading();
      CMS.showNotification('Erro!', 'Erro ao carregar configurações', 'danger');
      console.error('[CMSSettings] Erro:', error);
    });
};

/**
 * Popula o formulário com as configurações carregadas
 */
CMSSettings.populateForm = function () {
  const inputs = document.querySelectorAll('[data-cms-setting]');
  
  inputs.forEach(function (input) {
    const settingKey = input.getAttribute('data-cms-setting');
    const value = CMSSettings.data[settingKey];
    
    if (value !== undefined) {
      if (input.type === 'checkbox') {
        input.checked = value === 1 || value === true || value === 'true';
      } else if (input.type === 'radio') {
        if (input.value === value) {
          input.checked = true;
        }
      } else {
        input.value = value;
      }
    }
  });
  
  CMSSettings.modified = false;
  CMSSettings.updateModifiedIndicator();
};

// ============================================
// 4. SALVAMENTO DE CONFIGURAÇÕES
// ============================================

/**
 * Salva as configurações no servidor
 * {{cms_settings_data}} - Dados das configurações
 */
CMSSettings.saveSettings = function () {
  // Validar formulário
  if (!CMSSettings.validateForm()) {
    CMS.showNotification('Erro!', 'Por favor, preencha todos os campos obrigatórios', 'warning');
    return;
  }
  
  // Coletar dados do formulário
  const formData = CMSSettings.collectFormData();
  
  CMS.showLoading();
  
  fetch(CMSSettings.endpoint, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify(formData),
  })
    .then(function (response) {
      return response.json();
    })
    .then(function (data) {
      CMS.hideLoading();
      
      if (data.success) {
        CMSSettings.data = data.settings;
        CMSSettings.modified = false;
        CMSSettings.updateModifiedIndicator();
        
        CMS.showNotification('Sucesso!', 'Configurações salvas com sucesso', 'success');
      } else {
        CMS.showNotification('Erro!', data.message || 'Erro ao salvar configurações', 'danger');
      }
    })
    .catch(function (error) {
      CMS.hideLoading();
      CMS.showNotification('Erro!', 'Erro ao salvar configurações', 'danger');
      console.error('[CMSSettings] Erro:', error);
    });
};

/**
 * Coleta os dados do formulário
 * {{cms_form_data}} - Dados do formulário
 */
CMSSettings.collectFormData = function () {
  const formData = {};
  const inputs = document.querySelectorAll('[data-cms-setting]');
  
  inputs.forEach(function (input) {
    const settingKey = input.getAttribute('data-cms-setting');
    let value;
    
    if (input.type === 'checkbox') {
      value = input.checked ? 1 : 0;
    } else if (input.type === 'radio') {
      if (input.checked) {
        value = input.value;
      } else {
        return;
      }
    } else {
      value = input.value;
    }
    
    formData[settingKey] = value;
  });
  
  return formData;
};

// ============================================
// 5. VALIDAÇÃO DE FORMULÁRIO
// ============================================

/**
 * Valida o formulário de configurações
 * {{cms_form_valid}} - Resultado da validação
 */
CMSSettings.validateForm = function () {
  const requiredFields = document.querySelectorAll('[data-cms-setting][required]');
  let isValid = true;
  
  requiredFields.forEach(function (field) {
    if (!field.value || field.value.trim() === '') {
      isValid = false;
      field.classList.add('is-invalid');
    } else {
      field.classList.remove('is-invalid');
    }
  });
  
  return isValid;
};

// ============================================
// 6. RESET DE CONFIGURAÇÕES
// ============================================

/**
 * Reseta as configurações para os valores originais
 */
CMSSettings.resetSettings = function () {
  if (confirm('Tem certeza que deseja descartar as alterações?')) {
    CMSSettings.populateForm();
  }
};

// ============================================
// 7. INDICADOR DE MODIFICAÇÃO
// ============================================

/**
 * Atualiza o indicador de modificação
 */
CMSSettings.updateModifiedIndicator = function () {
  const indicator = document.getElementById('cms_settings_modified_indicator');
  
  if (indicator) {
    if (CMSSettings.modified) {
      indicator.style.display = 'block';
    } else {
      indicator.style.display = 'none';
    }
  }
};

// ============================================
// 8. GERENCIAMENTO DE IDENTIDADE DO SITE
// ============================================

/**
 * Atualiza a identidade do site
 * {{cms_site_name}} - Nome do site
 * {{cms_site_slogan}} - Slogan do site
 */
CMSSettings.updateIdentity = function () {
  const siteName = document.getElementById('cms_site_name').value;
  const siteSlogan = document.getElementById('cms_site_slogan').value;
  
  // Atualizar no documento
  const titleElements = document.querySelectorAll('[data-cms-site-name]');
  titleElements.forEach(function (el) {
    el.textContent = siteName;
  });
  
  const sloganElements = document.querySelectorAll('[data-cms-site-slogan]');
  sloganElements.forEach(function (el) {
    el.textContent = siteSlogan;
  });
};

// ============================================
// 9. GERENCIAMENTO DE CORES E TEMA
// ============================================

/**
 * Atualiza as cores do tema
 * {{cms_primary_color}} - Cor primária
 * {{cms_secondary_color}} - Cor secundária
 */
CMSSettings.updateColors = function () {
  const primaryColor = document.getElementById('cms_primary_color').value;
  const secondaryColor = document.getElementById('cms_secondary_color').value;
  
  // Atualizar variáveis CSS
  document.documentElement.style.setProperty('--cms-primary-color', primaryColor);
  document.documentElement.style.setProperty('--cms-secondary-color', secondaryColor);
};

/**
 * Atualiza a fonte do tema
 * {{cms_font_family}} - Família de fonte
 */
CMSSettings.updateFont = function () {
  const fontFamily = document.getElementById('cms_font_family').value;
  
  // Atualizar variável CSS
  document.documentElement.style.setProperty('--cms-font-family-base', fontFamily);
};

// ============================================
// 10. GERENCIAMENTO DE IMAGENS
// ============================================

/**
 * Preview de imagem
 * {{cms_image_input_id}} - ID do input de imagem
 * {{cms_image_preview_id}} - ID do elemento de preview
 */
CMSSettings.previewImage = function (inputId, previewId) {
  const input = document.getElementById(inputId);
  const preview = document.getElementById(previewId);
  
  if (input.files && input.files[0]) {
    const reader = new FileReader();
    
    reader.onload = function (e) {
      preview.src = e.target.result;
      preview.style.display = 'block';
    };
    
    reader.readAsDataURL(input.files[0]);
  }
};

/**
 * Remove uma imagem
 * {{cms_image_preview_id}} - ID do elemento de preview
 * {{cms_image_input_id}} - ID do input de imagem
 */
CMSSettings.removeImage = function (previewId, inputId) {
  const preview = document.getElementById(previewId);
  const input = document.getElementById(inputId);
  
  preview.src = '';
  preview.style.display = 'none';
  input.value = '';
};

// ============================================
// 11. GERENCIAMENTO DE SEO
// ============================================

/**
 * Atualiza preview de meta description
 * {{cms_meta_description}} - Meta description
 */
CMSSettings.updateMetaPreview = function () {
  const metaDescription = document.getElementById('cms_meta_description').value;
  const preview = document.getElementById('cms_meta_preview');
  
  if (preview) {
    preview.textContent = metaDescription || 'Descrição padrão do site...';
  }
};

/**
 * Gera slug automático a partir do título
 * {{cms_title}} - Título
 * {{cms_slug}} - Slug gerado
 */
CMSSettings.generateSlug = function (titleInput, slugInput) {
  const title = document.getElementById(titleInput).value;
  const slug = title
    .toLowerCase()
    .replace(/[^\w\s-]/g, '')
    .replace(/\s+/g, '-')
    .replace(/-+/g, '-')
    .trim();
  
  document.getElementById(slugInput).value = slug;
};

// ============================================
// 12. GERENCIAMENTO DE PERFORMANCE
// ============================================

/**
 * Limpa o cache
 * {{cms_cache_endpoint}} - Endpoint para limpar cache
 */
CMSSettings.clearCache = function () {
  if (confirm('Tem certeza que deseja limpar o cache?')) {
    CMS.showLoading();
    
    fetch(CMSSettings.endpoint + '/cache/clear', {
      method: 'POST',
    })
      .then(function (response) {
        return response.json();
      })
      .then(function (data) {
        CMS.hideLoading();
        
        if (data.success) {
          CMS.showNotification('Sucesso!', 'Cache limpo com sucesso', 'success');
        } else {
          CMS.showNotification('Erro!', 'Erro ao limpar cache', 'danger');
        }
      })
      .catch(function (error) {
        CMS.hideLoading();
        CMS.showNotification('Erro!', 'Erro ao limpar cache', 'danger');
        console.error('[CMSSettings] Erro:', error);
      });
  }
};

// ============================================
// 13. GERENCIAMENTO DE SEGURANÇA
// ============================================

/**
 * Gera uma chave de segurança
 * {{cms_security_key_field}} - Campo para armazenar a chave
 */
CMSSettings.generateSecurityKey = function (fieldId) {
  const key = CMSSettings.generateRandomString(32);
  document.getElementById(fieldId).value = key;
};

/**
 * Gera uma string aleatória
 * {{cms_length}} - Comprimento da string
 */
CMSSettings.generateRandomString = function (length) {
  const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
  let result = '';
  
  for (let i = 0; i < length; i++) {
    result += chars.charAt(Math.floor(Math.random() * chars.length));
  }
  
  return result;
};

// ============================================
// 14. EXPORT/IMPORT DE CONFIGURAÇÕES
// ============================================

/**
 * Exporta as configurações para JSON
 * {{cms_export_filename}} - Nome do arquivo
 */
CMSSettings.exportSettings = function () {
  const dataStr = JSON.stringify(CMSSettings.data, null, 2);
  const dataBlob = new Blob([dataStr], { type: 'application/json' });
  const url = URL.createObjectURL(dataBlob);
  const link = document.createElement('a');
  
  link.href = url;
  link.download = 'cms-settings-' + new Date().getTime() + '.json';
  link.click();
  
  CMS.showNotification('Sucesso!', 'Configurações exportadas com sucesso', 'success');
};

/**
 * Importa configurações de um arquivo JSON
 * {{cms_import_file}} - Arquivo a importar
 */
CMSSettings.importSettings = function (fileInput) {
  const file = fileInput.files[0];
  
  if (!file) {
    CMS.showNotification('Erro!', 'Selecione um arquivo para importar', 'warning');
    return;
  }
  
  const reader = new FileReader();
  
  reader.onload = function (e) {
    try {
      const importedData = JSON.parse(e.target.result);
      
      if (confirm('Tem certeza que deseja importar estas configurações? Isso sobrescreverá as configurações atuais.')) {
        CMSSettings.data = importedData;
        CMSSettings.populateForm();
        CMS.showNotification('Sucesso!', 'Configurações importadas com sucesso', 'success');
      }
    } catch (error) {
      CMS.showNotification('Erro!', 'Arquivo JSON inválido', 'danger');
      console.error('[CMSSettings] Erro ao importar:', error);
    }
  };
  
  reader.readAsText(file);
};

// ============================================
// 15. UTILITÁRIOS
// ============================================

/**
 * Copia um valor para a área de transferência
 * {{cms_element_id}} - ID do elemento
 */
CMSSettings.copyValue = function (elementId) {
  const element = document.getElementById(elementId);
  const text = element.value || element.textContent;
  
  CMS.copyToClipboard(text);
};

/**
 * Reseta um campo para o valor padrão
 * {{cms_field_id}} - ID do campo
 * {{cms_default_value}} - Valor padrão
 */
CMSSettings.resetField = function (fieldId, defaultValue) {
  const field = document.getElementById(fieldId);
  field.value = defaultValue;
  CMSSettings.modified = true;
  CMSSettings.updateModifiedIndicator();
};

// ============================================
// 16. INICIALIZAR AO CARREGAR
// ============================================

document.addEventListener('DOMContentLoaded', function () {
  CMSSettings.init();
});

// ============================================
// 17. EXPORTAR OBJETO
// ============================================

window.CMSSettings = CMSSettings;
