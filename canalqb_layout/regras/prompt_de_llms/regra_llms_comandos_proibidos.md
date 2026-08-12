# Comandos Proibidos e Falhas de Terminal

Este arquivo registra comandos que falharam em LLMs específicos devido a restrições de ambiente (Windows/XAMPP/Terminal específico). QUALQUER LLM deve consultar este arquivo antes de executar comandos e DEVE registrar novas falhas aqui.

## Registro de Falhas

| LLM | OS | Comando Proibido / Que Falhou | Motivo / Erro |
|-----|----|-------------------------------|---------------|
| Trae | Windows | `powershell -Command "Move-Item ..."` | Falha de permissão/sintaxe no terminal interno do Trae. |
| Antigravity | Windows | `sandbox-exec` | Comando exclusivo de macOS/Linux, inexistente no Windows. |
| Cascade | Windows | `ls -la` | `Get-ChildItem : Não é possível localizar um parâmetro que coincida com o nome de parâmetro 'la'` - PowerShell não reconhece parâmetro do Linux. |

## Regra de Registro
Se um comando falhar no terminal:
1. Identifique o nome do seu LLM.
2. Identifique o sistema operacional.
3. Adicione o comando exato à tabela acima.
4. Descreva brevemente o erro.
5. Busque uma alternativa compatível (ex: CMD em vez de PowerShell, ou vice-versa).

## Comandos Corretos por Ambiente

### ✅ PowerShell Windows:
```powershell
# Listar arquivos detalhado (equivalente a ls -la)
Get-ChildItem -Force

# Listar arquivos simples
dir

# Remover diretório recursivamente
Remove-Item -Recurse -Force

# Mover arquivos
Move-Item
```

### ✅ Bash Linux/macOS:
```bash
# Listar arquivos detalhado
ls -la

# Listar arquivos simples
ls

# Remover diretório recursivamente
rm -rf

# Mover arquivos
mv
```

## 🚨 IMPORTANTE

**ESTE ARQUIVO DEVE SER ATUALIZADO SEMPRE QUE UM COMANDO FALHAR!**

**LLMs devem consultar este arquivo antes de gerar comandos para ambientes específicos.**
