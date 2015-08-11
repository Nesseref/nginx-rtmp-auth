<?php
include "common.php";

session_start();
$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_errno) {
	die('Could not connect: ' . $conn->connect_error);
}
$uname = $_SESSION["username"];

$query = "SELECT idhash FROM $usertablename WHERE username = '$uname'";
$result = $conn->query($query);
$row = $result->fetch_array(MYSQLI_ASSOC);

echo "Server URL: " . $streamurl . $row["idhash"] . "<br>";
echo "<br><a href=$baseurl/profile.php>Go back</a>";
?>
