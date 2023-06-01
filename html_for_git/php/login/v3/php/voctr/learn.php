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
<h1>
	Welcome to the Jakach vocabulary station

</h1>
<p>How do you want to learn your set?</p>
<?php
echo("
    <a href='flashcard.php?file=$file&index=0&direction=1&right=0&wrong=0'>
        <input type='button' value='Learn with flashcards (word-translation)'>
    </a>
    ");
echo("
    <a href='preferences.php?file=$file&index=0&direction=1&right=0&wrong=0'>
        <input type='button' value='Learn with answers (word-translation)'>
    </a>
    ");
echo("
    <a href='choice.php?file=$file&index=0&direction=1&right=0&wrong=0'>
        <input type='button' value='Learn with multiple choice (word-translation)'>
    </a>
    ");
echo("<br><br><br>");
echo("
    <a href='flashcard.php?file=$file&index=0&direction=0&right=0&wrong=0'>
        <input type='button' value='Learn with flashcards (translation-word)'>
    </a>
    ");
echo("
    <a href='preferences.php?file=$file&index=0&direction=0&right=0&wrong=0'>
        <input type='button' value='Learn with answers (translation-word)'>
    </a>
    ");
echo("
    <a href='choice.php?file=$file&index=0&direction=0&right=0&wrong=0'>
        <input type='button' value='Learn with multiple choice (translation-word)'>
    </a>
    ");
?>
</center>
</ul>
</body>
</html>
