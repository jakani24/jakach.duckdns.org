<!DOCTYPE html>
<html>
<?php session_start(); ?>
<?php $color=$_SESSION["color"]; ?>
<?php echo(" <body style='background-color:$color'> ");?>
<script src="/php/login/v3/js/load_page.js"></script>
<script>
function load_user()
{
	$(document).ready(function(){
   	$('#content').load("/php/login/v3/html/user_page.html");
	});
}
function load_admin()
{
	$(document).ready(function(){
   	$('#content').load("/php/login/v3/html/admin_page.html");
	});
}
</script>
<?php
// Initialize the session
include "/var/www/html/php/login/v3/waf/waf.php";

 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
	$username=$_SESSION["username"];
	$role=$_SESSION["role"];
	if($role=="user")
	{
		echo "<script type='text/javascript' >load_user()</script>";
	}
	if($role=="admin")
	{
		echo "<script type='text/javascript' >load_admin()</script>";
	}
?>

	<div id="content"></div>
<div class="cleanslate w24tz-current-time w24tz-large" style="display: inline-block !important; visibility: hidden !important; min-width:300px !important; min-height:145px !important;"><p><a href="//24timezones.com/Bern/time" style="text-decoration: none" class="clock24" id="tz24-1645350594-c1270-eyJob3VydHlwZSI6MTIsInNob3dkYXRlIjoiMSIsInNob3dzZWNvbmRzIjoiMSIsImNvbnRhaW5lcl9pZCI6ImNsb2NrX2Jsb2NrX2NiNjIxMjBlYzIxZmJiNiIsInR5cGUiOiJkYiIsImxhbmciOiJlbiJ9" title="World Time :: Bern" target="_blank">Current time</a></p><div id="clock_block_cb62120ec21fbb6"></div></div>
<script type="text/javascript" src="//w.24timezones.com/l.js" async></script>
</body>
</html>
