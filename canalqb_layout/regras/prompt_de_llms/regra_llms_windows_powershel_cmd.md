# 📜 REGRA LLM — Mover Arquivos no Windows

## 🎯 Objetivo
Estabelecer padrões e regras para mover arquivos no ambiente Windows, evitando erros comuns e garantindo consistência.

---

## 📋 Detectar o Shell

### 🔍 PowerShell vs CMD
```powershell
# Detectar qual shell está em uso
$shell = $PSVersionTable.PSVersion.Major

if ($shell) {
    Write-Host "🔹 PowerShell detectado"
    # Usar comandos PowerShell
} else {
    Write-Host "💻 CMD detectado"
    # Usar comandos CMD
}
```

---

## 🔄 Mover Arquivos para `solucoes/`

### ✅ PowerShell (Recomendado)
```powershell
# Mover arquivo único
Move-Item -Path "arquivo_origem.php" -Destination "solucoes\destino\" -Force

# Mover múltiplos arquivos
Move-Item -Path "setup_*.php" -Destination "solucoes\setup\" -Force

# Mover com wildcard (todos os setup*.php)
Move-Item -Path "setup*.php" -Destination "solucoes\setup\" -Force
```

### 💻 CMD (Alternativa)
```cmd
# Mover arquivo único
move /Y arquivo_origem.php solucoes\destino\

# Mover múltiplos arquivos
move /Y setup_*.php solucoes\setup\

# Mover com wildcard
move /Y setup*.php solucoes\setup\
```

---

## ⚠️ Verificação de Conflitos

### 🔍 Antes de Mover
```powershell
# Verificar se arquivo origem existe
$origemExiste = Test-Path "arquivo_origem.php"

# Verificar se destino existe
$destinoExiste = Test-Path "solucoes\destino"

# Verificar se é diretório
$destinoEhDiretorio = Test-Path "solucoes\destino" -PathType Container

if ($destinoExiste -and $destinoEhDiretorio) {
    Write-Host "⚠️ Conflito: diretório de destino já existe"
    # Implementar lógica de tratamento
}
```

### 🛡️ Tratamento de Erros

### 📋 Códigos de Erro Comuns
| Erro | Causa | Solução |
|------|--------|---------|
| PathNotFound | Arquivo não encontrado | Verificar caminho |
| DirectoryExists | Diretório já existe | Usar -Force |
| AccessDenied | Sem permissão | Executar como admin |
| IOException | Erro de I/O | Verificar disco |

---

## 🎯 Boas Práticas

### ✅ Sempre Validar
1. **Existência do arquivo origem**
2. **Permissões de escrita no destino**
3. **Camininhos absolutos**
4. **Backup antes de mover arquivos críticos**

### 📝 Padrão de Comando
```powershell
# Script completo de verificação e movimentação
function Mover-ArquivosSetup {
    param(
        [string[]]$arquivos = @("setup_*.php"),
        [string]$destino = "solucoes\setup"
    )
    
    Write-Host "🔍 Verificando arquivos..."
    
    foreach ($arquivo in $arquivos) {
        if (Test-Path $arquivo) {
            Write-Host "✅ Encontrado: $arquivo"
            
            # Verificar se destino existe
            if (!(Test-Path $destino)) {
                New-Item -ItemType Directory -Path $destino -Force
            }
            
            try {
                Move-Item -Path $arquivo -Destination $destino -Force
                Write-Host "📁 Movido: $arquivo → $destino"
            } catch {
                Write-Host "❌ Erro ao mover $arquivo: $_"
            }
        } else {
            Write-Host "⚠️ Não encontrado: $arquivo"
        }
    }
    
    Write-Host "🎉 Operação concluída!"
}

# Executar
Mover-ArquivosSetup
```

---

## 🔄 Fluxo de Decisão

### 📋 Quando Arquivo Já Existe no Destino

1. **Verificar se é o mesmo arquivo**
2. **Comparar datas de modificação**
3. **Perguntar ao usuário (se interativo)**
4. **Criar backup com timestamp**
5. **Mover com nome alternativo**

### 📝 Exemplo de Tratamento
```powershell
if (Test-Path $destino) {
    $dataOrigem = (Get-Item $origem).LastWriteTime
    $dataDestino = (Get-Item $destino).LastWriteTime
    
    if ($dataDestino -gt $dataOrigem) {
        Write-Host "⚠️ Destino mais recente que origem"
        $resposta = Read-Host "Arquivo já existe. Deseja [S]obrescrever, [R]enomear, [C]ancelar? "
        
        switch ($resposta.ToUpper()) {
            "S" { Move-Item -Force }
            "R" { 
                $novoNome = "backup_" + (Get-Date -Format "yyyyMMdd_HHmmss") + ".php"
                Move-Item -Destination $novoNome 
            }
            "C" { Write-Host "Operação cancelada"; exit }
        }
    }
}
```

---

## 📊 Comandos Úteis

### 🔍 Verificar Estrutura
```powershell
# Listar arquivos setup*
Get-ChildItem -Path "." -Filter "setup*.php" | Select-Object Name, Length, LastWriteTime

# Verificar diretório setup
Test-Path "solucoes\setup" -PathType Container
```

### 📈 Estatísticas da Movimentação
```powershell
# Contar arquivos movidos
$movidos = 0
$erros = 0

foreach ($arquivo in $arquivos) {
    if (Test-Path $arquivo) {
        try {
            Move-Item -Path $arquivo -Destination $destino -Force
            $movidos++
        } catch {
            $erros++
            Write-Host "❌ Erro: $arquivo - $_"
        }
    }
}

Write-Host "📊 Relatório Final:"
Write-Host "Arquivos movidos: $movidos"
Write-Host "Erros: $erros"
Write-Host "Taxa de sucesso: $([math]::Round(($movidos/($movidos+$erros))*100, 2))%"
```

---

## 🚀 Integração com Sistema

### 📋 Uso com `master_rules.md`
1. **Consultar regra LLM** antes de mover
2. **Verificar estrutura de diretórios**
3. **Aplicar validações específicas**
4. **Documentar operações realizadas**

### 📝 Exemplo Completo
```powershell
# Script integrado com validações do master_rules.md
function Mover-ParaSolucoes {
    # 1. Consultar master_rules.md
    # 2. Validar estrutura
    # 3. Mover arquivos
    # 4. Gerar relatório
}
```

---

## 📞 Suporte e Troubleshooting

### 🔧 Problemas Comuns
- **"Arquivo já existe"**: Usar `-Force` ou verificar se é mesmo arquivo
- **"Caminho não encontrado"**: Verificar diretório atual
- **"Acesso negado"**: Executar como administrador
- **"Permissão negada"**: Verificar atributos do arquivo

### 📋 Comandos de Diagnóstico
```powershell
# Verificar permissões
Get-Acl "arquivo.php" | Format-List

# Verificar propriedades
Get-ItemProperty "arquivo.php"

# Testar caminho
Test-Path "c:\caminho\completo\arquivo.php"
```

---

**Versão:** 1.0.0  
**Data:** 26/02/2026  
**Status:** ✅ **REGRA ESTABELECIDA**
