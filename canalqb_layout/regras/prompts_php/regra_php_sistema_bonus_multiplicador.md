# 🎯 Sistema de Bônus Multiplicador - Documentação

## 📋 Visão Geral

O sistema de **Bônus Multiplicador** permite configurar multiplicadores de pontos (2x, 3x, 4x) para canais específicos. Este sistema é independente dos canais privilegiados e foca em **bonificar usuários** que interagem com canais estratégicos.

### Diferença entre os Sistemas:

| **Canais Privilegiados** | **Canais com Bônus** |
|--------------------------|----------------------|
| Não precisam de pontos para existir | Precisam de pontos normalmente |
| Sempre aparecem no sistema | Aparecem normalmente |
| Foco: Permanência no sistema | Foco: Bonificar usuários|
| Sem controle de data | Pode ter data de expiração|
| **Multiplicadores:** | **Multiplicadores:** |
| - Não possui | - 2x: Promoção/Impulso |
| - | - 3x: Canal Parceiro |
| - | - 4x: Canal Próprio |

## 🗃️ Estrutura do Banco de Dados

### Tabela: `canais_bonus_multiplicador`

```sql
CREATE TABLE `canais_bonus_multiplicador` (
  `id` int(11) AUTO_INCREMENT PRIMARY KEY,
  `canal_id` varchar(100) NOT NULL,
  `canal_nome` varchar(255) NOT NULL,
  `multiplicador` decimal(3,1) NOT NULL DEFAULT '2.0',
  `tipo_bonus` enum('PROPRIO','PARCEIRO','PROMOCAO') NOT NULL,
  `justificativa` text NOT NULL,
  `data_inicio` datetime DEFAULT CURRENT_TIMESTAMP,
  `data_fim` datetime DEFAULT NULL,
  `data_cadastro` datetime DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` datetime ON UPDATE CURRENT_TIMESTAMP,
  `ativo` tinyint(1) DEFAULT 1,
  `observacoes` text
);
```

### Recursos Incluídos

#### ✅ **Triggers de Validação**
- Valida existência do canal na tabela `login`
- Valida multiplicador (apenas 2.0, 3.0 ou 4.0)
- Atualização também valida multiplicador

#### ✅ **Views para Consultas**
```sql
-- Canais bônus ativos
vw_canais_bonus_ativos

-- Cálculo de pontos com bônus
vw_calculo_pontos_bonus
```

#### ✅ **Stored Procedures**
```sql
-- Gerenciar canal bônus(adicionar/atualizar)
CALL sp_gerenciar_canal_bonus(...)

-- Calcular pontos com bônus
CALL sp_calcular_pontos_com_bonus(...)
```

## 🎨 Funcionalidades da Interface Administrativa

### Configuração
- ✅ Seletor de multiplicador (2x, 3x, 4x)
- ✅ Tipo de bônus (Próprio, Parceiro, Promoção)
- ✅ Data de início e fim (opcional)
- ✅ Justificativa obrigatória
- ✅ Observações opcionais

### Dashboard
- ✅ Estatísticas em tempo real
- ✅ Contagem por tipo de bônus
- ✅ Contagem por multiplicador
- ✅ Lista completa com status

### Gestão
- ✅ Exibe avatar e nome do canal
- ✅ Badge visual do multiplicador
- ✅ Status (Ativo/Inativo/Expirado)
- ✅ Botões para remover/reativar
- ✅ Data de expiração visível

## 🚀 Como Instalar

### Passo 1: Executar Script SQL
```bash
mysql -u usuario-p senha database < sql/create_canais_bonus_multiplicador.sql
```

### Passo 2: Acessar Interface
```
URL: http://seusite/pages/admin-canais-bonus.php
Login: administrador
```

### Passo 3: Configurar Canais

#### Exemplo 1: Seu Canal Principal (4x)
```
ID: UC_SEU_CANAL_PRINCIPAL
Multiplicador: 4x
Tipo: Próprio
Justificativa: Canal oficial do administrador
Data Fim: (em branco - permanente)
```

#### Exemplo 2: Canal Parceiro (3x)
```
ID: UCbIP9dYrKdu1va22cTWVp_Q
Multiplicador: 3x
Tipo: Parceiro
Justificativa: Canal parceiro fundador
Data Fim: (em branco - permanente)
```

#### Exemplo 3: Canal em Promoção (2x)
```
ID: UCZw8aNAK7BdayyszdxsXRZw
Multiplicador: 2x
Tipo: Promoção
Justificativa: Impulsionando canal com menos views
Data Fim: 2026-04-09 23:59:59 (30 dias)
```

## 🔧 Integração no Sistema de Pontos

### Arquivos que Precisam ser Modificados:

#### 1. **processar-inscricao.php**
```php
// ANTES (código original):
$pontos_ganhos = $config_pontos['SC']; // 2 pontos

