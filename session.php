<?php
session_start();
?>
<!doctype html>
<html lang="ja">
  <head>
  <meta charset="utf-8">
  <title>Session</title>
</head>
<body>
<?php
print('セッションIDは '.$_COOKIE['PHPSESSID'].' です。');
if(!isset($_SESSION['count'])){
  $_SESSION['count'] = 1;
}else{
  $_SESSION['count'] ++;
}
echo "<p>アクセス回数： " . $_SESSION['count'] . "</p>";
 
?> 
    <form action="session2.php" method="post">
    あなたの名前を入力してください。
      <input type="text" name="username" value="" />
      <input type="submit" name="name_submit" value="送信" />
    </form>
</body>
</html>