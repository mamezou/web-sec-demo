<?php
if (isset($_POST["username"])) {

// expire : 120s
setcookie("username", $_POST["username"], time()+120);

print "cookieに保存しました。<br />";
echo "<p>お名前： " .  htmlspecialchars($_POST["username"]) . "</p>";

print "<br /><a href='cookie.php'>cookie.php</a>にアクセスしてください。";
}
?>
