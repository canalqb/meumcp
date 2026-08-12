<?php
/**
 * CMS SaaS - Footer Include
 * Arquivo: includes/footer.php
 * Descrição: Template para o rodapé da página
 * Autor: CMS Generator
 * Data: 2026-02-22
 */

// {{cms_load_settings}} - Carregar configurações
require_once __DIR__ . '/../config/settings.php';
?>

<!-- {{cms_bootstrap_js}} - Bootstrap 5 JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- {{cms_main_script}} - Script principal do CMS -->
<script src="<?php echo cms_get_url('/js/cms-main.js'); ?>"></script>

<!-- {{cms_settings_script}} - Script de gerenciamento de configurações -->
<script src="<?php echo cms_get_url('/js/cms-settings.js'); ?>"></script>

<!-- {{cms_custom_js}} - JavaScript customizado -->
<?php if ($customJs = cms_get_setting('custom_js')): ?>
<script>
<?php echo $customJs; ?>
</script>
<?php endif; ?>

<!-- {{cms_custom_body_scripts}} - Scripts customizados no body -->
<?php if ($customBodyScripts = cms_get_setting('custom_body_scripts')): ?>
<?php echo $customBodyScripts; ?>
<?php endif; ?>

<!-- {{cms_custom_footer_scripts}} - Scripts customizados no footer -->
<?php if ($customFooterScripts = cms_get_setting('custom_footer_scripts')): ?>
<?php echo $customFooterScripts; ?>
<?php endif; ?>

<!-- {{cms_google_tag_manager_noscript}} - Google Tag Manager (noscript) -->
<?php if ($gtmId = cms_get_setting('google_tag_manager_id')): ?>
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=<?php echo htmlspecialchars($gtmId); ?>"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<?php endif; ?>

<!-- {{cms_footer_script_initialization}} - Inicialização de scripts -->
<script>
  // {{cms_initialize_cms}} - Inicializar CMS
  document.addEventListener('DOMContentLoaded', function() {
    console.log('[CMS] Página carregada com sucesso');
    
    // {{cms_set_theme}} - Aplicar tema salvo
    const savedTheme = localStorage.getItem('cms_theme') || 'light';
    if (savedTheme === 'dark') {
      document.body.classList.add('cms-dark-mode');
    }
  });
</script>
