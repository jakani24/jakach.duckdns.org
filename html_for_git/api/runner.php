<?php
	session_start();
	include "/var/www/html/php/login/v3/waf/waf.php";
	if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
		header("location:/");
		exit;
	}
	else
	{
		if(isset($_GET["score"]))
		{
			$fp=fopen("runner.score","r");
			$score=(int)fgets($fp);
			fclose($fp);
			if($_GET["score"]>$score)
			{
				$fp=fopen("runner.score","w");
				fwrite($fp,$_GET["score"]."\n".$_SESSION["username"]);
				fclose($fp);				
			}
		}
		if(isset($_GET["get_score"]))
		{
			$fp=fopen("runner.score","r");
			$score=(int)fgets($fp);
			$user=fgets($fp);
			echo($score);
			echo(" ");
			echo($user);
			fclose($fp);		
		}
	}
?>
