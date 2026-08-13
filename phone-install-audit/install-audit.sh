#!/bin/sh
# Install audit: meumcp
export HOME=/root
export PATH=/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin
export DEBIAN_FRONTEND=noninteractive
mkdir -p "$HOME/install-audit"
TS=$(date +%Y%m%d-%H%M%S)
LOG="$HOME/install-audit/meumcp-$TS.log"
echo "=== START $TS ===" > "$LOG"
exec >> "$LOG" 2>&1
cd /root && git clone --depth 1 https://github.com/canalqb/meumcp meumcp-audit 2>&1
cd /root/meumcp-audit
echo "=== package ===" && grep '"name"' package.json | head -1 && grep '"version"' package.json | head -1
echo "=== node ===" && node --version
echo "=== npm install ===" && npm install --no-audit --no-fund 2>&1 | tail -4
echo "=== build ===" && npm run build 2>&1 | tail -3
echo "=== doctor ===" && node dist/cli.js doctor 2>&1 | head -6
echo "=== test ===" && npm test 2>&1 | grep -E "Tests:|Test Suites:" | head -2
echo "=== version ===" && node dist/cli.js --version 2>&1
echo "=== START ===" && cat <<'JSON' | node dist/server.js 2>&1 | head -4
{"jsonrpc":"2.0","id":1,"method":"initialize","params":{"protocolVersion":"2024-11-05","capabilities":{},"clientInfo":{"name":"audit","version":"1.0"}}}
JSON
echo "=== END INSTALL-AUDIT ==="
