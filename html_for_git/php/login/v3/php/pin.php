<?php
// Initialize the session
session_start();
include "/var/www/html/php/login/v3/waf/waf.php";
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true ){
    header("location: login.php");
    exit;
}
?>
<?php
	$role=$_SESSION["role"];
	if($role=="user")
	{
		header("location:user.php");
	}
	if($role=="admin")
	{
		header("location:admin.php");
	}
?>
