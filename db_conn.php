<?php
$dbhost = ‘ADDHOST’;
$dbuser = ‘USER’;
$dbpass = ‘PASS’;
$dbname = ’DB’;
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
if (!$conn) {
    die('Could not connect: ' . mysql_error());
}
 ?>
