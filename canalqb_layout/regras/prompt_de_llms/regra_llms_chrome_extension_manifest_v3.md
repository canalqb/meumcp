# 🌐 Regra LLM: Chrome Extension Manifest V3 — @CanalQb

## 🎯 Objetivo
Estabelecer diretrizes obrigatórias para criação de arquivos `manifest.json` de extensões Chrome, seguindo Manifest V3 e garantindo aprovação na Chrome Web Store sem red flags.

## 📋 Regra Fundamental

### ✅ OBRIGATÓRIO
- **TODA** extensão Chrome deve usar **Manifest V3**
- **NENHUMA** chave exclusiva do MV2 pode ser usada
- **TODAS** as permissões devem seguir o princípio do menor privilégio
- **TODAS** as validações da checklist devem ser confirmadas

### ❌ PROIBIDO
- Usar Manifest V2 (descontinuado em 2024)
- Declarar permissões não utilizadas no código
- Usar `<all_urls>` em host_permissions
- Incluir campos que causam red flags

## 🔧 Campos Obrigatórios (sem estes → rejeição imediata)

### Manifest Version
```json
{
  "manifest_version": 3
}
```
- **DEVE** ser exatamente `3`
- MV2 foi descontinuado, extensões com MV2 são rejeitadas automaticamente

### Identificação
```json
{
  "name": "Nome da Extensão",
  "version": "1.0.0",
  "description": "Descrição honesta e descritiva"
}
```
- `name`: Máximo 75 caracteres, sem marcas registradas do TikTok
- `version`: Formato semver obrigatório
- `description`: Máximo 132 caracteres, avaliado pelo Google

### Ícones
```json
{
  "icons": {
    "16": "icons/icon-16.png",
    "48": "icons/icon-48.png", 
    "128": "icons/icon-128.png"
  }
}
```
- **OBRIGATÓRIO**: 16, 48 e 128px
- Sem estes → ícone padrão feio + flag na revisão

## 🔐 Permissões — Princípio do Menor Privilégio

### Permissões Permitidas (com justificativa obrigatória)
```json
{
  "permissions": [
    "activeTab",    // Acessa aba atual apenas quando usuário clica
    "scripting",    // Necessário para executeScript() no MV3
    "storage",      // chrome.storage.local para persistência
    "tabs"          // Apenas se necessário para criar abas
  ]
}
```

### Permissões Proibidas (causam rejeição imediata)
- ❌ `webRequest` - Bloqueia requisições, alto risco
- ❌ `webRequestBlocking` - Descontinuado no MV3
- ❌ `<all_urls>` - Acesso a todos os sites, red flag
- ❌ `history` - Não necessário para extração de links
- ❌ `cookies` - Não necessário
- ❌ `management` - Não necessário
- ❌ `debugger` - Red flag máximo
- ❌ `nativeMessaging` - Suspeito sem justificativa
- ❌ `unsafe-eval` - Proibido no MV3

## 🌍 Host Permissions — Escopo Mínimo

### Formato Correto
```json
{
  "host_permissions": [
    "https://www.tiktok.com/*"
  ]
}
```

### Padrões Proibidos
- ❌ `"<all_urls>"` - Causa revisão manual obrigatória
- ❌ `"https://*/*"` - Muito amplo
- ❌ `"http://*/*"` - Inseguro

## 🛠️ Background — Service Worker (obrigatório no MV3)

### Formato Correto
```json
{
  "background": {
    "service_worker": "background.js",
    "type": "module"  // Opcional, apenas se usar ES modules
  }
}
```

### Chaves Proibidas do MV2
- ❌ `background.scripts`
- ❌ `background.page`

### Regras Críticas
- Service worker **NÃO** tem acesso ao DOM
- Use `chrome.storage.local` - nunca `localStorage`
- Use `fetch()` - nunca `XMLHttpRequest()`
- Registre listeners sincronamente no topo do arquivo

## 🎯 Action — Popup e Ícones

### Formato Correto
```json
{
  "action": {
    "default_popup": "popup.html",
    "default_title": "TikTok Link Extractor",
    "default_icon": {
      "16": "icons/icon-gray-16.png",
      "48": "icons/icon-gray-48.png", 
      "128": "icons/icon-gray-128.png"
    }
  }
}
```

### Importante
- `action` substitui `browser_action` e `page_action` do MV2
- Ícones dinâmicos (verde/amarelo) são definidos via `chrome.action.setIcon()` no código

## 📄 Content Scripts

