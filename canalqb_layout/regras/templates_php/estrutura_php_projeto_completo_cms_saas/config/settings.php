<?php
/**
 * CMS SaaS - Configurações Globais
 * Arquivo: config/settings.php
 * Descrição: Arquivo de configuração central do CMS
 * Autor: CMS Generator
 * Data: 2026-02-22
 */

// ============================================
// 1. CONFIGURAÇÕES DE BANCO DE DADOS
// ============================================

/**
 * {{cms_db_host}} - Host do banco de dados
 * Exemplo: localhost, 127.0.0.1, db.example.com
 */
define('CMS_DB_HOST', getenv('DB_HOST') ?: 'localhost');

/**
 * {{cms_db_user}} - Usuário do banco de dados
 */
define('CMS_DB_USER', getenv('DB_USER') ?: 'root');

/**
 * {{cms_db_password}} - Senha do banco de dados
 */
define('CMS_DB_PASSWORD', getenv('DB_PASSWORD') ?: '');

/**
 * {{cms_db_name}} - Nome do banco de dados
 */
define('CMS_DB_NAME', getenv('DB_NAME') ?: 'cms_saas');

/**
 * {{cms_db_charset}} - Charset do banco de dados
 */
define('CMS_DB_CHARSET', 'utf8mb4');

/**
 * {{cms_db_port}} - Porta do banco de dados
 */
define('CMS_DB_PORT', getenv('DB_PORT') ?: 3306);

// ============================================
// 2. CONFIGURAÇÕES DE APLICAÇÃO
// ============================================

/**
 * {{cms_app_name}} - Nome da aplicação
 */
define('CMS_APP_NAME', 'CMS SaaS');

/**
 * {{cms_app_version}} - Versão da aplicação
 */
define('CMS_APP_VERSION', '1.0.0');

/**
 * {{cms_app_url}} - URL base da aplicação
 * Exemplo: https://seu-site.com ou http://localhost/cms
 */
define('CMS_APP_URL', getenv('APP_URL') ?: 'http://localhost');

/**
 * {{cms_app_env}} - Ambiente da aplicação (development, production)
 */
define('CMS_APP_ENV', getenv('APP_ENV') ?: 'development');

/**
 * {{cms_app_debug}} - Modo debug (true/false)
 */
define('CMS_APP_DEBUG', CMS_APP_ENV === 'development');

/**
 * {{cms_app_timezone}} - Fuso horário da aplicação
 */
define('CMS_APP_TIMEZONE', 'America/Sao_Paulo');

// ============================================
// 3. CONFIGURAÇÕES DE SEGURANÇA
// ============================================

/**
 * {{cms_jwt_secret}} - Chave secreta para JWT
 */
define('CMS_JWT_SECRET', getenv('JWT_SECRET') ?: 'your-secret-key-change-in-production');

/**
 * {{cms_jwt_expiration}} - Expiração do JWT em segundos (7 dias)
 */
define('CMS_JWT_EXPIRATION', 7 * 24 * 60 * 60);

/**
 * {{cms_session_timeout}} - Timeout da sessão em minutos
 */
define('CMS_SESSION_TIMEOUT', 30);

/**
 * {{cms_force_https}} - Forçar HTTPS
 */
define('CMS_FORCE_HTTPS', CMS_APP_ENV === 'production');

/**
 * {{cms_enable_hsts}} - Ativar HSTS
 */
define('CMS_ENABLE_HSTS', CMS_APP_ENV === 'production');

/**
 * {{cms_recaptcha_site_key}} - Chave pública do reCAPTCHA
 */
define('CMS_RECAPTCHA_SITE_KEY', getenv('RECAPTCHA_SITE_KEY') ?: '');

/**
 * {{cms_recaptcha_secret_key}} - Chave secreta do reCAPTCHA
 */
define('CMS_RECAPTCHA_SECRET_KEY', getenv('RECAPTCHA_SECRET_KEY') ?: '');

// ============================================
// 4. CONFIGURAÇÕES DE EMAIL
// ============================================

/**
 * {{cms_smtp_host}} - Host SMTP
 */
define('CMS_SMTP_HOST', getenv('SMTP_HOST') ?: 'smtp.gmail.com');

/**
 * {{cms_smtp_port}} - Porta SMTP
 */
define('CMS_SMTP_PORT', getenv('SMTP_PORT') ?: 587);

/**
 * {{cms_smtp_username}} - Usuário SMTP
 */
define('CMS_SMTP_USERNAME', getenv('SMTP_USERNAME') ?: '');

/**
 * {{cms_smtp_password}} - Senha SMTP
 */
define('CMS_SMTP_PASSWORD', getenv('SMTP_PASSWORD') ?: '');

/**
 * {{cms_smtp_encryption}} - Criptografia SMTP (tls, ssl)
 */
define('CMS_SMTP_ENCRYPTION', getenv('SMTP_ENCRYPTION') ?: 'tls');

