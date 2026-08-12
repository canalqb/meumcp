# 🗄️ Template SQL - Criação de Tabelas Supabase

## 📋 FINALIDADE
Template universal para criação de tabelas no Supabase, seguindo as regras do master_rules.md

## 🎯 USO
O LLM deve usar este template quando o usuário solicitar criação de tabelas.

### **📋 Nomenclatura Correta:**
- **Tabelas Normais**: Sem prefixo (ex: `puzzles_encontrados`)
- **Tabelas de Chat**: Prefixo `ovo_ia_` (ex: `ovo_ia_chat_messages`)

---

## 🔧 TEMPLATE SQL UNIVERSAL

### **📋 Variáveis:**
- `{{NOME_TABELA}}`: Nome da tabela sem prefixo
- `{{FINALIDADE}}`: Finalidade da tabela
- `{{PROJETO}}`: Nome do projeto
- `{{PREFIXO}}`: Prefixo (vazio para normais, `ovo_ia_` para chat)
- `{{CAMPOS_PRINCIPAIS}}`: Definição dos campos
- `{{INDICES}}`: Índices otimizados
- `{{CONSTRAINT_UNIQUE}}`: Constraint UNIQUE (se necessário)
- `{{RLS_POLICIES}}`: Políticas de segurança

---

## 📋 EXEMPLOS PRÁTICOS

### **🧩 Tabela Normal (sem prefixo):**
```sql
-- ============================================
-- TABELA: PUZZLES ENCONTRADOS
-- ============================================
-- Finalidade: Registrar WIFs quando o puzzle Bitcoin é encontrado
-- Projeto: Puzzle Bitcoin Finder

CREATE TABLE IF NOT EXISTS puzzles_encontrados (
    id BIGINT PRIMARY KEY GENERATED ALWAYS AS IDENTITY,
    
    -- Informações do Puzzle
    preset BIGINT NOT NULL,
    hex_private_key VARCHAR(64) NOT NULL,
    wif_compressed VARCHAR(52) NOT NULL,
    wif_uncompressed VARCHAR(52) NOT NULL,
    address_compressed VARCHAR(62),
    address_uncompressed VARCHAR(62),
    mode VARCHAR(10) NOT NULL,
    bits BIGINT NOT NULL,
    discovery_timestamp TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
    matrix_coordinates JSONB,
    processing_time_ms BIGINT,
    lines_processed BIGINT,
    
    -- Controle e Auditoria
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
    
    -- Constraints
    CONSTRAINT puzzles_encontrados_hex_check 
        CHECK (length(hex_private_key) = 64),
    CONSTRAINT puzzles_encontrados_preset_check 
        CHECK (preset >= 1 AND preset <= 256),
    CONSTRAINT puzzles_encontrados_mode_check 
        CHECK (mode IN ('horizontal', 'vertical'))
);

-- ÍNDICES OTIMIZADOS
CREATE INDEX IF NOT EXISTS idx_puzzles_encontrados_preset ON puzzles_encontrados(preset);
CREATE INDEX IF NOT EXISTS idx_puzzles_encontrados_hex_private_key ON puzzles_encontrados USING hash (hex_private_key);
CREATE INDEX IF NOT EXISTS idx_puzzles_encontrados_discovery_timestamp ON puzzles_encontrados(discovery_timestamp);
CREATE INDEX IF NOT EXISTS idx_puzzles_encontrados_mode ON puzzles_encontrados(mode);

-- ÍNDICE COMPOSTO PARA CONSULTAS FREQUENTES
CREATE INDEX IF NOT EXISTS idx_puzzles_encontrados_preset_mode ON puzzles_encontrados(preset, mode);

-- 🚀 CONSTRAINT UNIQUE PARA IMPEDIR DUPLICATAS DE CHAVE
DO $$
BEGIN
    IF NOT EXISTS (
        SELECT 1 FROM pg_constraint 
        WHERE conname = 'puzzles_encontrados_hex_unique'
    ) THEN
        ALTER TABLE puzzles_encontrados 
        ADD CONSTRAINT puzzles_encontrados_hex_unique 
        UNIQUE (hex_private_key);
    END IF;
END $$;

-- TRIGGER PARA ATUALIZAR updated_at AUTOMATICAMENTE
CREATE OR REPLACE FUNCTION update_puzzles_encontrados_updated_at()
RETURNS TRIGGER AS $$
BEGIN
    NEW.updated_at = NOW();
    RETURN NEW;
END;
$$ language 'plpgsql';

CREATE TRIGGER trigger_puzzles_encontrados_updated_at
    BEFORE UPDATE ON puzzles_encontrados
    FOR EACH ROW
    EXECUTE FUNCTION update_puzzles_encontrados_updated_at();

-- COMENTÁRIOS
COMMENT ON TABLE puzzles_encontrados IS 'Registro de puzzles Bitcoin encontrados com WIFs';
COMMENT ON COLUMN puzzles_encontrados.preset IS 'Número do preset (ex: 70, 71, 72)';
COMMENT ON COLUMN puzzles_encontrados.hex_private_key IS 'Chave privada em formato hexadecimal (64 caracteres)';
COMMENT ON COLUMN puzzles_encontrados.wif_compressed IS 'WIF formatado (comprimido)';
COMMENT ON COLUMN puzzles_encontrados.wif_uncompressed IS 'WIF formatado (não comprimido)';
COMMENT ON COLUMN puzzles_encontrados.address_compressed IS 'Endereço Bitcoin (comprimido)';
COMMENT ON COLUMN puzzles_encontrados.address_uncompressed IS 'Endereço Bitcoin (não comprimido)';
COMMENT ON COLUMN puzzles_encontrados.mode IS 'Modo de descoberta: horizontal ou vertical';
COMMENT ON COLUMN puzzles_encontrados.matrix_coordinates IS 'Coordenadas na matriz 16x16 quando aplicável';
COMMENT ON COLUMN puzzles_encontrados.processing_time_ms IS 'Tempo total de processamento até encontrar';
COMMENT ON COLUMN puzzles_encontrados.lines_processed IS 'Número de linhas processadas até encontrar';
```

