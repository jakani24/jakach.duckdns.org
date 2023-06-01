<?php
session_start();
 //include "/var/www/html/php/login/v3/waf/waf.php";
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
	if(isset($_GET['right']))
	{
		$right=$_GET['right'];
	}
	if(isset($_GET['wrong']))
	{
		$wrong=$_GET['wrong'];
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
<?php
	$i=0;
	$path="/var/www/html/php/login/v3/voctr/$username/$file";
	//echo($path);
	$file_ptr=fopen($path,'r');
	//$prog_file_ptr=fopen($prog_path,'r');
	while(!feof($file_ptr))
	{
		if(!feof($file_ptr))
		{
			$cnt+=1;
			fgets($file_ptr);
		}
	}
	rewind($file_ptr);
	while($i != $index+1 and !feof($file_ptr))
	{
		if(!feof($file_ptr))
		{
			$buf=fgets($file_ptr);
			$i += 1;
		}
	}
	
		$word= (explode(";",$buf));
		//echo($word[0]);
		//echo($word[1]);
		$temp_index=$index+1;
		echo("<h3>Progress: $temp_index / $cnt</h3><br>");
		if($referer==0)//flashcards
		{
			if($direction==1)//word-trans
			{
				echo("<center><h1>$word[0]</h1></center>");
				echo("is:");
				echo("<center><h1>$word[1]</h1></center>");
				$right+=1;
				echo("<center>
				    <a href='flashcard.php?file=$file&index=$temp_index&direction=1&right=$right&wrong=$wrong'>
					<input type='button' value='Knew it!'>
				    </a><br><br>
				    </center>");
				$wrong+=1;
				echo("<center>
				    <a href='flashcard.php?file=$file&index=$temp_index&direction=1&right=$right&wrong=$wrong'>
					<input type='button' value='didnt know it'>
				    </a><br><br>
				    </center>");
			}
			else		//trans-word
			{
				echo("<center><h1>$word[1]</h1></center>");
				echo("is:");
				echo("<center><h1>$word[0]</h1></center>");
				$right+=1;
				echo("<center>
				    <a href='flashcard.php?file=$file&index=$temp_index&direction=0&right=$right&wrong=$wrong'>
					<input type='button' value='Knew it!'>
				    </a><br><br>
				    </center>");
				 $wrong+=1;
				echo("<center>
				    <a href='flashcard.php?file=$file&index=$temp_index&direction=0&right=$right&wrong=$wrong'>
					<input type='button' value='didnt know it'>
				    </a><br><br>
				    </center>");
			}
		}
		if($referer==1)//answer
		{
			
			if($direction==1)//word-trans
			{
				echo("<center><h1>$word[0]</h1></center>");
				echo("is:");
				echo("<center><h1>$word[1]</h1></center>");
				echo("<center>
				    <a href='answer.php?file=$file&index=$temp_index&direction=1&part_answer=$part_answer&right=$right&wrong=$wrong'>
					<input type='button' value='Ok! Got it'>
				    </a>
				    </center>");
			}
			else		//trans-word
			{
				echo("<center><h1>$word[1]</h1></center>");
				echo("is:");
				echo("<center><h1>$word[0]</h1></center>");
				echo("<center>
				    <a href='answer.php?file=$file&index=$temp_index&direction=0&part_answer=$part_answer&right=$right&wrong=$wrong'>
					<input type='button' value='Ok! Got it'>
				    </a>
				    </center>");
			}
		}
		if($referer==2)//multiple choice
		{
			if($direction==1)//word-trans
			{
				echo("<center><h1>$word[0]</h1></center>");
				echo("is:");
				echo("<center><h1>$word[1]</h1></center>");
				echo("<center>
				    <a href='choice.php?file=$file&index=$temp_index&direction=1&correct=1&right=$right&wrong=$wrong'>
					<input type='button' value='Ok! Got it'>
				    </a>
				    </center>");
			}
			else		//trans-word
			{
				echo("<center><h1>$word[1]</h1></center>");
				echo("is:");
				echo("<center><h1>$word[0]</h1></center>");
				echo("<center>
				    <a href='choice.php?file=$file&index=$temp_index&direction=0&correct=1&right=$right&wrong=$wrong'>
					<input type='button' value='Ok! Got it'>
				    </a>
				    </center>");
			}
		}
?>
</center>
</ul>
</body>
</html>
