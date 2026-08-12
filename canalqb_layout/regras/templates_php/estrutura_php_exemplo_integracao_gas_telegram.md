# 🤖 Template: Integração Google Apps Script + Telegram

## 📋 Objetivo
Criar um sistema completo para sincronizar dados do Telegram com Google Sheets via Webhook, permitindo importação e exportação de dados em tempo real.

---

## 🏗️ Estrutura do Sistema

### 📊 Tabela para GAS
```sql
-- Tabela de sincronização com Google Apps Script
CREATE TABLE IF NOT EXISTS w_gas_sync (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sheet_id VARCHAR(100) NOT NULL COMMENT 'ID da planilha Google',
    sheet_name VARCHAR(100) NOT NULL COMMENT 'Nome da aba',
    webhook_token VARCHAR(64) NOT NULL UNIQUE COMMENT 'Token de segurança',
    sync_direction ENUM('import', 'export', 'both') DEFAULT 'both',
    last_sync TIMESTAMP NULL COMMENT 'Última sincronização',
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_token (webhook_token),
    INDEX idx_sheet (sheet_id)
);

-- Tabela de log de sincronização
CREATE TABLE IF NOT EXISTS w_gas_sync_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sync_id INT NOT NULL,
    operation ENUM('import', 'export') NOT NULL,
    records_count INT DEFAULT 0,
    status ENUM('success', 'error') NOT NULL,
    message TEXT NULL,
    data JSON NULL COMMENT 'Dados processados',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_sync (sync_id),
    INDEX idx_created (created_at),
    FOREIGN KEY (sync_id) REFERENCES w_gas_sync(id) ON DELETE CASCADE
);
```

### 🔌 Webhook Endpoint
```php
<?php
// pages/admin/gas_webhook.php
require_once '../../config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

try {
    $token = $_SERVER['HTTP_AUTHORIZATION'] ?? $_GET['token'] ?? $_POST['token'] ?? '';
    $token = str_replace('Bearer ', '', $token);
    
    if (empty($token)) {
        http_response_code(401);
        echo json_encode(['error' => 'Token não fornecido']);
        exit;
    }
    
    // Verificar token na tabela
    $stmt = $pdo->prepare("SELECT * FROM w_gas_sync WHERE webhook_token = ? AND status = 'active'");
    $stmt->execute([$token]);
    $sync = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$sync) {
        http_response_code(401);
        echo json_encode(['error' => 'Token inválido']);
        exit;
    }
    
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        // Exportar dados para GAS
        $data = [];
        
        // Telegram data
        $stmt = $pdo->prepare("SELECT t.id, t.nome, t.link, t.inscritos, t.id_ultimapostagem, 
                              t.dataultimapostagemvisualizada, ta.ultima_mensagem, ta.data_atualizacao
                              FROM telegram_airdrop t 
                              LEFT JOIN telegram_atualizacao ta ON t.id = ta.id_telegram
                              WHERE t.id_ultimapostagem IS NOT NULL 
                              ORDER BY t.verificado DESC");
        $stmt->execute();
        $data['telegram'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Webhooks data
        $stmt = $pdo->prepare("SELECT name, active, created_at FROM w_web WHERE active = 1");
        $stmt->execute();
        $data['webhooks'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Log da operação
        $log_stmt = $pdo->prepare("INSERT INTO w_gas_sync_log (sync_id, operation, records_count, status, data) 
                                   VALUES (?, 'export', ?, 'success', ?)");
        $log_stmt->execute([$sync['id'], count($data['telegram']) + count($data['webhooks']), json_encode($data)]);
        
        // Atualizar última sincronização
        $pdo->prepare("UPDATE w_gas_sync SET last_sync = NOW() WHERE id = ?")->execute([$sync['id']]);
        
        echo json_encode([
            'success' => true,
            'data' => $data,
            'timestamp' => date('Y-m-d H:i:s'),
            'sync_id' => $sync['id']
        ]);
        
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Importar dados do GAS
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input) {
            http_response_code(400);
            echo json_encode(['error' => 'Dados inválidos']);
            exit;
        }
        
        $records_count = 0;
        $errors = [];
        
        // Processar Telegram updates
        if (isset($input['telegram']) && is_array($input['telegram'])) {
            foreach ($input['telegram'] as $item) {
                try {
                    $stmt = $pdo->prepare("UPDATE telegram_airdrop SET nome = ?, inscritos = ?, 
                                          id_ultimapostagem = ?, dataultimapostagemvisualizada = ?,
                                          verificado = NOW() WHERE id = ?");
                    $stmt->execute([
                        $item['nome'] ?? null,
                        $item['inscritos'] ?? null,
                        $item['id_ultimapostagem'] ?? null,
                        $item['dataultimapostagemvisualizada'] ?? null,
                        $item['id']
                    ]);
                    $records_count++;
                } catch (Exception $e) {
                    $errors[] = "Erro ao atualizar Telegram ID {$item['id']}: " . $e->getMessage();
                }
            }
        }
        
        // Log da operação
        $log_data = [
            'input' => $input,
            'processed' => $records_count,
            'errors' => $errors
        ];
        
        $log_stmt = $pdo->prepare("INSERT INTO w_gas_sync_log (sync_id, operation, records_count, status, message, data) 
                                   VALUES (?, 'import', ?, ?, ?, ?)");
        $log_stmt->execute([
            $sync['id'], 
            $records_count, 
            empty($errors) ? 'success' : 'error',
            empty($errors) ? 'Importação concluída' : 'Erros encontrados',
            json_encode($log_data)
        ]);
        
        // Atualizar última sincronização
        $pdo->prepare("UPDATE w_gas_sync SET last_sync = NOW() WHERE id = ?")->execute([$sync['id']]);
        
        echo json_encode([
            'success' => true,
            'message' => "Importação concluída: $records_count registros processados",
            'processed' => $records_count,
            'errors' => $errors,
            'timestamp' => date('Y-m-d H:i:s')
        ]);
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erro interno: ' . $e->getMessage()]);
}
?>
```

