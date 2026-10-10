#!/bin/bash
# Amazon Linux 2023 上で、cookie / session デモを Docker で起動するスクリプト。
# EC2 のユーザーデータに貼るか、root で実行する。再実行すると最新の内容で作り直す。
# セッションファイルはホストの /var/lib/php/session に出る (SSH で sudo ls / sudo cat して見せる)。
set -euo pipefail

dir=/opt/web-sec-demo
sessions=/var/lib/php/session

dnf install -y docker git
systemctl enable --now docker

if [ -d "$dir/.git" ]; then
  git -C "$dir" pull --ff-only
else
  git clone --depth 1 https://github.com/mamezou/web-sec-demo.git "$dir"
fi

# コンテナ内の www-data (uid 33) が書き込めるようにする
mkdir -p "$sessions"
chown 33:33 "$sessions"

docker build -t web-sec-demo "$dir"
docker rm -f web-sec-demo 2>/dev/null || true
docker run -d --name web-sec-demo --restart unless-stopped -p 80:80 \
  -v "$sessions:$sessions" web-sec-demo
