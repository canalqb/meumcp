# 🌐 Regra PHP: Protocolo Dinâmico e Detecção de Ambiente

Esta regra define como o sistema deve se comportar em relação ao protocolo (HTTP/HTTPS) e à detecção de ambiente (Local vs. Hospedado).

## 🎯 Objetivo
Garantir que o site funcione corretamente em ambiente de desenvolvimento (XAMPP/Ubuntu) via HTTP e em ambiente de produção via HTTPS, sem necessidade de alteração manual em arquivos de configuração.

## 🛠️ Lógica de Detecção (Universal)

O sistema deve identificar como **Ambiente Local** se o host for:
- `localhost`
- `127.0.0.1`
- IPs iniciados em `192.168.`
- IPs iniciados em `10.`

### 📋 Implementação no `app-config.php`

```php
<?php
// Detecção de Ambiente Local
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$isLocal = false;

if (
    $host === 'localhost' || 
    $host === '127.0.0.1' || 
    strpos($host, '192.168.') === 0 || 
    strpos($host, '10.') === 0
) {
    $isLocal = true;
}

// Definição de Protocolo
// Força HTTP em local, tenta detectar ou força HTTPS em produção
if ($isLocal) {
    $protocol = "http://";
} else {
    // Em produção, forçamos HTTPS para segurança e AdSense
    $protocol = "https://";
}

define('IS_LOCAL', $isLocal);
define('APP_PROTOCOL', $protocol);
define('APP_URL', APP_PROTOCOL . $host);
?>
```

### 🗄️ Integração com Banco de Dados (`conecta.php`)

A variável `$localhost` deve ser definida automaticamente:

```php
<?php
require_once 'config/app-config.php';
$localhost = IS_LOCAL;

if ($localhost) {
    // Configurações Local
} else {
    // Configurações Produção
}
?>
```

## 🚨 Regras de Ouro
1. **NUNCA** deixe o protocolo "https" fixo se o host for local.
2. **SEMPRE** use a constante `APP_URL` para links internos, redirecionamentos e assets.
3. **PROIBIDO** editar manualmente a variável `$localhost` em `conecta.php` após esta implementação.
