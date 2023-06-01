<?php
/* Database credentials. Assuming you are running MySQL
server with default setting (user 'root' with no password) */
define('DB_SERVER_plu', 'SERVER');
define('DB_USERNAME_plu', 'USERNAME');
define('DB_PASSWORD_plu', 'PASSWORD');
define('DB_NAME_plu', 'DB_NAME');
 
/* Attempt to connect to MySQL database */
$link = mysqli_connect(DB_SERVER_plu, DB_USERNAME_plu, DB_PASSWORD_plu, DB_NAME_plu);
//$link=mysqli_connect($DB_SERVER_,$DB_USERNAME_,$DB_PASSWORD_,$DB_NAME_);
// Check connection
if($link === false){

    die("<br>ERROR: Could not connect. " . mysqli_connect_error());
}
?>

