# 📝 Template: Configuração de Protocolo Dinâmico

Use este template para inicializar a lógica de ambiente em novos módulos ou projetos.

## 🚀 Como Aplicar

Copie o bloco abaixo para o seu arquivo de configuração central (ex: `config.php` ou `app-config.php`):

```php
<?php
/**
 * Lógica Universal de Ambiente @CanalQb
 * Detecta automaticamente se o acesso é local ou remoto.
 */
function getAppEnvironment() {
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    
    // Ranges de IP locais e redes privadas
    $localHosts = ['localhost', '127.0.0.1'];
    $isLocal = in_array($host, $localHosts) || 
               preg_match('/^(192\.168\.|10\.|172\.(1[6-9]|2[0-9]|3[0-1])\.)/', $host);
               
    return [
        'isLocal' => $isLocal,
        'protocol' => $isLocal ? 'http://' : 'https://',
        'host' => $host
    ];
}

$env = getAppEnvironment();
define('IS_LOCAL_ENV', $env['isLocal']);
define('BASE_PROTOCOL', $env['protocol']);
define('SITE_URL', BASE_PROTOCOL . $env['host']);

// Exemplo de uso em links
// echo SITE_URL . "/assets/css/style.css";
?>
```

## 🎯 Benefícios
- **Portabilidade**: O código funciona em XAMPP, Ubuntu Local e Servidores Web sem toque humano.
- **Segurancă**: Garante HTTPS em produção, exigência do Google AdSense e APIs modernas.
- **Desenvolvimento**: Evita erros de certificados SSL inválidos em ambiente local.
