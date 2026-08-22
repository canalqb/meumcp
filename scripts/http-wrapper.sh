#!/bin/bash
# Wrapper para expor MCP como HTTP (Streamable HTTP)
# Uso: ./scripts/http-wrapper.sh

PORT=${MCP_SERVER_PORT:-8765}

echo "🚀 Iniciando meumcp em modo HTTP na porta $PORT..."

# Usar o MCP Server HTTP Bridge
node -e "
const http = require('http');
const { spawn } = require('child_process');
const { StreamableHTTPServerTransport } = require('@modelcontextprotocol/sdk/serve...

echo "✅ Servidor está online em http://localhost:$PORT/mcp"
echo "🧪 Teste: curl -X POST http://localhost:$PORT/mcp"
"