# web-sec-demo

Cookie とセッションの違いを、ブラウザの開発者ツールで見せる講師デモです。Docker (`php:8.3-apache`) で動かします。EC2 (Amazon Linux 2023) ではポート 80 で公開します。

| ファイル | 見せること |
|---|---|
| `cookie.php` / `cookie2.php` | 名前と訪問回数が Cookie に保存される。値を書き換えると画面に反映される |
| `session.php` / `session2.php` | 名前とアクセス回数はサーバ側に保存され、ブラウザには `PHPSESSID` (セッション ID) の Cookie だけが残る |

**注意**: 教育用のデモです。`session2.php` は入力値をそのまま出力します。研修中だけ起動し、終わったらインスタンスを停止してください。

## 必要なもの

- ローカルで動かす場合: Docker
- EC2 で動かす場合: Amazon Linux 2023 の EC2 (インターネットから HTTP でアクセスできること)
- セキュリティグループで、受講者の接続元に対してポート 80 (HTTP) を許可する

## ローカルで起動する

```bash
docker build -t web-sec-demo .
docker run --rm -p 8080:80 web-sec-demo
```

ブラウザで `http://localhost:8080/cookie.php` を開きます。終了は Ctrl+C です。

## EC2 で起動する

EC2 の起動時に、`setup.sh` の内容を「ユーザーデータ」に貼ります。起動済みのインスタンスでは、root で次を実行します。

```bash
sudo bash -c "$(curl -fsSL https://raw.githubusercontent.com/mamezou/web-sec-demo/main/setup.sh)"
```

`setup.sh` は、Docker と git をインストールし、このリポジトリを `/opt/web-sec-demo` に取得して、コンテナをポート 80 で起動します。コンテナは EC2 の再起動後も自動で立ち上がります。再実行すると、最新の内容でコンテナを作り直します。

## 確認

ブラウザで `http://<EC2 のパブリック IP>/cookie.php` を開きます。

1. 開発者ツール (F12) の「ストレージ」または「Application」で、Cookie を見る
2. `cookie.php` で名前を送信し、`username` の Cookie が付くことを見る
3. `session.php` を開き、画面のセッション ID と `PHPSESSID` の Cookie の値が同じことを見る

## 終了

インスタンスを停止または終了します。

## 動作確認の状況

- ローカルの Docker (PHP 8.3.35 / Apache 2.4.68) で、4 本の PHP が HTTP 200 を返し、`visited_count` と `PHPSESSID` の Cookie が付くことを確認済みです
- `cookie.php` の入力欄は、Cookie に `"><script>` を入れても `htmlspecialchars` で無害化されることを確認済みです
- `setup.sh` は、Amazon Linux 2023 の EC2 (t3.micro、東京リージョン) で 2026-10-09 に確認済みです。ユーザーデータに貼って起動すると、4 本の PHP が HTTP 200 を返します。再起動後もコンテナが自動で立ち上がります
- 上の `curl` で `setup.sh` を取得して実行する手順は、EC2 では未確認です
- Amazon Linux 2 は対象外です