// DEPOIS (com bônus):
require_once './config/calcular-pontos-bonus.php';
$pontos_ganhos = calcular_pontos_com_bonus($canal_id, 'SC');
// Retorna: 2 * multiplicador (ex: 2 * 3 = 6 pontos)
```

#### 2. **processar-visualizacao.php**
```php
// ANTES:
$pontos = $config_pontos['AV']; // 2 pontos

// DEPOIS:
require_once './config/calcular-pontos-bonus.php';
$pontos = calcular_pontos_com_bonus($canal_id, 'AV');
// Retorna: 2 * multiplicador
```

#### 3. **deixar-de-seguir.php** (criar se não existir)
```php
// Quando usuário deixa de seguir
require_once './config/calcular-pontos-bonus.php';
$pontos_perdidos = calcular_pontos_com_bonus($canal_id, 'DC');
// Retorna: -4 * multiplicador (ex: -4 * 3 = -12 pontos)
```

### Criar Arquivo de Helper:

**Arquivo:** `[RAIZ_DO_PROJETO]/config/calcular-pontos-bonus.php`

```php
<?php
/**
 * Calcula pontos com multiplicador de bônus
 * 
 * @param string $canal_id ID do canal
 * @param string $tipo_ponto Tipo de ponto (LI, SC, AV, DC, etc.)
 * @return int Valor final com multiplicador
 */
function calcular_pontos_com_bonus($canal_id, $tipo_ponto) {
    global $link;
    
    // Busca valor base
    $stmt = $link->prepare("SELECT Valor FROM tiposdepontos WHERE Sigla = ?");
    $stmt->bind_param("s", $tipo_ponto);
    $stmt->execute();
    $valor_base = (int)$stmt->get_result()->fetch_assoc()['Valor'];
    $stmt->close();
    
    // Busca multiplicador do canal
    $stmt = $link->prepare("
        SELECT multiplicador 
        FROM canais_bonus_multiplicador 
        WHERE canal_id = ? 
          AND ativo = 1
          AND (data_fim IS NULL OR data_fim > NOW())
        LIMIT 1
    ");
    $stmt->bind_param("s", $canal_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $multiplicador = 1.0; // Padrão sem bônus
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $multiplicador = floatval($row['multiplicador']);
    }
    $stmt->close();
    
    // Retorna valor com multiplicador
    return round($valor_base * $multiplicador);
}
?>
```

## 📊 Regras de Negócio

### Multiplicadores por Tipo de Ação:

| Ação | Código | Valor Base | Com 2x | Com 3x | Com 4x |
|------|--------|------------|--------|--------|--------|
| Like/Inscrição | LI | 2 pts | 4 pts | 6 pts | 8 pts |
| Inscrição Recebida | SC | 2 pts | 4 pts | 6 pts | 8 pts |
| Vídeo Assistido | AV | 2 pts | 4 pts | 6 pts | 8 pts |
| Deixou de Seguir | DC | -4 pts | -8 pts | -12 pts | -16 pts |
| Cadastro | CI | 10 pts | 20 pts | 30 pts | 40 pts |
| Invite Usuário | IU | 10 pts | 20 pts | 30 pts | 40 pts |

### Fluxo de Pontos:

#### Inscrição em Canal com Bônus:
```
1. Usuário se inscreve no canal
2. Sistema verifica se canal tem multiplicador
3. Se tiver: pontos = base × multiplicador
4. Se não tiver: pontos = base (1x)
5. Registra na tabela pontosdocanal
```

#### Deixar de Seguir Canal com Bônus:
```
1. Usuário deixa de seguir canal
2. Sistema verifica se canal tem multiplicador
3. Se tiver: pontos = base × multiplicador (negativo)
4. Se não tiver: pontos = base (negativo)
5. Atualiza na tabela pontosdocanal
```

#### Assistir Vídeo com Bônus:
```
1. Usuário assiste vídeo completo
2. Sistema verifica multiplicador do canal
3. Adiciona pontos = base × multiplicador
4. Registra como tipo AV (Assistiu Vídeo)
```

## ⚠️ Importante

### Válidas Aplicadas:
- ✅ Trigger impede multiplicador inválido (apenas 2.0, 3.0, 4.0)
- ✅ Canal deve existir na tabela `login`
- ✅ Bônus expira automaticamente na data definida
- ✅ Um canal pode ter apenas um multiplicador ativo por vez

### Boas Práticas:
- 📝 Sempre justifique o multiplicador
- 📅 Defina data de fim para promoções temporárias
- 🔍 Revise periodicamente os bônus ativos
- 📊 Monitore o impacto na economia de pontos

## 🔍 Consultas Úteis

### Ver Todos os Canais com Bônus Ativo:
```sql
SELECT * FROM vw_canais_bonus_ativos;
```

### Ver Apenas Canais 4x (Próprios):
```sql
SELECT * FROM vw_canais_bonus_ativos 
WHERE multiplicador = 4.0 AND ativo_real = 1;
```

### Ver Bônus Expirando em Breve:
```sql
SELECT * FROM vw_canais_bonus_ativos 
WHERE data_fim BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 7 DAY);
```

### Calcular Pontos para uma Ação:
```sql
CALL sp_calcular_pontos_com_bonus(
    'UC_CANAL',
    'LI',        -- Tipo de ponto
    'UC_USUARIO',
    @base,
    @multi,
    @final
);
SELECT @base as Base, @multi as Multiplicador, @final as Final;
```

## 📈 Exemplos Práticos

### Cenário 1: Seu Canal Oficial (4x)
```sql
CALL sp_gerenciar_canal_bonus(
    'UC_SEU_CANAL',
   4.0,
    'PROPRIO',
    'Canal oficial do dono da plataforma',
    NULL,  -- Permanente
    '',
    @sucesso,
    @mensagem
);

