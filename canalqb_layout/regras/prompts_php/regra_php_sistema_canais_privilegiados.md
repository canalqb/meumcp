# 📋 Sistema de Canais Privilegiados - Documentação

## 🎯 Visão Geral

O sistema de **Canais Privilegiados** permite cadastrar manualmente canais específicos que não precisam de pontuação para permanecerem presentes no sistema de troca de inscritos. Estes canais sempre aparecerão disponíveis para troca, independentemente do saldo de pontos.

## 📁 Arquivos Criados

### 1. **Banco de Dados**
- **Arquivo:** `[RAIZ_DO_PROJETO]/sql/create_canais_privilegiados.sql`
- **Propósito:** Script SQL completo para criação da estrutura

### 2. **Interface Administrativa**
- **Arquivo:** `[RAIZ_DO_PROJETO]/pages/admin-canais-privilegiados.php`
- **Propósito:** Página de gestão para administradores

## 🗃️ Estrutura do Banco de Dados

### Tabela: `canais_privilegiados`

```sql
CREATE TABLE `canais_privilegiados` (
  `id` int(11) AUTO_INCREMENT PRIMARY KEY,
  `canal_id` varchar(100) NOT NULL,           -- ID único do YouTube
  `canal_nome` varchar(255) NOT NULL,          -- Nome do canal
  `canal_email` varchar(255),                  -- Email para contato (opcional)
  `justificativa` text NOT NULL,               -- Motivo do privilégio
  `data_cadastro` datetime DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` datetime ON UPDATE CURRENT_TIMESTAMP,
  `ativo` tinyint(1) DEFAULT 1,                -- 1=Ativo, 0=Inativo
  `prioridade` int(11) DEFAULT 0,              -- Ordem de exibição
  `destaque` tinyint(1) DEFAULT 0,             -- 1=Exibir em destaque
  `observacoes` text                           -- Informações adicionais
);
```

### Recursos Incluídos

#### ✅ **Trigger de Validação**
- Impede cadastro de canais que não existem na tabela `login`
- Garante integridade dos dados

#### ✅ **View para Consultas**
```sql
CREATE VIEW `vw_canais_privilegiados_ativos`
-- Retorna apenas canais ativos com dados completos
```

#### ✅ **Stored Procedure**
```sql
CALL sp_adicionar_canal_privilegiado(
    canal_id,
    justificativa,
    prioridade,
    destaque,
    observacoes,
    @sucesso,
    @mensagem
);
```

## 🎨 Funcionalidades da Interface Administrativa

### Cadastro
- ✅ Campo para ID do canal YouTube
- ✅ Definição de prioridade (quanto maior, primeiro aparece)
- ✅ Opção de destaque (exibe ícone especial)
- ✅ Justificativa obrigatória
- ✅ Observações opcionais

### Gestão
- ✅ Lista todos os canais privilegiados
- ✅ Exibe avatar, nome e ID do canal
- ✅ Mostra status (Ativo/Inativo)
- ✅ Permite remover privilégio
- ✅ Permite reativar privilégio
- ✅ Indicação visual de canais em destaque

## 📋 Como Instalar

### Passo 1: Executar Script SQL
```bash
# Acesse o phpMyAdmin ou MySQL
mysql -u usuario -p senha database < sql/create_canais_privilegiados.sql
```

### Passo 2: Testar Instalação
1. Acesse `/pages/admin-canais-privilegiados.php`
2. Faça login como administrador
3. Cadastre um canal de teste

### Passo 3: Integrar com Sistema de Troca
No arquivo `descobrir-canais.php`, adicione a consulta:

```php
// Busca canais privilegiados ativos
$query_privilegiados = "
    SELECT canal_id 
    FROM vw_canais_privilegiados_ativos
";
$result_privilegiados = $link->query($query_privilegiados);
$canais_privilegiados_ids = [];
while ($row = $result_privilegiados->fetch_assoc()) {
    $canais_privilegiados_ids[] = $row['canal_id'];
}

// Na hora de exibir canais, inclua os privilegiados
// Eles aparecem mesmo sem pontos
```

## 🔐 Segurança

- ✅ Apenas administradores podem acessar
- ✅ Validação de existência do canal
- ✅ Previne duplicidade
- ✅ Transações atômicas (BEGIN/COMMIT/ROLLBACK)
- ✅ Prepared statements contra SQL injection

## 🎯 Casos de Uso

### Exemplo 1: Canal Parceiro Fundador
```
ID: UCbIP9dYrKdu1va22cTWVp_Q
Nome: Luciano Souza
Justificativa: "Canal parceiro fundador da plataforma"
Prioridade: 10
Destaque: Sim
```

### Exemplo 2: Canal Patrocinado
```
ID: UCZw8aNAK7BdayyszdxsXRZw
Nome: ADRIANO ARTESS
Justificativa: "Canal patrocinado - Programa de apoio"
Prioridade: 5
Destaque: Não
```

### Exemplo 3: Canal da Equipe
```
ID: UCKMDTXQ0d4qnduldz_7VXBQ
Nome: Sala de Risos
Justificativa: "Canal oficial da equipe CanalQB"
Prioridade: 15
Destaque: Sim
```

## 📊 Consulta Avançada - Exemplo

```sql
-- Todos os canais privilegiados ativos com destaque
SELECT 
    cp.canal_nome,
    cp.canal_id,
    cp.prioridade,
    cp.justificativa,
    l.canal_subscriberCount,
    l.canal_videoCount
FROM canais_privilegiados cp
INNER JOIN login l ON cp.canal_id = l.canal_id
WHERE cp.ativo = 1 
  AND cp.destaque = 1
ORDER BY cp.prioridade DESC;
```

## 🔧 Personalização

### Alterar Prioridade
```sql
UPDATE canais_privilegiados 
SET prioridade = 20 
WHERE canal_id= 'UC_SEU_CANAL';
```

### Marcar como Destaque
```sql
UPDATE canais_privilegiados 
SET destaque = 1 
WHERE canal_id= 'UC_SEU_CANAL';
```

### Desativar Privilégio
```sql
UPDATE canais_privilegiados 
SET ativo = 0 
WHERE canal_id = 'UC_SEU_CANAL';
```

## 🚨 Regras de Negócio

1. **Obrigatório**: Canal deve existir na tabela `login`
2. **Único**: Um canal não pode ter privilégio duplicado
3. **Automático**: Reativação atualiza registro existente
4. **Validação**: Trigger verifica existência antes de inserir
5. **Hierarquia**: Maior prioridade = exibido primeiro

## 📝 Logs e Auditoria

A tabela inclui campos automáticos:
- `data_cadastro`: Quando o privilégio foi criado
- `data_atualizacao`: Última modificação
- Mantém histórico mesmo quando inativo (`ativo = 0`)

## 🎯 Integração Futura

Sugestões para desenvolvimento:
- [ ] Adicionar data de expiração do privilégio
- [ ] Sistema de renovação automática
- [ ] Notificações por email
- [ ] Estatísticas de desempenho
- [ ] Limites de trocas por período
- [ ] Categorias de privilégio (Ouro, Prata, Bronze)

## ⚠️ Importante

- Use com moderação para não desequilibrar o sistema
- Sempre documente a justificativa
- Revise periodicamente os canais ativos
- Remova privilégios de canais inativos

---

**Versão:** 1.0  
**Data:** 2026-03-09  
**Autor:** @CanalQb Team
