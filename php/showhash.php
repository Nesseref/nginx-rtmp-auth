<?php
include "common.php";

session_start();
try
{
	$dbh = new PDO('mysql:host='.$host.';dbname='.$dbname.';charset=utf8', $username, $password);
} catch(PDOException $e)
{
	http_response_code(401);
	trigger_error($e->getMessage());
	die("Database error!");
}

if (empty($_SESSION["username"])) {
	header("location: login.html");
}
$uname = $_SESSION["username"];


try
{
	$sth = $dbh->prepare("SELECT idhash FROM ".$usertablename." WHERE username = :username");
	$sth->execute(array("username" => $uname));
	$row = $sth->fetch();
} catch(PDOException $e)
{
	http_response_code(401);
	trigger_error($e->getMessage());
	die("Database error!");
}

echo "Server URL: " . $streamurl . $row["idhash"] . "<br>";
echo "<br><a href=$baseurl/profile.php>Go back</a>";
?>
