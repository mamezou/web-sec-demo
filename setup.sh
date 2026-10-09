#!/bin/bash
# Amazon Linux 2023 上で、cookie / session デモを Docker で起動するスクリプト。
# EC2 のユーザーデータに貼るか、root で実行する。再実行すると最新の内容で作り直す。
set -euo pipefail

dir=/opt/web-sec-demo

dnf install -y docker git
systemctl enable --now docker

if [ -d "$dir/.git" ]; then
  git -C "$dir" pull --ff-only
else
  git clone --depth 1 https://github.com/mamezou/web-sec-demo.git "$dir"
fi

docker build -t web-sec-demo "$dir"
docker rm -f web-sec-demo 2>/dev/null || true
docker run -d --name web-sec-demo --restart unless-stopped -p 80:80 web-sec-demo
