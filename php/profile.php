<?php
include "common.php";
session_start();

//removed unneeded mysql database connection

if (empty($_SESSION["username"]))
{
	header("location: login.html");
	exit(); // exit or browser may see the rest of the page
}
?>
<html>
<head>
<title>Frogstream User Profile</title>
</head>
<body>
Hi, <?php echo $_SESSION["username"];?><br>
<br><a href=<?php echo $baseurl;?>/showhash.php>Show your stream RTMP URL</a><br>
<br><a href=<?php echo $baseurl;?>/resethash.php>Reset your stream RTMP URL</a><br>
<br>Change your password:<br>
<form action="changepass.php" method="post">
Old password: <input type="password" name="oldpass"><br>
New password: <input type="password" name="newpass"><br>
<input type="submit">
</form>
<a href=<?php echo $baseurl;?>/logout.php>Logout</a><br>
</body>
</html>