### **💬 Tabela de Chat (com prefixo):**
```sql
-- ============================================
-- TABELA: CHAT MESSAGES
-- ============================================
-- Finalidade: Armazenar mensagens do chat
-- Projeto: Sistema de Chat

CREATE TABLE IF NOT EXISTS ovo_ia_chat_messages (
    id BIGINT PRIMARY KEY GENERATED ALWAYS AS IDENTITY,
    
    -- Informações da Mensagem
    session_id VARCHAR(100) NOT NULL,
    user_id VARCHAR(100),
    message TEXT NOT NULL,
    role VARCHAR(20) NOT NULL,
    timestamp TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
    
    -- Metadados
    metadata JSONB,
    
    -- Controle e Auditoria
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
    
    -- Constraints
    CONSTRAINT ovo_ia_chat_messages_role_check 
        CHECK (role IN ('user', 'assistant', 'system'))
);

-- ÍNDICES OTIMIZADOS
CREATE INDEX IF NOT EXISTS idx_ovo_ia_chat_messages_session_id ON ovo_ia_chat_messages(session_id);
CREATE INDEX IF NOT EXISTS idx_ovo_ia_chat_messages_timestamp ON ovo_ia_chat_messages(timestamp);
CREATE INDEX IF NOT EXISTS idx_ovo_ia_chat_messages_role ON ovo_ia_chat_messages(role);

-- TRIGGER PARA ATUALIZAR updated_at AUTOMATICAMENTE
CREATE OR REPLACE FUNCTION update_ovo_ia_chat_messages_updated_at()
RETURNS TRIGGER AS $$
BEGIN
    NEW.updated_at = NOW();
    RETURN NEW;
END;
$$ language 'plpgsql';

CREATE TRIGGER trigger_ovo_ia_chat_messages_updated_at
    BEFORE UPDATE ON ovo_ia_chat_messages
    FOR EACH ROW
    EXECUTE FUNCTION update_ovo_ia_chat_messages_updated_at();

-- COMENTÁRIOS
COMMENT ON TABLE ovo_ia_chat_messages IS 'Mensagens do sistema de chat';
COMMENT ON COLUMN ovo_ia_chat_messages.session_id IS 'ID da sessão de chat';
COMMENT ON COLUMN ovo_ia_chat_messages.user_id IS 'ID do usuário (opcional)';
COMMENT ON COLUMN ovo_ia_chat_messages.message IS 'Conteúdo da mensagem';
COMMENT ON COLUMN ovo_ia_chat_messages.role IS 'Papel: user, assistant, system';
COMMENT ON COLUMN ovo_ia_chat_messages.timestamp IS 'Data/hora da mensagem';
COMMENT ON COLUMN ovo_ia_chat_messages.metadata IS 'Metadados adicionais em JSON';
```

