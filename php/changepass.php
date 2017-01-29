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

if( empty( $_SESSION["username"] ) )
{
	header("location: login.html");
	exit(); // some clients don't follow redirects, so the rest of the code will execute without an exit()
}

if ( empty( $_POST["oldpass"] ) || empty( $_POST["newpass"] ) )
{
	echo "Empty required field";
	echo "<br><a href=$baseurl/profile.php>Go back</a>";
	die();
}

$uname = $_SESSION["username"];
$oldpass = hash('sha256', $_POST["oldpass"]);
$newpass = hash('sha256', $_POST["newpass"]);

try
{
	$sth = $dbh->prepare( "UPDATE $usertablename SET password=:newPassword WHERE username=:uname AND password=:oldPassword" );
	$sth->execute( array( 'newPassword' => $newpass, "oldPassword" => $newpass, 'uname' => $uname ) );
} catch(PDOException $e )
{
	echo "Incorrect old password";
	echo "<br><a href=$baseurl/profile.php>Go back</a>";
	trigger_error( $e->getMessage() );
	die();
}
header("Location: /profile.php");
exit();
?>