/**
 * {{cms_mail_from}} - Email de envio padrão
 */
define('CMS_MAIL_FROM', getenv('MAIL_FROM') ?: 'noreply@seu-site.com');

/**
 * {{cms_mail_from_name}} - Nome de envio padrão
 */
define('CMS_MAIL_FROM_NAME', getenv('MAIL_FROM_NAME') ?: 'CMS SaaS');

// ============================================
// 5. CONFIGURAÇÕES DE CACHE
// ============================================

/**
 * {{cms_cache_driver}} - Driver de cache (file, redis, memcached)
 */
define('CMS_CACHE_DRIVER', getenv('CACHE_DRIVER') ?: 'file');

/**
 * {{cms_cache_ttl}} - TTL padrão do cache em segundos (24 horas)
 */
define('CMS_CACHE_TTL', 24 * 60 * 60);

/**
 * {{cms_cache_path}} - Caminho para arquivos de cache
 */
define('CMS_CACHE_PATH', __DIR__ . '/../storage/cache');

/**
 * {{cms_redis_host}} - Host Redis
 */
define('CMS_REDIS_HOST', getenv('REDIS_HOST') ?: 'localhost');

/**
 * {{cms_redis_port}} - Porta Redis
 */
define('CMS_REDIS_PORT', getenv('REDIS_PORT') ?: 6379);

/**
 * {{cms_redis_password}} - Senha Redis
 */
define('CMS_REDIS_PASSWORD', getenv('REDIS_PASSWORD') ?: '');

// ============================================
// 6. CONFIGURAÇÕES DE ARMAZENAMENTO
// ============================================

/**
 * {{cms_storage_driver}} - Driver de armazenamento (local, s3, azure)
 */
define('CMS_STORAGE_DRIVER', getenv('STORAGE_DRIVER') ?: 'local');

/**
 * {{cms_storage_path}} - Caminho para armazenamento local
 */
define('CMS_STORAGE_PATH', __DIR__ . '/../storage/uploads');

/**
 * {{cms_storage_url}} - URL pública para armazenamento
 */
define('CMS_STORAGE_URL', getenv('STORAGE_URL') ?: CMS_APP_URL . '/storage');

/**
 * {{cms_max_upload_size}} - Tamanho máximo de upload em bytes (10MB)
 */
define('CMS_MAX_UPLOAD_SIZE', 10 * 1024 * 1024);

/**
 * {{cms_allowed_file_types}} - Tipos de arquivo permitidos
 */
