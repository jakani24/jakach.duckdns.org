<?php
// Initialize the session
session_start();
include "/var/www/html/php/login/v3/waf/waf.php";
 require_once "php/config.php";
 require_once "keepmeloggedin.php";
$error=logmein($link);
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"]!== "admin"){
    header("location: login.php");
    exit;
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>

</head>
<?php $color=$_SESSION["color"]; ?>
<?php echo(" <body style='background-color:$color'> ");?>
    <script src="/php/login/v3/js/load_page.js"></script>
     <script>
     	function load_user()
        {
            $(document).ready(function(){
            $('#content').load("/php/login/v3/html/admin_page.html");
            });
        }
        load_user();
     </script>
    <div id="content"></div>
    <h1 class="my-5"> <center>Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>.</center></h1>
    <p>
        <center>
        <label>Currently it is</label>
        <span id="span"></span>
        </center>

    </p>
</body>
</html>

<html>
    <div style="overflow-x: auto;">
    <center>
	<link rel="stylesheet" href="/trex-runner.css">

    <div class="interstitial-wrapper"></div>

    <div id="offline-resources">
      <img id="offline-resources-1x" src="/images/100-percent/100-offline-sprite.png">
      <img id="offline-resources-2x" src="/images/200-percent/200-offline-sprite.png">
      <div id="audio-resources">
        <audio id="offline-sound-press" src="/sounds/button-press.mp3"></audio>
        <audio id="offline-sound-hit" src="/sounds/hit.mp3"></audio>
        <audio id="offline-sound-reached" src="/sounds/score-reached.mp3"></audio>
      </div>
    </div>

    <script src="/trex-runner.js"></script>
    <script>
      new Runner('.interstitial-wrapper');
    </script>
    </center>
    </div>
    <?php
    	echo("<center>");
		$fp=fopen("/var/www/html/api/runner.score","r");
		$score=(int)fgets($fp);
		$user=fgets($fp);
		echo("<p>Zurzeit ist <b>$user</b> mit einem Score von <b>$score</b> der beste Spieler<br>*Bitte lade die Seite neu, um das Leaderboard zu aktualisieren</p>");
		fclose($fp);	   	
    	
    	echo("</center>");
    
    ?>
    <br><br><br>
</html>
<script>
var span = document.getElementById('span');

function time() {
  var d = new Date();
  var s = d.getSeconds();
  var m = d.getMinutes();
  var h = d.getHours();
  span.textContent = 
    ("0" + h).substr(-2) + ":" + ("0" + m).substr(-2) + ":" + ("0" + s).substr(-2);
}
time()
setInterval(time, 1000);
</script>
