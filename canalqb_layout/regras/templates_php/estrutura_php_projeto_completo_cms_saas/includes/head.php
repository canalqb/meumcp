<?php
/**
 * CMS SaaS - Head Include
 * Arquivo: includes/head.php
 * Descrição: Template para o <head> da página
 * Autor: CMS Generator
 * Data: 2026-02-22
 */

// {{cms_load_settings}} - Carregar configurações
require_once __DIR__ . '/../config/settings.php';

// {{cms_page_title}} - Variável de título da página
$pageTitle = isset($pageTitle) ? $pageTitle : cms_get_setting('site_name');

// {{cms_page_description}} - Variável de descrição da página
$pageDescription = isset($pageDescription) ? $pageDescription : cms_get_setting('default_description', 'Painel administrativo do CMS');

// {{cms_page_keywords}} - Variável de palavras-chave
$pageKeywords = isset($pageKeywords) ? $pageKeywords : cms_get_setting('default_keywords', 'cms, admin, painel');

// {{cms_og_title}} - Variável de título Open Graph
$ogTitle = isset($ogTitle) ? $ogTitle : $pageTitle;

// {{cms_og_description}} - Variável de descrição Open Graph
$ogDescription = isset($ogDescription) ? $ogDescription : $pageDescription;

// {{cms_og_image}} - Variável de imagem Open Graph
$ogImage = isset($ogImage) ? $ogImage : cms_get_setting('og_image_url', '');

// {{cms_twitter_card}} - Variável de tipo de Twitter Card
$twitterCard = isset($twitterCard) ? $twitterCard : 'summary_large_image';

// {{cms_canonical_url}} - Variável de URL canônica
$canonicalUrl = isset($canonicalUrl) ? $canonicalUrl : cms_get_url();
?>

<!-- {{cms_charset}} - Charset da página -->
<meta charset="UTF-8" />

<!-- {{cms_viewport}} - Meta tag de viewport para responsividade -->
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1" />

<!-- {{cms_page_title}} - Título da página -->
<title><?php echo htmlspecialchars($pageTitle); ?></title>

<!-- {{cms_page_description}} - Descrição da página -->
<meta name="description" content="<?php echo htmlspecialchars($pageDescription); ?>" />

<!-- {{cms_page_keywords}} - Palavras-chave da página -->
<meta name="keywords" content="<?php echo htmlspecialchars($pageKeywords); ?>" />

<!-- {{cms_author}} - Autor da página -->
<meta name="author" content="<?php echo htmlspecialchars(cms_get_setting('author', 'CMS SaaS')); ?>" />

<!-- {{cms_language}} - Idioma da página -->
<meta name="language" content="<?php echo htmlspecialchars(cms_get_setting('language', 'pt-BR')); ?>" />

<!-- {{cms_robots}} - Robots meta tag -->
<meta name="robots" content="index, follow" />

<!-- {{cms_revisit_after}} - Revisit after -->
<meta name="revisit-after" content="7 days" />

<!-- {{cms_distribution}} - Distribution -->
<meta name="distribution" content="global" />

<!-- {{cms_rating}} - Rating -->
<meta name="rating" content="general" />

<!-- {{cms_favicon}} - Favicon -->
<link rel="icon" type="image/x-icon" href="<?php echo cms_get_storage_url(cms_get_setting('favicon', 'favicon.ico')); ?>" />

<!-- {{cms_apple_touch_icon}} - Apple Touch Icon -->
<link rel="apple-touch-icon" href="<?php echo cms_get_storage_url(cms_get_setting('apple_touch_icon', 'apple-touch-icon.png')); ?>" />

<!-- {{cms_canonical_url}} - Canonical URL -->
<link rel="canonical" href="<?php echo htmlspecialchars($canonicalUrl); ?>" />

<!-- {{cms_open_graph_title}} - Open Graph - Title -->
<meta property="og:title" content="<?php echo htmlspecialchars($ogTitle); ?>" />

<!-- {{cms_open_graph_description}} - Open Graph - Description -->
<meta property="og:description" content="<?php echo htmlspecialchars($ogDescription); ?>" />

<!-- {{cms_open_graph_image}} - Open Graph - Image -->
<?php if (!empty($ogImage)): ?>
<meta property="og:image" content="<?php echo htmlspecialchars($ogImage); ?>" />
<meta property="og:image:width" content="1200" />
<meta property="og:image:height" content="630" />
<?php endif; ?>

<!-- {{cms_open_graph_type}} - Open Graph - Type -->
<meta property="og:type" content="website" />

<!-- {{cms_open_graph_url}} - Open Graph - URL -->
<meta property="og:url" content="<?php echo htmlspecialchars($canonicalUrl); ?>" />

<!-- {{cms_open_graph_site_name}} - Open Graph - Site Name -->
<meta property="og:site_name" content="<?php echo htmlspecialchars(cms_get_setting('site_name', 'CMS SaaS')); ?>" />

