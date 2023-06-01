
<!DOCTYPE html>
<html>
<?php session_start();?>
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
	include "/var/www/html/php/login/v3/waf/waf.php";
	session_start();
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
<title>news page</title>
<center>
<h1>Welcome to the Jakach news site</h1>
<br>
<br>
<h2>
For all users including admins and normal users:
</h2>
<p>

<?php echo(file_get_contents("../news/users.txt")); ?>

</p>
<h2>
For admins:
</h2>
<p>
<?php echo(file_get_contents("../news/admins.txt")); ?>
</p>

</center>
</body>
</html>

