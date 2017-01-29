<?php
include "common.php";

try
{
	$dbh = new PDO('mysql:host='.$host.';dbname='.$dbname.';charset=utf8', $username, $password);
} catch(PDOException $e)
{
	http_response_code(401);
	trigger_error($e->getMessage());
	die("Database error!");
}

$login_username=isset($_POST["username"])?(string)$_POST["username"]:'';
$login_password=isset($_POST["password"])?(string)$_POST["password"]:'';

if ( empty($login_username) || empty($login_password) )
{
	echo "Empty required field";
	echo "<br><a href=$baseurl/login.html>Go back</a>";
	die();
}

$login_password = hash('sha256', $login_password);
try
{
	$sth = $dbh->prepare("SELECT * FROM :table WHERE username = :username AND password = :password");
	$sth->execute( array( 'table' => $usertablename, 'username' => $login_username, 'password' => $login_password ) );
} catch(PDOException $e)
{
	trigger_error($e->getMessage());
	die("database error");
}

$res = $sth->fetch();

if( empty($res) )
{
	session_start();
	$_SESSION["username"] = $username;
	header("location: profile.php");
}
else
{
	echo "Invalid username/password combination";
	echo "<br><a href=$baseurl/login.html>Go back</a>";
	die();
}
?>
