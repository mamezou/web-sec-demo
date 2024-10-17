<?php
session_start();
if (isset($_POST["username"])) {
$_SESSION['username'] = $_POST["username"];
print "sessionに保存しました。<br />";
echo "<p>お名前： " . $_POST["username"] . "</p>";
}
?>
