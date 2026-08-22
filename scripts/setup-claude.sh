#!/bin/bash
# Script de verificação e correção de configuração Claude Desktop

echo "🔍 Verificando instalação do meumcp..."

# Verifica se build existe
if [ ! -f "dist/server.js" ]; then
    echo "❌ Build não encontrado. Executando npm run build..."
    npm run build
fi

# Encontra caminho absoluto
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
SERVER_PATH="$SCRIPT_DIR/dist/server.js"

echo "📍 Caminho do servidor: $SERVER_PATH"

# Detecta sistema operacional
if [[ "$OSTYPE" == "darwin"* ]]; then
    # macOS
    CONFIG_FILE="$HOME/Library/Application Support/Claude/claude_desktop_config.json"
elif [[ "$OSTYPE" == "linux-gnu"* ]]; then
    # Linux
    CONFIG_FILE="$HOME/.config/Claude/claude_desktop_config.json"
else
    # Windows (Git Bash / MSYS)
    CONFIG_FILE="/c/Users/$(whoami)/AppData/Roaming/Claude/claude_desktop_config.json"
fi

echo "📄 Arquivo de configuração: $CONFIG_FILE"

if [ -f "$CONFIG_FILE" ]; then
    echo "✅ Arquivo de configuração encontrado"
    echo ""
    echo "📋 Conteúdo atual:"
    cat "$CONFIG_FILE" | head -20
else
    echo "⚠️ Arquivo não encontrado. Crie manualmente:"
    echo ""
    cat << EOF
{
  "mcpServers": {
    "meumcp": {
      "command": "node",
      "args": ["$SERVER_PATH"],
      "env": {
        "MEUMCP_CLI": "true"
      }
    }
  }
}
EOF
fi

echo ""
echo "✅ Configuração verificada! Reinicie o Claude Desktop para aplicar as mudanças."