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
	if(isset($_GET['right']))
	{
		$right=$_GET['right'];
	}
	if(isset($_GET['wrong']))
	{
		$wrong=$_GET['wrong'];
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
	$i=0;
	$cnt=0;
	$index += 1;
	$path="/var/www/html/php/login/v3/voctr/$username/$file";
	//$prog_path="/var/www/html/php/login/v2/voctr/$username/progress/$file";
	//echo($path);
	$file_ptr=fopen($path,'r');
	//$prog_file_ptr=fopen($prog_path,'r');
	while(!feof($file_ptr))
	{
		//if(!feof($file_ptr))
		//{
			$cnt+=1;
			fgets($file_ptr);
		//}
	}
	rewind($file_ptr);
	while($i != $index and !feof($file_ptr))
	{
		//if(!feof($file_ptr))
		//{
			$buf=fgets($file_ptr);
			//$r_cnt=fgets($prog_file_ptr);
			$i += 1;
			//echo ($i);
			//echo("<br>");
		//}
	}
	if(!feof($file_ptr))
	{
		//$r_cnt=substr_replace($r_cnt ,"", -1);
		//$r_cnt_i= (int)$r_cnt;
		//echo($r_cnt);
		$word= (explode(";",$buf));
		$temp_index=$index-1;
		$temp_cnt=$cnt-1;
		//echo($word[0]);
		//echo($word[1]);
		echo("<h3>Progress: $index / $temp_cnt</h3>");
		if($direction==1)//word-trans
		{
			echo("<center><h1>".htmlspecialchars($word[0])."</h1></center>");
			//echo("<center>
			//    <a href='flashcard.php?file=$file&index=$index&direction=1'>
			//	<input type='button' value='Know it, continue with next word'>
			//    </a><br><br>
			//    </center>");
			echo("<center>
			    <a href='show.php?file=$file&index=$temp_index&direction=1&referer=0&right=$right&wrong=$wrong'>
				<input type='button' value='Turn card'>
			    </a><br><br>
			    </center>");
		}
		else		//trans-word
		{
			echo("<center><h1>".htmlspecialchars($word[1])."</h1></center>");
			//echo("<center>
			//    <a href='flashcard.php?file=$file&index=$index&direction=0'>
			//	<input type='button' value='Know it, continue with next word'>
			//    </a><br><br>
			//    </center>");
			echo("<center>
			    <a href='show.php?file=$file&index=$temp_index&direction=0&referer=0&right=$right&wrong=$wrong'>
				<input type='button' value='Turn card'>
			    </a><br><br>
			    </center>");
		}
	}
	else
	{
		//header("LOCATION:end.php");
		header("LOCATION:end.php?right=$right&wrong=$wrong&file=$file");	
		//echo("<center><p>Well done! Youve learned this set, continue learning to get best in class</p><br><a href='voctr.php'>go back to learn hub</a></center>\n");	
	}
	fclose($file_ptr);
?>
</center>
</ul>
</body>
</html>
