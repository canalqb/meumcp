#!/bin/sh
set -x
export HOME=/root
export PATH=/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin
cd /root
git clone https://github.com/canalqb/meumcp meumcp 2>&1
cd /root/meumcp
npm install 2>&1
npm run build 2>&1
node dist/cli.js doctor 2>&1
node dist/cli.js --version 2>&1
echo "===MEUMCP_INSTALL_DONE==="