---

## 📝 Google Apps Script

### 🔧 Script Principal
```javascript
// gas_telegram_integration.gs
const WEBHOOK_URL = "http://localhost/novo_airdrop/pages/admin/gas_webhook.php";
const SHEET_ID = "SUA_PLANILHA_ID";
const SHEET_NAME = "Telegram Data";

// Configuração
function setup() {
  const token = generateToken();
  const sheet = SpreadsheetApp.openById(SHEET_ID);
  const configSheet = sheet.getSheetByName("Config");
  
  if (!configSheet) {
    sheet.insertSheet("Config");
    configSheet = sheet.getSheetByName("Config");
  }
  
  // Salvar configuração
  configSheet.getRange("A1:B1").setValues([["Webhook Token", token]]);
  configSheet.getRange("A2:B2").setValues([["Webhook URL", WEBHOOK_URL]]);
  configSheet.getRange("A3:B3").setValues([["Last Sync", ""]]);
  
  // Criar menu
  SpreadsheetApp.getUi()
    .createMenu('Telegram Sync')
    .addItem('Importar Dados', 'importData')
    .addItem('Exportar Dados', 'exportData')
    .addItem('Sincronizar Ambos', 'syncBoth')
    .addSeparator()
    .addItem('Configurar Token', 'showConfig')
    .addToUi();
  
  // Preparar aba de dados
  prepareDataSheet();
  
  SpreadsheetApp.getUi().alert('Configuração concluída! Token: ' + token);
}

// Gerar token seguro
function generateToken() {
  const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
  let token = '';
  for (let i = 0; i < 64; i++) {
    token += chars.charAt(Math.floor(Math.random() * chars.length));
  }
  return token;
}

// Preparar aba de dados
function prepareDataSheet() {
  const sheet = SpreadsheetApp.openById(SHEET_ID);
  let dataSheet = sheet.getSheetByName(SHEET_NAME);
  
  if (!dataSheet) {
    dataSheet = sheet.insertSheet(SHEET_NAME);
  }
  
  // Headers
  const headers = [
    'ID', 'Nome', 'Link', 'Inscritos', 'Última Postagem', 
    'Data Última Postagem', 'Última Mensagem', 'Data Atualização'
  ];
  dataSheet.getRange(1, 1, 1, headers.length).setValues([headers]);
  
  // Formatação
  dataSheet.autoResizeColumn(1, 8);
  dataSheet.setFrozenRows(1);
}

// Importar dados do webhook
function importData() {
  const token = getConfigToken();
  if (!token) {
    SpreadsheetApp.getUi().alert('Token não configurado!');
    return;
  }
  
  try {
    const response = UrlFetchApp.fetch(WEBHOOK_URL + "?token=" + token, {
      method: "GET",
      headers: {"Authorization": "Bearer " + token},
      muteHttpExceptions: true
    });
    
    const result = JSON.parse(response.getContentText());
    
    if (result.success) {
      updateSheetWithData(result.data);
      updateLastSync();
      SpreadsheetApp.getUi().alert('Dados importados com sucesso!');
    } else {
      SpreadsheetApp.getUi().alert('Erro na importação: ' + result.error);
    }
  } catch (e) {
    SpreadsheetApp.getUi().alert('Erro: ' + e.toString());
  }
}

// Exportar dados para webhook
function exportData() {
  const token = getConfigToken();
  if (!token) {
    SpreadsheetApp.getUi().alert('Token não configurado!');
    return;
  }
  
  try {
    const sheetData = getSheetData();
    
    const response = UrlFetchApp.fetch(WEBHOOK_URL + "?token=" + token, {
      method: "POST",
      headers: {
        "Authorization": "Bearer " + token,
        "Content-Type": "application/json"
      },
      payload: JSON.stringify(sheetData),
      muteHttpExceptions: true
    });
    
    const result = JSON.parse(response.getContentText());
    
    if (result.success) {
      updateLastSync();
      SpreadsheetApp.getUi().alert('Dados exportados com sucesso! Registros: ' + result.processed);
    } else {
      SpreadsheetApp.getUi().alert('Erro na exportação: ' + result.error);
    }
  } catch (e) {
    SpreadsheetApp.getUi().alert('Erro: ' + e.toString());
  }
}

// Sincronização bidirecional
function syncBoth() {
  importData();
  Utilities.sleep(1000); // Pequena pausa
  exportData();
}

// Obter dados da planilha
function getSheetData() {
  const sheet = SpreadsheetApp.openById(SHEET_ID).getSheetByName(SHEET_NAME);
  const data = sheet.getDataRange().getValues();
  
  // Pular header
  const rows = data.slice(1).filter(row => row[0]); // Filtrar linhas com ID
  
  return {
    telegram: rows.map(row => ({
      id: row[0],
      nome: row[1],
      link: row[2],
      inscritos: row[3],
      id_ultimapostagem: row[4],
      dataultimapostagemvisualizada: row[5],
      ultima_mensagem: row[6],
      data_atualizacao: row[7]
    }))
  };
}

// Atualizar planilha com dados
function updateSheetWithData(data) {
  const sheet = SpreadsheetApp.openById(SHEET_ID).getSheetByName(SHEET_NAME);
  
  // Limpar dados antigos (exceto header)
  sheet.getRange(2, 1, sheet.getMaxRows() - 1, sheet.getMaxColumns()).clearContent();
  
  // Adicionar dados do Telegram
  if (data.telegram && data.telegram.length > 0) {
    const telegramRows = data.telegram.map(item => [
      item.id,
      item.nome || '',
      item.link || '',
      item.inscritos || '',
      item.id_ultimapostagem || '',
      item.dataultimapostagemvisualizada || '',
      item.ultima_mensagem || '',
      item.data_atualizacao || ''
    ]);
    
    sheet.getRange(2, 1, telegramRows.length, telegramRows[0].length).setValues(telegramRows);
  }
  
  sheet.autoResizeColumn(1, 8);
}

// Obter token da configuração
function getConfigToken() {
  const sheet = SpreadsheetApp.openById(SHEET_ID).getSheetByName("Config");
  if (!sheet) return null;
  
  const tokenRange = sheet.getRange("B1");
  return tokenRange.getValue();
}

// Atualizar última sincronização
function updateLastSync() {
  const sheet = SpreadsheetApp.openById(SHEET_ID).getSheetByName("Config");
  if (sheet) {
    sheet.getRange("B3").setValue(new Date().toLocaleString());
  }
}

// Mostrar configuração
function showConfig() {
  const token = getConfigToken();
  const message = token ? 
    `Token: ${token}\nURL: ${WEBHOOK_URL}` : 
    'Token não configurado. Execute setup() primeiro.';
  
  SpreadsheetApp.getUi().alert(message);
}

// Trigger automático (opcional)
function createAutoSync() {
  // Criar trigger para sincronização automática a cada hora
  ScriptApp.newTrigger('syncBoth')
    .timeBased()
    .everyHours(1)
    .create();
}

// Limpar triggers
function removeTriggers() {
  const triggers = ScriptApp.getProjectTriggers();
  triggers.forEach(trigger => ScriptApp.deleteTrigger(trigger));
}
```

