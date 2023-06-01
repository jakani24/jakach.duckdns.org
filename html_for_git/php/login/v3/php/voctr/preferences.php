<?php
session_start();
 include "/var/www/html/php/login/v3/waf/waf.php";
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: ../login.php");
    exit;
}
	$username=$_SESSION["username"];
?>
<!DOCTYPE html>
<html>
<head>
  <title>voctr station by jakach</title>
</head>
<body>
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
<center>
<h1>
	Preferences

</h1>
<p>Please choose one of the following options?</p>
<?php
$file=$_GET['file'];
$dir=$_GET['direction'];
echo("
    <a href='answer.php?file=$file&index=0&direction=$dir&part_answer=1&right=0&wrong=0'>
        <input type='button' value='Accept part-answers'>
    </a><br><br>
    ");
echo("
    <a href='answer.php?file=$file&index=0&direction=$dir&part_answer=0&right=0&wrong=0'>
        <input type='button' value='dont accept part-answers'>
    </a>
    ");

?>
</center>
</ul>
</body>
</html>
