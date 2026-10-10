# web-sec-demo

Cookie とセッションの違いを、ブラウザの開発者ツールで確かめる教材です。「名前を覚える」同じ動作を、Cookie に保存する版とサーバ側のセッションに保存する版で動かし、ブラウザに残るものを比べます。

| ファイル | 動作 | ブラウザに残るもの |
|---|---|---|
| `cookie.php` / `cookie2.php` | 名前と訪問回数を Cookie に保存する | 名前と回数そのもの (`username`、`visited_count`) |
| `session.php` / `session2.php` | 名前とアクセス回数をサーバ側に保存する | セッション ID の Cookie (`PHPSESSID`) |

**注意**: 教育用のデモです。`session2.php` は入力値をそのまま出力します。自分の PC の中で動かし、インターネットに公開しないでください。

## 必要なもの

- Docker

## 起動

```bash
docker build -t web-sec-demo .
docker run --rm -p 8080:80 web-sec-demo
```

ブラウザで http://localhost:8080/cookie.php を開きます。終了は Ctrl+C です。

## 試してみる

1. 開発者ツール (F12) の「ストレージ」または「Application」で、Cookie を見る。`visited_count` が付いている
2. `cookie.php` で名前を送信する。`username` の Cookie が付く。値を書き換えて再読み込みすると、画面の名前が変わる
3. `session.php` を開く。画面のセッション ID と `PHPSESSID` の値が同じになる。Cookie に名前も回数もない

## 実行例

```
$ curl -si http://localhost:8080/cookie.php | grep -i set-cookie
Set-Cookie: visited_count=1
$ curl -si http://localhost:8080/session.php | grep -i set-cookie
Set-Cookie: PHPSESSID=53ae439afae7f2491f4e590f318d601e; path=/
```

`setup.sh` は、講師が EC2 で見せるときの起動スクリプトです。
