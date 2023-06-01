<?php
	include_once "convert.php";
session_start();
 include "/var/www/html/php/login/v3/waf/waf.php";
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: ../login.php");
    exit;
}
	$username=$_SESSION["username"];
	$unwanted_ft=array("csv","voctr","txt");
	if(isset($_GET['delete']))
	{
		$unwanted_chr=[' ','(',')','/','\\','<','>',':',';','?','*','"','|','%'];
		$to_delete=$_GET['delete'];
		$to_delete=str_replace($unwanted_chr,"_",$to_delete);
		$cmd="cd /var/www/html/php/login/v3/voctr/$username/ && rm ".$to_delete;
		exec($cmd);
		//echo($cmd);
		unset($_GET['delete']);
	}
	if(!empty($_FILES['uploaded_file']))
	{
		$unwanted_chr=[' ','(',')','/','\\','<','>',':',';','?','*','"','|','%'];
		$filetype = strtolower(pathinfo($_FILES['uploaded_file']['name'],PATHINFO_EXTENSION));
		$path = "/var/www/html/php/login/v3/voctr/$username/";
		$filename=basename( $_FILES['uploaded_file']['name']);
		$filename=str_replace($unwanted_chr,"_",$filename);
		$path = $path . $filename;

		if(!in_array($filetype,$unwanted_ft))
		{
			echo "Sorry, this file extensions is not allowed!";
		}
		else if(in_array($filetype,$unwanted_ft))
		{
			if(move_uploaded_file($_FILES['uploaded_file']['tmp_name'], $path)) {
				echo "The file ".  basename( $_FILES['uploaded_file']['name']). " has been uploaded";
				convert_to_utf8($path);
			}
			else
			{
				echo "There was an error uploading the file, please try again!";
			}
			
		}
		
		 //echo mb_detect_encoding(file_get_contents($_FILES['uploaded_file']));
		unset($_FILES['uploaded_file']);
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
<h1>
	Welcome to the Jakach vocabulary station

</h1>

  <form enctype="multipart/form-data" action="voctr.php" method="POST">
    <p>Upload your new csv or voctr file</p>
    <input type="file" name="uploaded_file"></input>
    <input type="submit" value="Upload"></input>
  </form>

<p>Or choose one of the following sets you've allready uploaded!</p>
</center>
<?php
if ($handle = opendir("/var/www/html/php/login/v3/voctr/$username/")) {
    while (false !== ($entry = readdir($handle))) {
        if ($entry != "." && $entry != ".." &&$entry != "progress") {

            echo "<center><li><p>$entry...<a href='/php/login/v3/php/voctr/voctr.php?delete=$entry'>delete this set</a>...<a href='/php/login/v3/php/voctr/learn.php?file=$entry' >learn this set</a></li></p></center>\n";
        }
    }
    closedir($handle);
}
?>
</ul>
</body>
</html>