define('CMS_ALLOWED_FILE_TYPES', ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx', 'xls', 'xlsx']);

// ============================================
// 7. CONFIGURAÇÕES DE API
// ============================================

/**
 * {{cms_api_version}} - Versão da API
 */
define('CMS_API_VERSION', 'v1');

/**
 * {{cms_api_rate_limit}} - Limite de requisições por minuto
 */
define('CMS_API_RATE_LIMIT', 60);

/**
 * {{cms_api_timeout}} - Timeout da API em segundos
 */
define('CMS_API_TIMEOUT', 30);

/**
 * {{cms_cors_allowed_origins}} - Origens CORS permitidas
 */
define('CMS_CORS_ALLOWED_ORIGINS', [
    'http://localhost',
    'http://localhost:3000',
    'https://seu-site.com',
]);

// ============================================
// 8. CONFIGURAÇÕES DE LOGGING
// ============================================

/**
 * {{cms_log_channel}} - Canal de logging (single, daily, stack)
 */
define('CMS_LOG_CHANNEL', getenv('LOG_CHANNEL') ?: 'single');

/**
 * {{cms_log_level}} - Nível de logging (debug, info, notice, warning, error, critical, alert, emergency)
 */
define('CMS_LOG_LEVEL', getenv('LOG_LEVEL') ?: (CMS_APP_DEBUG ? 'debug' : 'error'));

/**
 * {{cms_log_path}} - Caminho para arquivos de log
 */
define('CMS_LOG_PATH', __DIR__ . '/../storage/logs');

// ============================================
// 9. CONFIGURAÇÕES DE PAGINAÇÃO
// ============================================

/**
 * {{cms_pagination_per_page}} - Itens por página padrão
 */
define('CMS_PAGINATION_PER_PAGE', 15);

/**
 * {{cms_pagination_max_per_page}} - Máximo de itens por página
 */
define('CMS_PAGINATION_MAX_PER_PAGE', 100);

// ============================================
// 10. CONFIGURAÇÕES DE INTEGRAÇÕES
// ============================================

/**
 * {{cms_google_analytics_id}} - ID do Google Analytics
 */
define('CMS_GOOGLE_ANALYTICS_ID', getenv('GOOGLE_ANALYTICS_ID') ?: '');

/**
 * {{cms_google_tag_manager_id}} - ID do Google Tag Manager
 */
define('CMS_GOOGLE_TAG_MANAGER_ID', getenv('GOOGLE_TAG_MANAGER_ID') ?: '');

/**
 * {{cms_facebook_pixel_id}} - ID do Facebook Pixel
 */
define('CMS_FACEBOOK_PIXEL_ID', getenv('FACEBOOK_PIXEL_ID') ?: '');

/**
 * {{cms_stripe_public_key}} - Chave pública do Stripe
 */
define('CMS_STRIPE_PUBLIC_KEY', getenv('STRIPE_PUBLIC_KEY') ?: '');

/**
 * {{cms_stripe_secret_key}} - Chave secreta do Stripe
 */
define('CMS_STRIPE_SECRET_KEY', getenv('STRIPE_SECRET_KEY') ?: '');

// ============================================
// 11. INICIALIZAR CONFIGURAÇÕES
// ============================================

// Definir timezone
date_default_timezone_set(CMS_APP_TIMEZONE);

// Definir charset padrão
mb_internal_encoding(CMS_DB_CHARSET);

// Inicializar sessão
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Definir headers de segurança
if (CMS_FORCE_HTTPS && $_SERVER['HTTPS'] !== 'on') {
    header('Location: https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
    exit;
}

if (CMS_ENABLE_HSTS) {
    header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
}

// ============================================
// 12. FUNÇÕES AUXILIARES
// ============================================

/**
 * {{cms_get_setting}} - Obter configuração
 * @param string $key - Chave da configuração
 * @param mixed $default - Valor padrão
 * @return mixed
 */
function cms_get_setting($key, $default = null)
{
    return defined('CMS_' . strtoupper($key)) ? constant('CMS_' . strtoupper($key)) : $default;
}

/**
 * {{cms_is_debug}} - Verificar se está em modo debug
 * @return bool
 */
function cms_is_debug()
{
    return CMS_APP_DEBUG;
}

/**
 * {{cms_is_production}} - Verificar se está em produção
 * @return bool
 */
function cms_is_production()
{
    return CMS_APP_ENV === 'production';
}

/**
 * {{cms_get_url}} - Obter URL completa
 * @param string $path - Caminho relativo
 * @return string
 */
function cms_get_url($path = '')
{
    return rtrim(CMS_APP_URL, '/') . '/' . ltrim($path, '/');
}

/**
 * {{cms_get_storage_url}} - Obter URL de armazenamento
 * @param string $path - Caminho relativo
 * @return string
 */
function cms_get_storage_url($path = '')
{
    return rtrim(CMS_STORAGE_URL, '/') . '/' . ltrim($path, '/');
}

/**
 * {{cms_log}} - Registrar log
 * @param string $message - Mensagem
 * @param string $level - Nível (debug, info, warning, error)
 * @return void
 */
function cms_log($message, $level = 'info')
{
    $timestamp = date('Y-m-d H:i:s');
    $logMessage = "[$timestamp] [$level] $message" . PHP_EOL;
    
    if (!is_dir(CMS_LOG_PATH)) {
        mkdir(CMS_LOG_PATH, 0755, true);
    }
    
    $logFile = CMS_LOG_PATH . '/' . date('Y-m-d') . '.log';
    file_put_contents($logFile, $logMessage, FILE_APPEND);
}

/**
 * {{cms_dd}} - Dump and die (apenas em debug)
 * @param mixed $var - Variável a exibir
 * @return void
 */
function cms_dd($var)
{
    if (cms_is_debug()) {
        echo '<pre>';
        var_dump($var);
        echo '</pre>';
        die;
    }
}

// ============================================
// 13. RETORNAR CONFIGURAÇÕES
// ============================================

return [
    'app' => [
        'name' => CMS_APP_NAME,
        'version' => CMS_APP_VERSION,
        'url' => CMS_APP_URL,
        'env' => CMS_APP_ENV,
        'debug' => CMS_APP_DEBUG,
        'timezone' => CMS_APP_TIMEZONE,
    ],
    'database' => [
        'host' => CMS_DB_HOST,
        'user' => CMS_DB_USER,
        'password' => CMS_DB_PASSWORD,
        'name' => CMS_DB_NAME,
        'charset' => CMS_DB_CHARSET,
        'port' => CMS_DB_PORT,
    ],
    'security' => [
        'jwt_secret' => CMS_JWT_SECRET,
        'jwt_expiration' => CMS_JWT_EXPIRATION,
        'session_timeout' => CMS_SESSION_TIMEOUT,
        'force_https' => CMS_FORCE_HTTPS,
        'enable_hsts' => CMS_ENABLE_HSTS,
    ],
    'cache' => [
        'driver' => CMS_CACHE_DRIVER,
        'ttl' => CMS_CACHE_TTL,
        'path' => CMS_CACHE_PATH,
    ],
    'storage' => [
        'driver' => CMS_STORAGE_DRIVER,
        'path' => CMS_STORAGE_PATH,
        'url' => CMS_STORAGE_URL,
        'max_upload_size' => CMS_MAX_UPLOAD_SIZE,
    ],
];
