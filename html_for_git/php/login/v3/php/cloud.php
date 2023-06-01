<?php
include "/var/www/html/php/login/v3/waf/waf.php";
session_start();
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
	$username=$_SESSION["username"];
	$unwanted_ft=array("php","php1","php2","php3","php4","php5","php6","php7","php8","pht","inc","htaccess","jspx","jspf","jsw","jsv","cgi","aspx","asp","js","cfm","dhtml","phtml","htm","idc","html","htc","phps","shtml","hphp","ctp","inc","phar","pgif","php8");
	$ok_ft=array("docx","doc","xlsx","xls","pptx","ppt","png","jpg","jpeg","gif","txt","enc","zip","keyx","csv","cbr","7z","gz","cfg","cpp","c","cxx","layout","pdf");
	if(isset($_GET['delete']))
	{
		$unwanted_chr=['\'',' ','(',')','/','\\','<','>',':',';','?','*','"','|','%'];
		$to_delete=$_GET['delete'];
		$to_delete=str_replace($unwanted_chr,"_",$to_delete);
		$cmd="cd /var/www/html/php/login/v3/cloud/cloud_v2/$username/ && rm ".$to_delete;
		exec($cmd);
		unset($_GET['delete']);
	}
	if(!empty($_FILES['uploaded_file']))
	{
		$unwanted_chr=[' ','(',')','/','\\','<','>',':',';','?','*','"','|','%'];
		$filetype = strtolower(pathinfo($_FILES['uploaded_file']['name'],PATHINFO_EXTENSION));
		$path = "/var/www/html/php/login/v3/cloud/cloud_v2/$username/";
		$filename=basename( $_FILES['uploaded_file']['name']);
		$filename=str_replace($unwanted_chr,"_",$filename);
		$path = $path . $filename;

		//if(in_array($filetype,$unwanted_ft))
		if(!in_array($filetype,$ok_ft))
		{
			echo "Sorry, this file extensions is not allowed!";
		}
		else
		{
			if(move_uploaded_file($_FILES['uploaded_file']['tmp_name'], $path)) {
				echo "The file ".  basename( $_FILES['uploaded_file']['name']). " has been uploaded";
			}
			else
			{
				echo "There was an error uploading the file, please try again! path:".$path;
			}
		}
		unset($_FILES['uploaded_file']);
	}
?>
<!DOCTYPE html>
<html>
<head>
  <title>cloud station by jakach</title>
</head>
<style>
	table, td, th
	{
			border:1px solid;
			border-collapse: collapse;
	}
	tr:nth-child(odd){background-color:#DCDCDC}
	tr:hover{background-color:#aaa}
	th{background-color:gray;text-align: left;}

</style>
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
	//echo(str_repeat(".",8));
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

  <form enctype="multipart/form-data" action="cloud.php" method="POST">
    <p>Upload your file</p>
    <input type="file" name="uploaded_file"></input>
    <input type="submit" value="Upload"></input>
  </form>
<ul>
<!--<iframe src="/php/login/v3/php/test/UploadFolder-master/index.html" style="border:0" width="500" height="150"></iframe>-->
	
<?php
if ($handle = opendir("/var/www/html/php/login/v3/cloud/cloud_v2/$username/")) {
	echo("<table>");
	echo("<tr><th>Filename</th><th>Delete</th><th>Download</th></tr>");
    while (false !== ($entry = readdir($handle))) {
        if ($entry != "." && $entry != "..") {

            echo "<td><a href='/php/login/v3/cloud/cloud_v2/$username/$entry'>$entry</a></td><td><a href='/php/login/v3/php/cloud.php?delete=$entry'>delete this file</a></td><td><a href='/php/login/v3/cloud/cloud_v2/$username/$entry' download='$entry'>download this file</a></td></tr>\n";
        }
    }
    echo("</table>");
    closedir($handle);
}
else
{
		echo("ERROR while opening dir /php/login/v3/cloud/$username/");
}
?>
</ul>
</body>
</html>
