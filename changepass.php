<?php
include "common.php";

session_start();
$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_errno) {
	die('Could not connect: ' . $conn->connect_error);
}

if (empty($_SESSION["username"])) {
	header("location: login.html");
}

if (empty($_POST["oldpass"]) || empty($_POST["newpass"])) {
	echo "Empty required field";
	echo "<br><a href=$baseurl/profile.php>Go back</a>";
	die();
}
$uname = $_SESSION["username"];
$oldpass = hash('sha256', $_POST["oldpass"]);
$newpass = hash('sha256', $_POST["newpass"]);
$checkoldquery = "SELECT id FROM $usertablename WHERE username='$uname' AND password='$oldpass'";
$checkoldresult = $conn->query($checkoldquery);
if (mysqli_num_rows($checkoldresult) != 1) {
	echo "Incorrect old password";
	echo "<br><a href=$baseurl/profile.php>Go back</a>";
	die();
}
else {
	$changequery = "UPDATE $usertablename SET password='$newpass' WHERE username='$uname'";
	$conn->query($changequery);
	echo "Password change successful";
	echo "<br><br><a href=$baseurl/profile.php>Go back</a>";
	die();
}
?>
