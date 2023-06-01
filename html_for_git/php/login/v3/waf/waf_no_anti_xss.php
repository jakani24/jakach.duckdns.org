<?php


if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
/*
 * 
 * Block message
 * 
 */
$block=false;
if($_SESSION["username"]!="janis" &&$block===true)
{
    echo("<p>We are sorry but jakach is currently down for maintenance</p>");
    die();
} 


    // Check for SQL injection
    foreach ($_POST as $key => $value) {
        if (preg_match('/(union|select|insert|update|delete|drop|create|rename|alter|\s+where\s+|\s+or\s+|\s+and\s+)/i', $value)) {
			echo("error-sql_post<br>");
            log_security_risk($value,$key);
            header('Location:/php/login/v3/waf/forbidden.php');
            exit;
        }
    }
    
       foreach ($_GET as $key => $value) {
        if (preg_match('/(union|select|insert|update|delete|drop|create|rename|alter|\s+where\s+|\s+or\s+|\s+and\s+)/i', $value)) {
			echo("error-sql_get<br>");
            log_security_risk($value,$key);
            header('Location:/php/login/v3/waf/forbidden.php');
            exit;
        }
    }


function log_security_risk($dangerous_value,$dangerous_key)
{
   if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
         $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
   }
   elseif(isset($_SERVER['HTTP_CLIENT_IP'])) {
         $ip = $_SERVER['HTTP_CLIENT_IP'];
   }
   else {
         $ip = $_SERVER['REMOTE_ADDR'];
   }
    $username=$_SESSION["username"];
    $file=$_SERVER["PHP_SELF"];
    $fp=fopen("/var/www/html/php/login/v3/log/waf.log","a");
    if($fp!=NULL)
    {
        fwrite($fp,date(DATE_RFC2822));
        fwrite($fp,"     ");
        fwrite($fp,$ip);
        fwrite($fp,"     ");
        fwrite($fp,$username);
        fwrite($fp,"     ");
        fwrite($fp,$dangerous_key);
        fwrite($fp,"     ");
        fwrite($fp,$dangerous_value);
        fwrite($fp,"     ");
        fwrite($fp,$file);
        fwrite($fp,"\n");
        fclose($fp);
    }
    $dangerous_key=  htmlspecialchars($dangerous_key);
    $dangerous_value=  htmlspecialchars($dangerous_value);
   // $text=urlencode("JWAF WARNING: user: $username;            ip: $ip;            request_name: $dangerous_key;            request_value: $dangerous_value;            END");
    $text=urlencode("JWAF WARNING:\nuser: $username;\nip: $ip;\nrequest_name: $dangerous_key;\nrequest_value: $dangerous_value;\nEND");
    exec("curl 'https://api.callmebot.com/whatsapp.php?phone=41775252026&text=$text&apikey=6002955'");
}

?>
