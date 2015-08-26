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

$uname = $_SESSION["username"];

$newhash = genkey();
$query = "UPDATE $usertablename SET idhash='$newhash' WHERE username='$uname'";
$conn->query($query);

echo "Server URL: " . $streamurl . $newhash . "<br>";
echo "<br><a href=$baseurl/profile.php>Go back</a>";
?>
