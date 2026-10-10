# web-sec-demo

Cookie とセッションの違いを、ブラウザの開発者ツールで確かめる教材です。「名前を覚える」という同じ動作を、Cookie に保存する版とサーバ側のセッションに保存する版で動かし、ブラウザに残るものを比べます。

| ファイル | 動作 | ブラウザに残るもの |
|---|---|---|
| `cookie.php` / `cookie2.php` | 名前と訪問回数を Cookie に保存する | 名前と回数そのもの (`username`、`visited_count`) |
| `session.php` / `session2.php` | 名前とアクセス回数をサーバ側に保存する | セッション ID の Cookie (`PHPSESSID`) |

**注意**: 教育用のデモです。`session2.php` は入力値をそのまま出力します。自分の PC の中で動かし、インターネットに公開しないでください。

## 必要なもの

- Docker
- git

## 起動

```bash
git clone https://github.com/mamezou/web-sec-demo.git
cd web-sec-demo
docker build -t web-sec-demo .
docker run --rm --name web-sec-demo -p 8080:80 web-sec-demo
```

`docker run` を実行したターミナルは、デモが動いている間、使えなくなります。コマンドを実行したいときは、2 つ目のターミナルを開きます。

ブラウザで http://localhost:8080/ を開きます。トップページから Cookie、Session の順に進みます。終了は、`docker run` を実行したターミナルで Ctrl+C を押します。

## 試してみる

### Cookie

1. トップページの「Cookie」を開く (`cookie.php`)。開発者ツール (F12) の「ストレージ」または「Application」で、Cookie の一覧から `http://localhost:8080` を選ぶ。`visited_count` が付いていて、再読み込みするたびに値が増える
2. 名前を入力して送信する (`cookie2.php` が開く)。`username` の Cookie が付く。画面のリンクから `cookie.php` に戻ると、名前が表示される。開発者ツールで `username` の値をダブルクリックして書き換え、`cookie.php` を再読み込みすると、画面の名前が変わる。`username` の Cookie は 120 秒で消える

### Session

3. トップページの「Session」を開く (`session.php`)。画面のセッション ID と、Cookie 一覧の `PHPSESSID` の値が同じになる。名前を入力して送信し (`session2.php` が開く)、画面のリンクから `session.php` に戻ると、名前が表示される。`PHPSESSID` の値はセッション ID で、名前も回数も含まない
4. `session.php` の「サーバ側のセッションファイルを見る」を開く。ファイルの中身に `count|i:2;username|s:...` のように回数と名前が入っている。ファイル名の `sess_` より後ろは `PHPSESSID` の値と同じ。2 つ目のターミナルで `docker exec web-sec-demo cat /var/lib/php/session/sess_<PHPSESSID の値>` を実行しても、同じ中身が出る
5. `session.php` の名前欄に `<script>document.body.append(document.cookie)</script>` を入力して送信する。送信後の `session2.php` の画面の最下部に `PHPSESSID=...` が表示される。`visited_count` など、手順 1 と 2 の Cookie も並ぶ。ページ内のスクリプトが Cookie を読めたことを示す

## 直してみる

次のどちらかのファイル修正を行います。

- `session.php` と `session2.php` の `session_start();` の前に `ini_set('session.cookie_httponly', '1');` を入れる。`HttpOnly` は、ページ内のスクリプトが Cookie を読むことを防ぐ。スクリプトが動くこと自体は防げない
- `session2.php` の `$_POST["username"]` の出力を `htmlspecialchars($_POST["username"], ENT_QUOTES)` に変える。入力したスクリプトが文字として表示され、動かなくなる

修正後の確認は、次の順で行います。

1. `docker run` を実行したターミナルで Ctrl+C を押し、デモを止める
2. 上のどちらかを修正する
3. `docker build -t web-sec-demo .` と `docker run --rm --name web-sec-demo -p 8080:80 web-sec-demo` を実行する
4. 手順 5 をもう一度行う。1 つ目の修正では `PHPSESSID` が画面に出なくなる (`HttpOnly` が付かない `visited_count` などは出る)。2 つ目の修正では、スクリプトが文字として表示される

## 実行例

ターミナルで `curl` が使える場合は、レスポンスヘッダーの `Set-Cookie` を確認できます。1 つ目の修正の後は、`PHPSESSID` の行の末尾に `HttpOnly` が付きます。

```
$ curl -si http://localhost:8080/cookie.php | grep -i set-cookie
Set-Cookie: visited_count=1
$ curl -si http://localhost:8080/session.php | grep -i set-cookie
Set-Cookie: PHPSESSID=53ae439afae7f2491f4e590f318d601e; path=/
```

`setup.sh` は、講師が EC2 で見せるときの起動スクリプトです。
