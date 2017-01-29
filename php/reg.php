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

if (empty($_POST["username"]) || empty($_POST["email"]) || empty($_POST["password"])) {
	echo "Empty required field";
	echo "<br><a href=$baseurl/register.html>Go back</a>";
	die();
}

$username = $_POST["username"];
$email = $_POST["email"];
$password = hash('sha256', $_POST["password"]);	

if (strlen($username) > 64) {
	echo "Username too long";
	echo "<br><a href=$baseurl/register.html>Go back</a>";
	die();
}
if (strlen($email) > 64) {
	echo "Email too long";
	echo "<br><a href=$baseurl/register.html>Go back</a>";
	die();
}

if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "Invalid email address";
    die();
}

try
{
	$sth = $dbh->prepare("SELECT username FROM ".$usertablename." WHERE username = :username");
	$sth->execute( array( 'username' => $username ) );
	$nameresult = $sth->fetch();

	$sth = $dbh->prepare("SELECT email FROM ".$usertablename." WHERE email = :email");
	$sth->execute( array( 'email' => $email ) );
	$emailresult = $sth->fetch();
} catch(PDOException $e)
{
	trigger_error($e->getMessage());
	die("Database error");
}
if ( !empty($nameresult['username']) )
{
	echo "Duplicate username";
	echo "<br><a href=$baseurl/register.html>Go back</a>";
	die();
}

if ( !empty($emailresult['email']) )
{
	echo "Duplicate email";
	echo "<br><a href=$baseurl/register.html>Go back</a>";
	die();
}

$idhash = genkey();

try
{
	$sth = $dbh->prepare("INSERT INTO ".$usertablename." (username, email, password, idhash) VALUES (:username, :email, :password, :idhash)");
	$sth->execute(array(
		'username' => $username,
		'email' => $email,
		'password' => $password,
		'idhash' => $idhash
	));
} catch(PDOException $e)
{
	trigger_error($e->getMessage());
	die("Database error");
}





echo "Server URL: " . $streamurl . $idhash . "<br>";
echo "Play Path/Stream Key: " . $username;
echo "<br><a href=$baseurl/index.html>Main page</a>";
echo "<br><a href=$baseurl/profile.php>User profile</a>";
die();
