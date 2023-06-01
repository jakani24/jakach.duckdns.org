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
	if(isset($_GET['correct']))
	{
		//word-translation or translation-word?
		$correct=$_GET['correct'];
	}
	else
	{
		$correct=1;
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
	$rand_num1=rand(1,$cnt-1);
	$rand_num2=rand(1,$cnt-1);
	$rand_num3=rand(1,$cnt-1);
	while($rand_num1==$rand_num2 or $rand_num1==$index)
	{
		$rand_num1=rand(1,$cnt-1);
	}
	while($rand_num3==$rand_num2 or $rand_num2==$index)
	{
		$rand_num2=rand(1,$cnt-1);
	}
	while($rand_num3==$rand_num1 or $rand_num3==$index)
	{
		$rand_num3=rand(1,$cnt-1);
	}

	while(!feof($file_ptr))
	{
		if(!feof($file_ptr))
		{
			$buf=fgets($file_ptr);
			//$r_cnt=fgets($prog_file_ptr);
			$i += 1;
			if($i==$rand_num1)
			{
				$buf1=$buf;
				//echo("*1");
			}
			if($i==$rand_num2)
			{
				$buf2=$buf;
				//echo("*2");
			}
			if($i==$rand_num3)
			{
				$buf3=$buf;
				//echo("*3");
			}
			if($i==$index)
			{
				$buf4=$buf;
			}
		}
	}
	if($index!=$cnt and $correct==1)
	{
		$right+=1;
		//$r_cnt=substr_replace($r_cnt ,"", -1);
		//$r_cnt_i= (int)$r_cnt;
		//echo($r_cnt);
		$word= (explode(";",$buf4));
		$word1= (explode(";",$buf1));
		$word2= (explode(";",$buf2));
		$word3= (explode(";",$buf3));
		$temp_index=$index-1;
		$temp_cnt=$cnt-1;
		echo("<h3>Progress: $index / $temp_cnt</h3><br>");

		if($direction==1)//word-trans
		{
			echo("<center><h1>".htmlspecialchars($word[0])."</h1></center>");
			$rand_num=rand(1,3);
			if($rand_num==1)
			{
			echo("
			    <a href='choice.php?file=$file&index=$index&direction=1&correct=1&right=$right&wrong=$wrong'>
				<input type='button' value='$word[1]'>
			    </a><br><br>
			    ");
			}
			else
			{
			echo("
			    <a href='choice.php?file=$file&index=$index&direction=1&correct=0&right=$right&wrong=$wrong'>
				<input type='button' value='$word1[1]'>
			    </a><br><br>
			    ");			
			}
			if($rand_num==2)
			{
			echo("
			    <a href='choice.php?file=$file&index=$index&direction=1&correct=1&right=$right&wrong=$wrong'>
				<input type='button' value='$word[1]'>
			    </a><br><br>
			    ");
			}
			else
			{
			echo("
			    <a href='choice.php?file=$file&index=$index&direction=1&correct=0&right=$right&wrong=$wrong'>
				<input type='button' value='$word2[1]'>
			    </a><br><br>
			    ");			
			}
			if($rand_num==3)
			{
			echo("
			    <a href='choice.php?file=$file&index=$index&direction=1&correct=1&right=$right&wrong=$wrong'>
				<input type='button' value='$word[1]'>
			    </a><br><br>
			    ");
			}
			else
			{
			echo("
			    <a href='choice.php?file=$file&index=$index&direction=1&correct=0&right=$right&wrong=$wrong'>
				<input type='button' value='$word3[1]'>
			    </a><br><br>
			    ");			
			}
			
		}
		else		//trans-word
		{
			echo("<center><h1>".htmlspecialchars($word[1])."</h1></center>");
			$rand_num=rand(1,3);
			if($rand_num==1)
			{
			echo("
			    <a href='choice.php?file=$file&index=$index&direction=0&correct=1&right=$right&wrong=$wrong'>
				<input type='button' value='$word[0]'>
			    </a><br><br>
			    ");
			}
			else
			{
			echo("
			    <a href='choice.php?file=$file&index=$index&direction=0&correct=0&right=$right&wrong=$wrong'>
				<input type='button' value='$word1[0]'>
			    </a><br><br>
			    ");			
			}
			if($rand_num==2)
			{
			echo("
			    <a href='choice.php?file=$file&index=$index&direction=0&correct=1&right=$right&wrong=$wrong'>
				<input type='button' value='$word[0]'>
			    </a><br><br>
			    ");
			}
			else
			{
			echo("
			    <a href='choice.php?file=$file&index=$index&direction=0&correct=0&right=$right&wrong=$wrong'>
				<input type='button' value='$word2[0]'>
			    </a><br><br>
			    ");			
			}
			if($rand_num==3)
			{
			echo("
			    <a href='choice.php?file=$file&index=$index&direction=0&correct=1&right=$right&wrong=$wrong'>
				<input type='button' value='$word[0]'>
			    </a><br><br>
			    ");
			}
			else
			{
			echo("
			    <a href='choice.php?file=$file&index=$index&direction=0&correct=0&right=$right&wrong=$wrong'>
				<input type='button' value='$word3[0]'>
			    </a><br><br>
			    ");			
			}


		}
		fclose($file_ptr);
	}
	else
	{
		if($correct==1)
		{
			//echo("<center><p>Well done! Youve learned this set, continue learning to get best in class</p><br><a href='voctr.php'>go back to learn hub</a></center>\n");
			header("LOCATION:end.php?right=$right&wrong=$wrong&file=$file");	
		}
		else
		{
			$wrong+=1;
			$right-=1;
			$temp_index=$index-2;
			header("LOCATION:show.php?file=$file&index=$temp_index&direction=$direction&referer=2&right=$right&wrong=$wrong");
		}
	}
?>
</center>
</ul>
</body>
</html>