---

## 🎯 Como Usar

### 📋 Passo 1: Configurar Banco de Dados
```sql
-- Executar as queries SQL para criar as tabelas
CREATE TABLE IF NOT EXISTS w_gas_sync (...);
CREATE TABLE IF NOT EXISTS w_gas_sync_log (...);
```

### 📋 Passo 2: Criar Webhook
1. Copiar o código `gas_webhook.php` para `pages/admin/`
2. Acessar: `http://localhost/novo_airdrop/pages/admin/gas_webhook.php`
3. Gerar token via interface

### 📋 Passo 3: Configurar Google Apps Script
1. Criar nova planilha Google Sheets
2. Abrir Apps Script (Extensões > Apps Script)
3. Copiar o script `gas_telegram_integration.gs`
4. Substituir `SUA_PLANILHA_ID` pelo ID real
5. Executar `setup()` uma vez

### 📋 Passo 4: Sincronizar Dados
1. Na planilha, usar menu "Telegram Sync"
2. Opções disponíveis:
   - **Importar Dados**: Busca dados do webhook
   - **Exportar Dados**: Envia dados para webhook
   - **Sincronizar Ambos**: Importa depois exporta

---

## 🔧 Configurações Avançadas

### ⚙️ Variáveis de Ambiente
```javascript
// No GAS, personalizar estas constantes
const WEBHOOK_URL = "http://seusite.com/pages/admin/gas_webhook.php";
const SHEET_ID = "1234567890abcdefghijklmnopqrstuvwxyz"; // ID da planilha
const SHEET_NAME = "Telegram Data"; // Nome da aba
```

