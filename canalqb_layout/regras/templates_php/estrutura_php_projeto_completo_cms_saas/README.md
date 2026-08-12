# CMS SaaS Admin Panel Template

Um template completo e profissional para painel administrativo de CMS para SaaS, desenvolvido com **Bootstrap 5** e seguindo padrões de nomenclatura rigorosos para facilitar integração com LLMs.

## 📋 Características

### ✅ Seções Implementadas

1. **Dashboard** - Visão geral do sistema com estatísticas
2. **Identidade do Site** - Configuração de nome, slogan, descrição e palavras-chave
3. **SEO Avançado** - Meta tags, Open Graph, Twitter Cards, Schema Markup, Robots.txt
4. **Ads & Pixels** - Google Analytics, GTM, Facebook Pixel, Bing Webmaster
5. **Layout & UI** - Customização de cores, fontes e layout
6. **Imagens** - Gerenciamento de logo, favicon e imagens padrão
7. **Performance** - Cache, minificação, CDN, lazy load
8. **Segurança** - HTTPS, HSTS, CSP, reCAPTCHA
9. **Configurações Extras** - Manutenção, SMTP, Webhooks, Timezone

### 🎨 Design & Estrutura

- **Prefixo HTML**: Todos os elementos HTML utilizam prefixo `cms_`
- **Prefixo CSS**: Todos os arquivos CSS utilizam prefixo `cms-*.css`
- **Prefixo JS**: Todos os arquivos JavaScript utilizam prefixo `cms-*.js`
- **Comentários LLM**: Variáveis comentadas com formato `{{variable_name}}`
- **Bootstrap 5**: Framework CSS moderno e responsivo
- **Sidebar Navigation**: Menu lateral sticky com navegação intuitiva
- **Tema Escuro**: Suporte completo para tema escuro

## 📁 Estrutura de Arquivos

```
cms-saas-template/
├── css/
│   ├── cms-styles.css      # Estilos principais
│   └── cms-theme.css       # Temas e customizações
├── js/
│   ├── cms-main.js         # Funcionalidades principais
│   └── cms-settings.js     # Gerenciamento de configurações
├── templates/              # Templates adicionais
├── includes/               # Includes reutilizáveis
├── config/                 # Configurações
├── index.html              # Template principal
└── README.md               # Esta documentação
```

## 🚀 Como Usar

### 1. Estrutura Básica

```html
<!DOCTYPE html>
<html lang="pt-BR">
  <head>
    <!-- Estilos -->
    <link rel="stylesheet" href="css/cms-styles.css" />
    <link rel="stylesheet" href="css/cms-theme.css" />
  </head>
  <body>
    <!-- Layout -->
    <div class="cms_layout">
      <aside class="cms_sidebar"><!-- Navegação --></aside>
      <main class="cms_main-content"><!-- Conteúdo --></main>
    </div>
    
    <!-- Scripts -->
    <script src="js/cms-main.js"></script>
    <script src="js/cms-settings.js"></script>
  </body>
</html>
```

### 2. Adicionar Nova Aba

```html
<!-- No menu de navegação -->
<li class="cms_nav-item">
  <a href="#nova-secao" class="cms_nav-link" data-cms-tab="nova-secao">
    <i class="bi bi-icon"></i> Nova Seção
  </a>
</li>

<!-- No container de abas -->
<button class="cms_tab-button" data-cms-tab="nova-secao">
  <i class="bi bi-icon"></i> Nova Seção
</button>

<!-- Conteúdo da aba -->
<div id="cms_content_nova-secao" class="cms_tab-content">
  <!-- Seu conteúdo aqui -->
</div>
```

### 3. Adicionar Campo de Formulário

```html
<!-- Campo de texto -->
<div class="cms_form-group">
  <label for="cms_field_name" class="cms_form-label required">
    Nome do Campo
  </label>
  <input
    type="text"
    class="cms_form-control"
    id="cms_field_name"
    name="field_name"
    data-cms-setting="field_name"
    placeholder="Placeholder"
    required
  />
  <small class="cms_form-text">Texto de ajuda</small>
</div>

<!-- Campo de seleção -->
<div class="cms_form-group">
  <label for="cms_select_name" class="cms_form-label">
    Nome da Seleção
  </label>
  <select class="cms_form-control" id="cms_select_name" name="select_name" data-cms-setting="select_name">
    <option value="opcao1">Opção 1</option>
    <option value="opcao2">Opção 2</option>
  </select>
</div>

<!-- Campo de checkbox -->
<div class="cms_form-group">
  <div class="form-check">
    <input
      class="form-check-input"
      type="checkbox"
      id="cms_checkbox_name"
      name="checkbox_name"
      data-cms-setting="checkbox_name"
    />
    <label class="form-check-label" for="cms_checkbox_name">
      Rótulo do Checkbox
    </label>
  </div>
</div>
```