<!-- {{cms_open_graph_locale}} - Open Graph - Locale -->
<meta property="og:locale" content="<?php echo str_replace('-', '_', cms_get_setting('language', 'pt_BR')); ?>" />

<!-- {{cms_twitter_card_type}} - Twitter Card - Type -->
<meta name="twitter:card" content="<?php echo htmlspecialchars($twitterCard); ?>" />

<!-- {{cms_twitter_card_title}} - Twitter Card - Title -->
<meta name="twitter:title" content="<?php echo htmlspecialchars($ogTitle); ?>" />

<!-- {{cms_twitter_card_description}} - Twitter Card - Description -->
<meta name="twitter:description" content="<?php echo htmlspecialchars($ogDescription); ?>" />

<!-- {{cms_twitter_card_image}} - Twitter Card - Image -->
<?php if (!empty($ogImage)): ?>
<meta name="twitter:image" content="<?php echo htmlspecialchars($ogImage); ?>" />
<?php endif; ?>

<!-- {{cms_twitter_card_site}} - Twitter Card - Site -->
<?php if ($twitterHandle = cms_get_setting('twitter_handle')): ?>
<meta name="twitter:site" content="@<?php echo htmlspecialchars($twitterHandle); ?>" />
<?php endif; ?>

<!-- {{cms_schema_markup}} - Schema Markup JSON-LD -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "WebSite",
  "name": "<?php echo cms_get_setting('site_name', 'CMS SaaS'); ?>",
  "description": "<?php echo cms_get_setting('site_description', ''); ?>",
  "url": "<?php echo cms_get_url(); ?>",
  "image": {
    "@type": "ImageObject",
    "url": "<?php echo !empty($ogImage) ? $ogImage : ''; ?>",
    "width": 1200,
    "height": 630
  },
  "potentialAction": {
    "@type": "SearchAction",
    "target": {
      "@type": "EntryPoint",
      "urlTemplate": "<?php echo cms_get_url('/search?q={search_term_string}'); ?>"
    },
    "query-input": "required name=search_term_string"
  }
}
</script>

<!-- {{cms_bootstrap_css}} - Bootstrap 5 CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />

<!-- {{cms_bootstrap_icons}} - Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet" />

<!-- {{cms_custom_styles}} - Estilos customizados do CMS -->
<link rel="stylesheet" href="<?php echo cms_get_url('/css/cms-styles.css'); ?>" />
<link rel="stylesheet" href="<?php echo cms_get_url('/css/cms-theme.css'); ?>" />

<!-- {{cms_custom_css}} - CSS customizado -->
<?php if ($customCss = cms_get_setting('custom_css')): ?>
<style>
<?php echo $customCss; ?>
</style>
<?php endif; ?>

<!-- {{cms_preload_fonts}} - Preload de fontes -->
<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />

<!-- {{cms_google_analytics}} - Google Analytics -->
<?php if ($gaId = cms_get_setting('google_analytics_id')): ?>
<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo htmlspecialchars($gaId); ?>"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', '<?php echo htmlspecialchars($gaId); ?>');
</script>
<?php endif; ?>

<!-- {{cms_google_tag_manager}} - Google Tag Manager -->
<?php if ($gtmId = cms_get_setting('google_tag_manager_id')): ?>
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','<?php echo htmlspecialchars($gtmId); ?>');</script>
<?php endif; ?>

<!-- {{cms_facebook_pixel}} - Facebook Pixel -->
<?php if ($fbPixelId = cms_get_setting('facebook_pixel_id')): ?>
<script>
  !function(f){if(!f.fbq)f.fbq=function(){f.fbq.callMethod?
  f.fbq.callMethod.apply(f.fbq,arguments):f.fbq.queue.push(arguments)};
  if(!f._fbq)f._fbq=f.fbq;f.fbq.push=f.fbq;f.fbq.loaded=!0;
  f.fbq.version='2.0';f.fbq.queue=[];t=b.createElement('script');
  t.async=!0;t.src='https://connect.facebook.net/en_US/fbevents.js';
  b=b.getElementsByTagName('script')[0];b.parentNode.insertBefore(t,b)}(window,document,'script');
  fbq('init', '<?php echo htmlspecialchars($fbPixelId); ?>');
  fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
  src="https://www.facebook.com/tr?id=<?php echo htmlspecialchars($fbPixelId); ?>&ev=PageView&noscript=1"
/></noscript>
<?php endif; ?>

<!-- {{cms_custom_head_scripts}} - Scripts customizados no head -->
<?php if ($customHeadScripts = cms_get_setting('custom_head_scripts')): ?>
<?php echo $customHeadScripts; ?>
<?php endif; ?>

<!-- {{cms_security_headers}} - Headers de segurança -->
<?php
// Definir headers de segurança
if (cms_is_production()) {
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: SAMEORIGIN');
    header('X-XSS-Protection: 1; mode=block');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    
    if ($cspHeader = cms_get_setting('csp_header')) {
        header('Content-Security-Policy: ' . $cspHeader);
    }
}
?>
