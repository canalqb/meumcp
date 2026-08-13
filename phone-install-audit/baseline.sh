#!/system/bin/sh
# Baseline Ubuntu/Termux/OpenCode (dentro do proot-distro)
export HOME=/root
export PATH=/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin
echo "1.pwd=$(pwd)"
whoami
uname -a
cat /etc/os-release 2>/dev/null | grep PRETTY_NAME | head -1
echo "node=$(node --version 2>&1 | head -1)"
echo "npm=$(npm --version 2>&1 | head -1)"
echo "git=$(git --version 2>&1 | head -1)"
echo "opencode=$(which opencode 2>&1)"
opencode --version 2>&1 | head -1
echo "===meumcp state==="
ls /root/meumcp/ 2>&1 | head -3 || echo "meumcp not installed yet"
