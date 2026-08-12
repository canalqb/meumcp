# 📚 Guia Prático: Google Apps Script + Telegram

## 🎯 Objetivo
Ensinar passo a passo como configurar e usar a integração entre Google Apps Script e o sistema Telegram via Webhook.

---

## 📋 Pré-requisitos

### ✅ O que você precisa:
1. **Conta Google** com acesso ao Google Sheets
2. **Planilha Google Sheets** (nova ou existente)
3. **Acesso ao sistema Telegram** já configurado
4. **Token do webhook** (gerado pelo sistema)

---

## 🚀 Passo a Passo Completo

### 📋 Passo 1: Criar a Planilha Google Sheets

1. **Acessar** [sheets.google.com](https://sheets.google.com)
2. **Criar nova planilha**: "Telegram Sync"
3. **Copiar o ID** da URL:
   ```
   https://docs.google.com/spreadsheets/d/1234567890abcdefghijklmnopqrstuvwxyz/edit
   ↑
   Este é o ID da planilha
   ```

### 📋 Passo 2: Abrir o Apps Script

1. **Na planilha**, ir em **Extensões > Apps Script**
2. **Apagar** o código padrão
3. **Colar** o script do template

### 📋 Passo 3: Configurar o Script

1. **Localizar** estas linhas no script:
   ```javascript
   const WEBHOOK_URL = "http://localhost/novo_airdrop/pages/admin/gas_webhook.php";
   const SHEET_ID = "SUA_PLANILHA_ID";
   const SHEET_NAME = "Telegram Data";
   ```

2. **Substituir**:
   - `SUA_PLANILHA_ID` → pelo ID copiado no passo 1
   - `WEBHOOK_URL` → pela URL do seu sistema (se necessário)

### 📋 Passo 4: Executar Setup Inicial

1. **Selecionar** função `setup` no dropdown
2. **Clicar** em **Executar**
3. **Autorizar** o script (primeira vez apenas):
   - Clique em "Revisar permissões"
   - Escolha sua conta
   - Clique em "Avançado"
   - Clique em "Acessar [nome do projeto] (não seguro)"
   - Clique em "Permitir"

4. **Aguardar** o alerta com o token gerado

### 📋 Passo 5: Obter o Token

1. **Após executar setup**, aparecerá um alerta
2. **Copie** o token mostrado
3. **Guarde** este token - será necessário para o webhook

---

## 🔧 Configuração do Webhook

### 📋 Passo 6: Configurar Token no Sistema

1. **Acessar** seu sistema: `http://localhost/novo_airdrop/pages/admin/gas_webhook.php`
2. **Inserir** o token gerado no passo anterior
3. **Salvar** a configuração

### 📋 Passo 7: Testar Conexão

1. **No Apps Script**, executar função `showConfig`
2. **Verificar** se o token aparece corretamente
3. **Testar** a URL do webhook

---

## 📊 Usando o Sistema

### 🔄 Importar Dados (Telegram → Planilha)

1. **Na planilha**, usar menu **Telegram Sync > Importar Dados**
2. **Aguardar** o processamento
3. **Verificar** os dados na aba "Telegram Data"

**O que acontece:**
- Script faz requisição GET para o webhook
- Webhook retorna dados do Telegram
- Script atualiza a planilha

### 📤 Exportar Dados (Planilha → Telegram)

1. **Editar** dados na planilha (se necessário)
2. **Usar** menu **Telegram Sync > Exportar Dados**
3. **Aguardar** confirmação

**O que acontece:**
- Script lê dados da planilha
- Envia via POST para o webhook
- Webhook atualiza o banco de dados

### 🔄 Sincronização Bidirecional

1. **Usar** menu **Telegram Sync > Sincronizar Ambos**
2. **Script** importa primeiro, depois exporta
3. **Ideal** para manter tudo sincronizado

---

## 🎯 Exemplo Prático

### 📱 Cenário: Atualizar Nome de Canal

1. **No sistema Telegram**: Canal "Crypto News" → "Crypto News BR"
2. **Na planilha**: Menu > Importar Dados
3. **Resultado**: Nome atualizado na planilha

### 📱 Cenário: Corrigir Inscritos

1. **Na planilha**: Editar coluna "Inscritos"
2. **Menu > Exportar Dados**
3. **Resultado**: Banco atualizado com novos valores

---

## ⚙️ Configurações Avançadas

### 🔄 Sincronização Automática

1. **Executar** função `createAutoSync()`
2. **Resultado**: Sincronização a cada hora
3. **Para cancelar**: Executar `removeTriggers()`

### 📊 Logs de Sincronização

1. **No banco**, consultar:
   ```sql
   SELECT * FROM w_gas_sync_log 
   WHERE created_at >= DATE_SUB(NOW(), INTERVAL 1 DAY)
   ORDER BY created_at DESC;
   ```

### 🔧 Personalização

**Mudar nome da aba:**
```javascript
const SHEET_NAME = "Meus Dados"; // Mude aqui
```

**Mudar intervalo de sincronização:**
```javascript
// Em createAutoSync(), mude para:
.everyMinutes(30)  // A cada 30 minutos
.everyHours(2)     // A cada 2 horas
.everyDays(1)      // Diariamente
```

---

## 🚨 Solução de Problemas

### ❌ "Token não configurado!"
**Solução:**
1. Execute `setup()` novamente
2. Copie o novo token
3. Configure no webhook

### ❌ "Erro na importação/exportação"
**Verifique:**
1. URL do webhook está correta?
2. Token é válido?
3. Sistema está online?

### ❌ "Permissão negada"
**Solução:**
1. Vá em Extensões > Apps Script
2. Clique em "Configurações do Projeto"
3. Desmarque "Mostrar arquivo 'appsscript.json'"
4. Salve e tente novamente

### ❌ "Dados não aparecem"
**Verifique:**
1. Se há dados no banco Telegram
2. Se a planilha está correta
3. Se os headers estão corretos

---

## 📋 Dicas de Uso

### ✅ Boas Práticas

1. **Backup**: Sempre faça backup da planilha antes de exportar
2. **Teste**: Teste com poucos dados primeiro
3. **Log**: Verifique logs de sincronização regularmente
4. **Token**: Mantenha o token seguro e compartilhe apenas com quem precisa

### 🎯 Casos de Uso

**Relatórios:**
- Importar dados para criar relatórios
- Usar fórmulas do Sheets para análise
- Exportar correções feitas na planilha

**Backup:**
- Manter cópia dos dados no Google Drive
- Histórico de alterações do Sheets
- Recuperação fácil de dados

**Colaboração:**
- Múltiplos usuários editando
- Controle de acesso do Google
- Comentários e sugestões

---

## 📊 Estrutura da Planilha

### 📋 Colunas (Aba "Telegram Data")

| Coluna | Descrição | Exemplo |
|--------|-----------|---------|
| A | ID | 123 |
| B | Nome | Crypto News BR |
| C | Link | @cryptonewsbr |
| D | Inscritos | 15000 |
| E | Última Postagem | 12345 |
| F | Data Última Postagem | 26/02/2026 20:00 |
| G | Última Mensagem | 🚀 Nova moeda... |
| H | Data Atualização | 26/02/2026 20:15 |

### 📋 Aba "Config"

| Célula | Conteúdo |
|--------|----------|
| B1 | Token do webhook |
| B2 | URL do webhook |
| B3 | Última sincronização |

---

## 🎯 Resumo Rápido

### 🔄 Fluxo Completo:
```
1. Criar planilha → Copiar ID
2. Apps Script → Colar código → Configurar ID
3. Executar setup → Obter token
4. Configurar webhook → Inserir token
5. Menu Telegram Sync → Importar/Exportar
```

### ⚡ Comandos Principais:
- `setup()` - Configuração inicial
- `importData()` - Busca dados do webhook
- `exportData()` - Envia dados para webhook
- `syncBoth()` - Sincronização bidirecional
- `showConfig()` - Mostra configuração atual

---

**Sistema pronto para uso!** 🚀

Com este guia, você consegue sincronizar dados do Telegram com Google Sheets de forma automática e segura!