-- Resultado:
-- Inscrição: 2 × 4 = 8 pontos
-- Vídeo: 2 × 4 = 8 pontos
-- Deixar de seguir: -4 × 4 = -16 pontos
```

### Cenário 2: Canal Parceiro Estratégico (3x)
```sql
CALL sp_gerenciar_canal_bonus(
    'UC_PARCEIRO',
    3.0,
    'PARCEIRO',
    'Parceiro fundador - programa especial',
    NULL,
    '',
    @sucesso,
    @mensagem
);

-- Resultado:
-- Inscrição: 2 × 3 = 6 pontos
-- Vídeo: 2 × 3 = 6 pontos
-- Deixar de seguir: -4 × 3 = -12 pontos
```

### Cenário 3: Promoção Temporária (2x por 30 dias)
```sql
CALL sp_gerenciar_canal_bonus(
    'UC_PROMOCAO',
    2.0,
    'PROMOCAO',
    'Impulsionamento inicial -30 dias',
    DATE_ADD(NOW(), INTERVAL 30 DAY),
    '',
    @sucesso,
    @mensagem
);

-- Resultado:
-- Inscrição: 2 × 2 = 4 pontos
-- Vídeo: 2 × 2 = 4 pontos
-- Deixar de seguir: -4 × 2 = -8 pontos
-- Expira automaticamente em 30 dias
```

## 🎯 Casos de Uso

### Para Você (Administrador):
- **Seus canais principais**: 4x
- **Canais secundários**: 3x ou 4x
- **Projetos especiais**: 3x

### Para Parceiros:
- **Parceiros fundadores**: 3x
- **Parceiros estratégicos**: 3x
- **Colaborações**: 2x ou 3x

### Para Promoções:
- **Lançamento de canal**: 2x por30 dias
- **Canais com poucas views**: 2x
- **Eventos especiais**: 3x por período limitado

## 🔄 Atualizações Futuras Sugeridas

- [ ] Notificação automática quando bônus estiver para expirar
- [ ] Relatórios de impacto na economia de pontos
- [ ] Limite máximo de canais com bônus por tipo
- [ ] Histórico de mudanças de multiplicador
- [ ] Aprovação necessária para bônus > 2x
- [ ] Integração com sistema de notificações

---

**Versão:** 1.0  
**Data:** 2026-03-09  
**Autor:** @CanalQb Team
