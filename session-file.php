<?php
session_start();
$file = session_save_path() . '/sess_' . session_id();
?>
<!doctype html>
<html lang="ja">
  <head>
  <meta charset="utf-8">
  <title>Session File</title>
</head>
<body>
<p>あなたのセッションファイルは、サーバ上の <code><?= htmlspecialchars($file, ENT_QUOTES) ?></code> です。</p>
<?php if (is_readable($file) && filesize($file) > 0): ?>
<p>中身:</p>
<pre><?= htmlspecialchars(file_get_contents($file), ENT_QUOTES) ?></pre>
<?php else: ?>
<p>まだファイルがありません。<a href="session.php">session.php</a> を開いてから、もう一度来てください。</p>
<?php endif; ?>
    <p><a href="session.php">session.php へ戻る</a> / <a href="index.html">トップページへ</a></p>
</body>
</html>
