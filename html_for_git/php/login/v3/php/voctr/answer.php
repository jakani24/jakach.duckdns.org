<?php
// Initialize the session
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
	if(isset($_GET['correct'])) 
	{
		$correct=$_GET['correct'];
		//die();
	}
	if(isset($_GET['right']))
	{
		$right=$_GET['right'];
	}
	if(isset($_GET['wrong']))
	{
		$wrong=$_GET['wrong'];
	}
	if(isset($_GET['part_answer']))
	{
		$part_answer=$_GET['part_answer'];
		//die();
	}
	if(isset($_POST['trans']))
	{
		$trans=$_POST['trans'];
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
    function string_to_ascii($string)
    {
        $ascii = NULL;
     
        for ($i = 0; $i < strlen($string); $i++) 
        { 
        	echo(ord($string[$i]));
        	echo("<br>"); 
        }
     
        return($ascii);
    }
    ?>
<?php
	$i=0;
	$cnt=0;
	if($correct==0)
	{
		$index += 1;
	}
	$path="/var/www/html/php/login/v3/voctr/$username/$file";
	//$prog_path="/var/www/html/php/login/v2/voctr/$username/progress/$file";
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
	while($i != $index and !feof($file_ptr))
	{
		if(!feof($file_ptr))
		{
			$buf=fgets($file_ptr);
			//$r_cnt=fgets($prog_file_ptr);
			$i += 1;
		}
	}
	if(!feof($file_ptr) and $correct==0)
	{
		//$r_cnt=substr_replace($r_cnt ,"", -1);
		//$r_cnt_i= (int)$r_cnt;
		//echo($r_cnt);
		$word= (explode(";",$buf));
		$temp_index=$index-1;
		//echo($word[0]);
		//echo($word[1]);
		$temp_cnt=$cnt-1;
		echo("<h3>Progress: $index / $temp_cnt</h3><br>");
		if($direction==1)//word-trans
		{
			echo("<center><h1>".htmlspecialchars($word[0])."</h1></center>");
			echo("<center><form action='answer.php?file=$file&index=$index&direction=1&correct=1&part_answer=$part_answer&right=$right&wrong=$wrong' method='post'>
			Translation: <input type='text' name='trans'>
			<input type='submit'>
			</form>
			</center>");
			
		}
		else		//trans-word
		{
			echo("<center><h1>".htmlspecialchars($word[1])."</h1></center>");
			echo("<center><form action='answer.php?file=$file&index=$index&direction=0&correct=1&part_answer=$part_answer&right=$right&wrong=$wrong' method='post'>
			Translation: <input type='text' name='trans'>
			<input type='submit'>
			</form>
			</center>");


		}
		fclose($file_ptr);
	}
	else
	{
		if($correct==0)
		{
			header("LOCATION:end.php?right=$right&wrong=$wrong&file=$file");	
			//echo("<center><p>Well done! Youve learned this set, continue learning to get best in class</p><br><a href='voctr.php'>go back to learn hub</a></center>\n");	
		}
		else
		{
			//$word=substr_replace($word ,"", -1);
			$word=(explode(";",$buf));
			$temp_index=$index-1;
			//$index +=1;
			//echo($temp_index);
			//echo("word:");
			//echo($word[1]);
			//echo("<br>translation:");
			//echo($trans);
			//echo("<br>");
			//string_to_ascii($trans);
			echo("<br><br>");
			//string_to_ascii($word[1]);
			if($direction==1)
			{
				if($part_answer==0)
				{
					$trans.="\r\n";
					if($trans==$word[1])
					{
						$right+=1;
						header("LOCATION:answer.php?file=$file&index=$index&direction=1&correct=0&part_answer=0&right=$right&wrong=$wrong");
						//die();
						echo("test");
					}
					else
					{
						$wrong+=1;
						$index=$index-1;
						header("LOCATION:show.php?file=$file&index=$temp_index&direction=1&referer=1&part_answer=0&right=$right&wrong=$wrong");
					}
				}
				if($part_answer==1)
				{
					//if($trans==$word[1])
					if(str_contains($word[1],$trans))
					{
						$right+=1;
						header("LOCATION:answer.php?file=$file&index=$index&direction=1&correct=0&part_answer=1&right=$right&wrong=$wrong");
						//die();
						echo("test");
					}
					else
					{
						$wrong+=1;
						$index=$index-1;
						header("LOCATION:show.php?file=$file&index=$temp_index&direction=1&referer=1&part_answer=1&right=$right&wrong=$wrong");
						echo("<br>test2");
					}				
				}
			}
			else
			{
				if($part_answer==0)
				{
					if($word[0]==$trans)
					{
						$right+=1;
						header("LOCATION:answer.php?file=$file&index=$index&direction=0&correct=0&part_answer=0&right=$right&wrong=$wrong");
					}
					else
					{
						$wrong+=1;
						$index=$index-1;
						header("LOCATION:show.php?file=$file&index=$temp_index&direction=0&referer=1&part_answer=0&right=$right&wrong=$wrong");
					}
				}
				if($part_answer==1)
				{
					//if($trans==$word[1])
					if(str_contains($word[0],$trans))
					{
						$right+=1;
						header("LOCATION:answer.php?file=$file&index=$index&direction=1&correct=0&part_answer=1&right=$right&wrong=$wrong");
					}
					else
					{
						$wrong+=1;
						$index=$index-1;
						header("LOCATION:show.php?file=$file&index=$temp_index&direction=1&referer=1&part_answer=1&right=$right&wrong=$wrong");
					}				
				}
			}
		}
	}
?>
</center>
</ul>
</body>
</html>
