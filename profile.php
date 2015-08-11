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

echo "Hi, " . $_SESSION["username"] . "<br>";
echo "<a href=$baseurl/showhash.php>Show your stream URL</a><br>";

?>

