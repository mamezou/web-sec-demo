<?php
if(!isset($_COOKIE['visited_count']) ){
  $visited_count = 1;
}else{
  $visited_count = $_COOKIE['visited_count'] + 1;
}
setcookie('visited_count', $visited_count);
?>
<!doctype html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <title>Cookie</title>
  </head>
  <body>
    <?php
    if (isset($_COOKIE["username"])) {
      echo("こんにちは、");
      print htmlspecialchars($_COOKIE["username"], ENT_QUOTES)."さん<br />";
      $input = $_COOKIE["username"];
    }
    
    ?>
    <?php
      echo '訪問回数： ' . htmlspecialchars($visited_count, ENT_QUOTES, 'UTF-8');
    ?>
    <form action="cookie2.php" method="post">
    あなたの名前を入力してください。
      <input type="text" name="username" value="<?= $input ?>" />
      <input type="submit" name="name_submit" value="送信" />
    </form>

  </body>
</html>