### 4. Usar JavaScript

```javascript
// Acessar objeto CMS global
console.log(CMS.version);
console.log(CMS.theme);
console.log(CMS.userData);

// Mostrar notificação
CMS.showNotification('Título', 'Mensagem', 'success');

// Alternar tema
CMS.toggleTheme();

// Abrir/Fechar modal
CMS.openModal('modal_id');
CMS.closeModal('modal_id');

// Gerenciar configurações
CMSSettings.loadSettings();
CMSSettings.saveSettings();
CMSSettings.exportSettings();
CMSSettings.importSettings(fileInput);

// Utilitários
CMS.formatDate(date, 'pt-BR');
CMS.formatCurrency(value, 'BRL');
CMS.validateEmail(email);
CMS.copyToClipboard(text);
```

## 🎨 Variáveis CSS Customizáveis

```css
/* Cores */
--cms-primary-color: #0d6efd;
--cms-secondary-color: #6c757d;
--cms-success-color: #198754;
--cms-danger-color: #dc3545;
--cms-warning-color: #ffc107;
--cms-info-color: #0dcaf0;

/* Fundos */
--cms-bg-primary: #ffffff;
--cms-bg-secondary: #f8f9fa;
--cms-bg-tertiary: #e9ecef;

/* Texto */
--cms-text-primary: #212529;
--cms-text-secondary: #6c757d;
--cms-text-light: #ffffff;

/* Espaçamento */
--cms-spacing-xs: 0.25rem;
--cms-spacing-sm: 0.5rem;
--cms-spacing-md: 1rem;
--cms-spacing-lg: 1.5rem;
--cms-spacing-xl: 2rem;
--cms-spacing-xxl: 3rem;

/* Tipografia */
--cms-font-family-base: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
--cms-font-size-base: 1rem;
--cms-font-size-sm: 0.875rem;
--cms-font-size-lg: 1.125rem;
```

## 🔧 Customização

### Alterar Cores

```css
:root {
  --cms-primary-color: #your-color;
  --cms-secondary-color: #your-color;
}
```

### Ativar Tema Escuro

```javascript
CMS.applyTheme('dark');
```

### Adicionar Variáveis Customizadas

```html
<!-- No index.html -->
<script>
  CMS.settings = {
    site_name: '{{cms_site_name}}',
    site_url: '{{cms_site_url}}',
    // ... mais configurações
  };
</script>
```

## 📝 Comentários para LLMs

Todos os elementos HTML possuem comentários no formato `{{variable_name}}` para facilitar integração com LLMs:

```html
<!-- {{cms_page_title}} - Título da página -->
<title>{{cms_site_name}} - Painel Administrativo</title>

<!-- {{cms_site_logo}} - Logo do site -->
<img src="{{cms_logo_url}}" alt="{{cms_site_name}}" />

<!-- {{cms_form_site_name}} - Campo: Nome do Site -->
<input type="text" id="cms_site_name" name="site_name" />
```

## 🎯 Classes CSS Principais

### Layout
- `.cms_layout` - Container principal
- `.cms_sidebar` - Sidebar de navegação
- `.cms_main-content` - Conteúdo principal
- `.cms_container` - Container centralizado

### Cards
- `.cms_card` - Card padrão
- `.cms_card-header` - Cabeçalho do card
- `.cms_card-body` - Corpo do card
- `.cms_card-footer` - Rodapé do card

### Formulários
- `.cms_form-group` - Grupo de formulário
- `.cms_form-label` - Rótulo
- `.cms_form-control` - Input/Textarea/Select
- `.cms_form-text` - Texto de ajuda

### Botões
- `.cms_btn` - Botão padrão
- `.cms_btn-primary` - Botão primário
- `.cms_btn-secondary` - Botão secundário
- `.cms_btn-success` - Botão sucesso
- `.cms_btn-danger` - Botão perigo
- `.cms_btn-warning` - Botão aviso
- `.cms_btn-outline` - Botão outline
- `.cms_btn-sm` - Botão pequeno
- `.cms_btn-lg` - Botão grande

### Abas
- `.cms_tabs` - Container de abas
- `.cms_tab-button` - Botão de aba
- `.cms_tab-content` - Conteúdo de aba
- `.cms_tab-button.active` - Aba ativa
- `.cms_tab-content.active` - Conteúdo ativo

### Alertas
- `.cms_alert` - Alerta padrão
- `.cms_alert-success` - Alerta sucesso
- `.cms_alert-danger` - Alerta perigo
- `.cms_alert-warning` - Alerta aviso
- `.cms_alert-info` - Alerta info

### Badges
- `.cms_badge` - Badge padrão
- `.cms_badge-primary` - Badge primário
- `.cms_badge-success` - Badge sucesso
- `.cms_badge-danger` - Badge perigo

