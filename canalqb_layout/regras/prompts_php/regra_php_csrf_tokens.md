# 🔐 Regra PHP — Proteção CSRF (Obrigatória)

Esta regra define o padrão obrigatório de proteção contra CSRF para todo formulário HTML e qualquer requisição que altere estado (POST/PUT/PATCH/DELETE), inclusive endpoints JSON.

## Objetivos
- Evitar submissões forjadas de outros sites.
- Padronizar geração, injeção e validação de tokens em formulários e APIs.
- Garantir compatibilidade com multi-abas e acessibilidade.

## Escopo e Aplicação
- Obrigatório em todas as rotas que modificam estado.
- GET não requer token.
- Para APIs JSON, o token deve ser enviado no header `X-CSRF-Token`.

## Geração de Token
- Use `random_bytes(32)` com `bin2hex()` para gerar 64 caracteres.
- Armazene em `$_SESSION['csrf'] = ['value' => <token>, 'exp' => time()+1800]`.
- TTL padrão: 30 minutos. Expirado → regenerar.
- Regenerar em login/logout/início de sessão.

## Injeção no Formulário
- Inserir `<input type="hidden" name="_csrf" value="<token>" />` em todos os formulários.
- Não trafegar token por URL.

## Validação
- Em cada requisição que altera estado:
  1. Verificar presença de `$_POST['_csrf']` ou header `X-CSRF-Token`.
  2. Checar expiração.
  3. Comparar usando `hash_equals($sessionToken, $requestToken)`.
  4. Em falha: retornar 419 (authentication timeout) ou 403.
- Após validação bem-sucedida, pode-se rotacionar token.

## Multi-Abas
- Aceitar reutilização do token da sessão enquanto dentro do TTL.
- Para exigência de uso único, rotacionar após validação.

## Acessibilidade
- Campo oculto não interfere com leitores de tela; não usar `alert()`. Preferir `showToast()`.

## Exemplo — Helpers mínimos

```php
function csrf_token_start(): void {
    if (session_status() !== PHP_SESSION_ACTIVE) session_start();
    if (empty($_SESSION['csrf']) || $_SESSION['csrf']['exp'] < time()) {
        $_SESSION['csrf'] = [
            'value' => bin2hex(random_bytes(32)),
            'exp' => time() + 1800
        ];
    }
}
function csrf_token(): string { csrf_token_start(); return $_SESSION['csrf']['value']; }
function csrf_input(): string { return '<input type="hidden" name="_csrf" value="'.htmlspecialchars(csrf_token(),ENT_QUOTES).'">'; }
function csrf_valid(): bool {
    csrf_token_start();
    $t = $_POST['_csrf'] ?? ($_SERVER['HTTP_X_CSRF_TOKEN'] ?? null);
    if (!$t || $_SESSION['csrf']['exp'] < time()) return false;
    return hash_equals($_SESSION['csrf']['value'], (string)$t);
}
function csrf_require(): void {
    if (!csrf_valid()) { http_response_code(419); exit('CSRF inválido ou expirado'); }
}
```

## Integração em Formulários
- Dentro do `<form>`: `<?= csrf_input() ?>`
- No handler de POST: `csrf_require();`

## Integração em APIs JSON
- Enviar header `X-CSRF-Token: <token>`.
- Validar com `csrf_valid()` antes de processar.

## Requisitos Innegociáveis
- Implementação de CSRF é obrigatória antes de entregar qualquer funcionalidade que altere estado.
- Submissões sem token válido devem ser recusadas.

## Templates
- Usar o template em `regras/templates_php/estrutura_php_elemento_csrf_token.php` como base mínima.