### Formato Correto
```json
{
  "content_scripts": [{
    "js": ["content.js"],
    "matches": ["https://www.tiktok.com/*"],
    "run_at": "document_idle",
    "all_frames": false
  }]
}
```

### Parâmetros
- `run_at`: `"document_idle"` (não bloqueia carregamento)
- `all_frames`: `false` (evita injeção em iframes)
- `matches`: Restrito ao domínio necessário

## 🔒 Content Security Policy

### Recomendação
```json
{
  "content_security_policy": {
    "extension_pages": "script-src 'self'; object-src 'self'"
  }
}
```

### Valores Proibidos
- ❌ `unsafe-eval`
- ❌ `unsafe-inline` para scripts
- ❌ URLs de CDN externo em script-src
- ❌ Fontes remotas não-HTTPS

### Nota
O MV3 já aplica CSP restrita por padrão. Omita este campo se não for necessário.

## 🚨 Red Flags que Causam Rejeição

### Campos Proibidos
- ❌ `update_url` - Reservado para distribuição externa
- ❌ `key` - Apenas para desenvolvimento local
- ❌ `externally_connectable` - Suspeito sem justificativa

### Padrões Proibidos
- ❌ Scripts ou recursos de URLs remotas
- ❌ `eval()` ou `new Function()` em qualquer arquivo
- ❌ Permissões declaradas mas não usadas no código
- ❌ Ícones que imitam logos de marcas registradas

## 📋 Checklist de Validação Obrigatória

### Antes de Entregar
- [ ] `manifest_version` é 3
- [ ] Nenhuma chave exclusiva do MV2 presente
- [ ] `background` usa `service_worker`
- [ ] `host_permissions` não contém `<all_urls>`
- [ ] Todas as permissões declaradas têm uso real no código
- [ ] Nenhuma URL externa em qualquer campo
- [ ] Ícones declarados nos tamanhos 16, 48 e 128
- [ ] `action` usa `default_popup` e `default_icon`
- [ ] `content_scripts` tem `matches` restrito
- [ ] `web_accessible_resources` tem matches restrito (ou omitido)
- [ ] Nenhum valor proibido na CSP
- [ ] `update_url` ausente
- [ ] `key` ausente
- [ ] `name` tem no máximo 75 caracteres
- [ ] `description` tem no máximo 132 caracteres

## 🎯 Campos Recomendados

### Version Mínima
```json
{
  "minimum_chrome_version": "88"
}
```
- MV3 requer Chrome 88+
- Demonstra responsabilidade técnica

### Homepage URL
```json
{
  "homepage_url": "https://github.com/usuario/repo"
}
```
- Aumenta confiança na revisão
- Permite verificação do código-fonte

## 📝 Estrutura do Arquivo Gerado

### Comentários Obrigatórios
```json
{
  // Manifest V3 - Chrome Extension TikTok Link Extractor
  // Segue políticas Chrome Web Store 2024+
  // Permissões mínimas necessárias
  
  "manifest_version": 3,
  // ... resto do arquivo
}
```

## 🔍 Validação Final

### Testes Obrigatórios
1. **Validação JSON**: Arquivo deve ser JSON válido
2. **Teste Local**: Instalar em modo desenvolvedor
3. **Teste de Permissões**: Verificar se todas são utilizadas
4. **Teste de Funcionalidade**: Extensão deve funcionar conforme esperado

### Ferramentas de Validação
- Chrome Extension Validator
- Lighthouse Extension Audit
- Manual Review Checklist

## 📊 Tabela de Permissões

| Permissão | Justificativa | Uso no Código |
|-----------|---------------|---------------|
| activeTab | Acesso apenas quando usuário clica | chrome.tabs.query |
| scripting | executeScript() MV3 | chrome.scripting.executeScript |
| storage | Persistência de perfis | chrome.storage.local |
| tabs | Criar novas abas | chrome.tabs.create |

## 🚀 Entregáveis Obrigatórios

1. **manifest.json completo** - Válido e funcional
2. **Comentários inline** - Explicando cada campo
3. **Checklist preenchido** - Com ✅/❌ e justificativas
4. **Tabela de permissões** - Por que incluídas e por que outras omitidas

## 🔗 Integração com Master Rules

Esta regra complementa:
- **Métricas de Qualidade**: Validação 100% obrigatória
- **Segurança**: CSP e permissões mínimas
- **Padronização**: Nomenclatura e estrutura

**ESTA REGRA É OBRIGATÓRIA E SOBREPÕE QUALQUER OUTRA PRÁTICA!**
