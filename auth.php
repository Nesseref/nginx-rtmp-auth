<?php
$pattern = "/\\?(.*$)/i";
$host = "";
$username = "";
$password = "";
$dbname = "";

$conn = mysql_connect($host, $username, $password);
if (!$conn) {
        die('Could not connect: ' . mysql_error());
}

if(empty($_GET['name']) || empty($_GET['swfurl']))
{
        http_response_code(400);
        die("Bad query");
}
else
{
        $name = mysql_real_escape_string($_GET['name']);
        preg_match($pattern, $_GET['swfurl'], $matches);
}

mysql_select_db($dbname);
$query = "SELECT idhash FROM users WHERE username = '$name'";
$result = mysql_query($query);
$row = mysql_fetch_assoc($result);

if ($matches[1] == $row['idhash']){
        http_response_code(200);
        die("Good query");
}
else{
        http_response_code(400);
        die("Bad query");
}
?>

