<?php
$hash = bin2hex(openssl_random_pseudo_bytes(10));
echo $hash;
?>