### 🔄 Sincronização Automática
```javascript
// Para sincronização automática, executar:
createAutoSync(); // Cria trigger a cada hora
removeTriggers(); // Remove todos os triggers
```

### 📊 Logs de Sincronização
```php
// Consultar logs no banco
SELECT * FROM w_gas_sync_log 
WHERE sync_id = 1 
ORDER BY created_at DESC 
LIMIT 10;
```

---

## 🚀 Funcionalidades

### ✅ Importação
- Busca dados do Telegram do banco
- Formata para planilha
- Atualiza timestamp

### ✅ Exportação
- Lê dados da planilha
- Envia para webhook
- Atualiza banco

### ✅ Logs
- Registra todas as operações
- Armazena erros e sucessos
- Dados em JSON para análise

### ✅ Segurança
- Token único por sincronização
- Validação de requisições
- CORS configurado

---

## 📱 Interface de Gerenciamento

### 📊 Painel de Sincronização
```php
// pages/admin/gas_sync_manager.php
<?php
require_once '../../config.php';

// Listar sincronizações ativas
$stmt = $pdo->query("SELECT * FROM w_gas_sync WHERE status = 'active'");
$syncs = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Exibir interface com botões de ação
foreach ($syncs as $sync) {
    echo "<div class='card'>";
    echo "<h5>Sheet: {$sync['sheet_name']}</h5>";
    echo "<p>Última sincronização: {$sync['last_sync']}</p>";
    echo "<button onclick='testSync({$sync['id']})'>Testar</button>";
    echo "</div>";
}
?>
```

---

## 📋 Checklist de Implementação

- [ ] Criar tabelas `w_gas_sync` e `w_gas_sync_log`
- [ ] Implementar webhook `gas_webhook.php`
- [ ] Configurar Google Apps Script
- [ ] Testar importação de dados
- [ ] Testar exportação de dados
- [ ] Configurar sincronização automática
- [ ] Implementar interface de gerenciamento
- [ ] Documentar uso para equipe

---

## 🎯 Benefícios

### ✅ Sincronização em Tempo Real
- Dados sempre atualizados
- Bidirecional (import/export)
- Logs completos

### ✅ Automação
- Triggers automáticos
- Menu intuitivo
- Configuração simples

### ✅ Segurança
- Tokens únicos
- Validação rigorosa
- Logs de auditoria

---

**Template completo para integração GAS + Telegram!** 🚀
