<?php
$host = "";
$username = "";
$password = "";
$dbname = "";
$usertablename = "";
$streamurl = "";
$baseurl = "";

function genkey() {
	return bin2hex(openssl_random_pseudo_bytes(10));
}
?>