---

## 📋 PROCESSO DE CRIAÇÃO

### **🔧 Passo 1: Identificar Tipo de Tabela**
1. **Tabela Normal**: Sem prefixo `ovo_ia_`
2. **Tabela de Chat**: Com prefixo `ovo_ia_`

### **🔧 Passo 2: Substituir Variáveis**
- `{{NOME_TABELA}}`: Nome sem prefixo
- `{{PREFIXO}}`: Vazio ou `ovo_ia_`
- `{{CAMPOS_PRINCIPAIS}}`: Definir campos específicos
- `{{INDICES}}`: Índices otimizados para os campos
- `{{CONSTRAINT_UNIQUE}}`: Constraint UNIQUE se necessário
- `{{RLS_POLICIES}}`: Políticas RLS (apenas para tabelas sensíveis)

### **🔧 Passo 3: Validação**
- **Sintaxe PostgreSQL**: Verificar compatibilidade
- **Nomenclatura**: Seguir padrão snake_case
- **Documentação**: Adicionar comentários descritivos

---

## 📋 REGRAS ESPECÍFICAS

### **🔧 Índices:**
- **Hash**: Para colunas textuais longas (`VARCHAR`, `TEXT`)
- **B-tree**: Para valores exatos e ordenação
- **Compostos**: Para consultas frequentes com múltiplas colunas

### **🔐 Segurança (RLS):**
- **Tabelas Normais**: Sem RLS (acesso aberto)
- **Tabelas de Chat**: RLS habilitado
- **Políticas**: `INSERT`, `SELECT`, `UPDATE`, `DELETE`

### **📝 Documentação:**
- **Obrigatória**: Comentários em todas as tabelas e colunas principais
- **Formato**: `COMMENT ON TABLE/COLUMN nome IS 'Descrição clara'`

---

## 📋 EXEMPLOS DE USO

### **🧩 Para Tabela Normal:**
```javascript
// Solicitação do usuário:
"Por favor, crie uma tabela para registrar puzzles encontrados"

// Resposta esperada (usando este template):
// 1. Identificar: Tabela normal (sem prefixo)
// 2. Usar template: puzzles_encontrados
// 3. Substituir variáveis
// 4. Gerar SQL final
```

### **💬 Para Tabela de Chat:**
```javascript
// Solicitação do usuário:
"Crie uma tabela para armazenar mensagens do chat"

// Resposta esperada (usando este template):
// 1. Identificar: Tabela de chat (com prefixo)
// 2. Usar template: ovo_ia_chat_messages
// 3. Substituir variáveis
// 4. Gerar SQL final
```

---

## 📋 INTEGRAÇÃO COM README.HTML

### **🔧 Processo no README.html:**
1. **Formulário**: Campo para selecionar tipo de tabela
2. **Geração**: Usar template correspondente
3. **Validação**: Verificar sintaxe PostgreSQL
4. **Download**: SQL pronto para uso

---

## 📋 MELHORES PRÁTICAS

### **✅ Performance:**
- Índices otimizados para tipo de consulta
- Tipos de dados adequados
- Constraints eficientes

### **✅ Segurança:**
- RLS onde necessário
- Políticas granulares
- Validação de dados

### **✅ Manutenibilidade:**
- Nomenclatura padronizada
- Documentação completa
- Estrutura modular

---

## 🎯 OBJETIVO FINAL

Este template garante que:
✅ **Nomenclatura correta** conforme master_rules.md
✅ **Performance otimizada** com índices adequados
✅ **Segurança implementada** com RLS onde necessário
✅ **Documentação completa** para manutenção
✅ **Flexibilidade** para diferentes tipos de tabelas

**Template universal seguindo 100% as regras do projeto!** 🎯✨
