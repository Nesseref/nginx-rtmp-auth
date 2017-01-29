<?php
include "common.php";
$pattern = "/\\?(.*$)/i";

try
{
	$dbh = new PDO('mysql:host='.$host.';dbname='.$dbname.';charset=utf8', $username, $password);
} catch(PDOException $e)
{
	http_response_code(401);
	trigger_error($e->getMessage());
	die("Database error!");
}


if(empty($_GET['name']) || empty($_GET['swfurl']))
{
	http_response_code(401);
	die();
}
else
{
	if( !preg_match($pattern, $_GET['swfurl'], $matches) )
	{
		trigger_error("Validation error, swfurl did not match pattern.");	
		http_response_code(401);
		die("Validation error");
	}
}

try
{
	$sth = $dbh->prepare("SELECT idhash FROM :table WHERE username = :username");
	$sth->execute( array( 'table' => $usertablename, 'username' => $_GET['name'] ) );
	$res = $sth->fetch();
} catch(PDOException $e)
{
	trigger_error($e->getMessage());
	http_response_code(401);
	die("Query error");
}

$row = $res;

if ($matches[1] == $row['idhash'])
{
	http_response_code(200);
	die();
} else {
	http_response_code(402);
	die();
}
?>
