#!/system/bin/sh
export HOME=/root
export PATH=/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin
cd /root/meumcp
echo "=== AUDIT-START ==="
echo "node=$(node --version 2>&1)"
echo "npm=$(npm --version 2>&1)"
echo "git_status=$(git status --short 2>&1 | head -1)"
echo "===1. npm test==="
npm test 2>&1 | grep -E "Tests:|Test Suites:|PASS|FAIL" | head -4
echo "===2. npm run build (check dist)==="
node dist/cli.js --version 2>&1 | head -1
echo "===3. meumcp doctor==="
node dist/cli.js doctor 2>&1 | head -8
echo "===4. MCPs discovered==="
node dist/cli.js discover 2>&1 | grep -iE "MCPs|free|valid" | head -2
echo "===5. server.ts check (logger pino dest + zodToJsonSchema)==="
grep -c "dest: 2" src/core/logger.ts 2>&1
grep -c "zodToJsonSchema" src/server.ts 2>&1
echo "===6. custom dirs (divergem)==="
ls /root/meumcp/agents /root/meumcp/canalqb_layout /root/meumcp/regras 2>&1 | head -3
echo "===AUDIT-END==="
