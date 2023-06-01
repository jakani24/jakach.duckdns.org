<?php
	session_start();
	require_once "config.php";
	if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
		header("location:../login.php");
		exit;
	}
	$username=$_SESSION["username"];
	exec("mkdir users/$username");
	$fp=fopen("id/$username.id","w");
	fclose($fp);
	echo("<center>Success</center><br><br>");
	echo("<center>Account activated!<br></center>");
	echo("<center><a href='main.php'>Back to dashboard</a></center>");
?>
