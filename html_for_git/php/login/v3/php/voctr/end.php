


<?php
session_start();
 include "/var/www/html/php/login/v3/waf/waf.php";
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: ../login.php");
    exit;
}
	$username=$_SESSION["username"];
	$unwanted_ft=array("csv","voctr","txt");
	if(isset($_GET['file']))
	{
		//we got the file!
		$file=$_GET['file'];
	}
	if(isset($_GET['direction']))
	{
		//word-translation or translation-word?
		$direction=$_GET['direction'];
	}
	if(isset($_GET['index']))
	{
		//we got the index!
		$index=$_GET['index'];
	}
	else
	{
		$index=0;
	}
	if(isset($_GET['referer']))
	{
		$referer=$_GET['referer'];
	}
	if(isset($_GET['part_answer']))
	{
		$part_answer=$_GET['part_answer'];
		//die();
	}
	if(isset($_GET['right']))
	{
		$right=$_GET['right'];
		//die();
	}
	if(isset($_GET['wrong']))
	{
		$wrong=$_GET['wrong'];
		//die();
	}

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
<center>
<?php
	echo("<h3><center></center>Wow youve completed $file!</center><br><br></h3>");
	echo("<h4><center>While learning you knew $right words!<br><br></center></h4>");
	if($wrong>0){
	echo("<h4><center>Sadly you didnt know $wrong words!<br><br></center></h4>");
	}
	else
	{
			echo("<h4><center>Wow you didnt make any errors. Im impressed!</center></h4><br><br>");
	}
	
	echo("<center><a href='voctr.php'>go back to learn hub</a></center>\n");
?>
</center>
</ul>
</body>
</html>