### Utilitários
- `.cms_text-center` - Texto centralizado
- `.cms_text-muted` - Texto desabilitado
- `.cms_mb-1`, `.cms_mb-2`, `.cms_mb-3` - Margin bottom
- `.cms_mt-1`, `.cms_mt-2`, `.cms_mt-3` - Margin top
- `.cms_p-1`, `.cms_p-2`, `.cms_p-3` - Padding
- `.cms_d-none` - Display none
- `.cms_d-block` - Display block
- `.cms_d-flex` - Display flex
- `.cms_w-100` - Largura 100%
- `.cms_h-100` - Altura 100%

## 🔐 Segurança

### Boas Práticas

1. **Validação de Entrada**: Sempre validar dados de entrada
2. **Sanitização**: Sanitizar dados antes de salvar
3. **HTTPS**: Forçar HTTPS em produção
4. **CSP**: Implementar Content Security Policy
5. **reCAPTCHA**: Proteger formulários com reCAPTCHA
6. **Autenticação**: Verificar autenticação do usuário

### Exemplo de Validação

```javascript
// Validar email
if (!CMS.validateEmail(email)) {
  CMS.showNotification('Erro!', 'Email inválido', 'danger');
  return;
}

// Validar formulário
if (!CMSSettings.validateForm()) {
  CMS.showNotification('Erro!', 'Preencha todos os campos obrigatórios', 'warning');
  return;
}
```

## 📱 Responsividade

O template é totalmente responsivo e funciona em:
- Desktop (1024px+)
- Tablet (768px - 1023px)
- Mobile (< 768px)

Breakpoints:
- `@media (max-width: 768px)` - Tablet e mobile
- `@media (max-width: 480px)` - Mobile pequeno

## 🌐 Suporte a Navegadores

- Chrome 90+
- Firefox 88+
- Safari 14+
- Edge 90+

## 📚 Documentação Adicional

### Variáveis de Template

| Variável | Descrição |
|----------|-----------|
| `{{cms_site_name}}` | Nome do site |
| `{{cms_site_slogan}}` | Slogan do site |
| `{{cms_site_description}}` | Descrição do site |
| `{{cms_site_keywords}}` | Palavras-chave |
| `{{cms_author}}` | Autor do site |
| `{{cms_language}}` | Idioma (pt-BR, en-US, etc) |
| `{{cms_logo_url}}` | URL do logo |
| `{{cms_favicon_url}}` | URL do favicon |
| `{{cms_site_url}}` | URL do site |
| `{{cms_custom_head_scripts}}` | Scripts customizados no head |
| `{{cms_custom_body_scripts}}` | Scripts customizados no body |

### Funções JavaScript Principais

```javascript
// CMS - Objeto Global
CMS.applyTheme(themeName)           // Aplicar tema
CMS.toggleTheme()                   // Alternar tema
CMS.showNotification(title, msg, type) // Mostrar notificação
CMS.showLoading()                   // Mostrar loading
CMS.hideLoading()                   // Ocultar loading
CMS.openModal(modalId)              // Abrir modal
CMS.closeModal(modalId)             // Fechar modal
CMS.switchTab(tabName)              // Alternar aba
CMS.formatDate(date, format)        // Formatar data
CMS.formatCurrency(value, currency) // Formatar moeda
CMS.validateEmail(email)            // Validar email
CMS.copyToClipboard(text)           // Copiar para clipboard
CMS.logout()                        // Fazer logout

// CMSSettings - Gerenciador de Configurações
CMSSettings.loadSettings()          // Carregar configurações
CMSSettings.saveSettings()          // Salvar configurações
CMSSettings.validateForm()          // Validar formulário
CMSSettings.exportSettings()        // Exportar configurações
CMSSettings.importSettings(file)    // Importar configurações
CMSSettings.clearCache()            // Limpar cache
CMSSettings.generateSecurityKey(fieldId) // Gerar chave de segurança
CMSSettings.previewImage(inputId, previewId) // Preview de imagem
CMSSettings.removeImage(previewId, inputId)  // Remover imagem
```

## 🤝 Integração com Backend

### Endpoint de Configurações

```
GET /api/v1/settings
POST /api/v1/settings
```

### Formato de Resposta

```json
{
  "success": true,
  "settings": {
    "site_name": "Meu Site",
    "site_slogan": "Meu Slogan",
    "primary_color": "#0d6efd",
    "enable_cache": 1,
    "maintenance_mode": 0
  }
}
```

### Endpoint de Autenticação

```
GET /api/v1/auth/check
POST /api/v1/auth/logout
```

## 📄 Licença

Este template é fornecido como está para uso em projetos SaaS e CMS.

## 🆘 Suporte

Para dúvidas ou sugestões, consulte a documentação ou entre em contato com o desenvolvedor.

---

**Versão**: 1.0.0  
**Última Atualização**: 2026-02-22  
**Autor**: CMS Generator
