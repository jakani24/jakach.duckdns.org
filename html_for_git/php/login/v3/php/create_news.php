<?php
include "/var/www/html/php/login/v3/waf/waf_no_anti_xss.php";
require_once "../waf/noscript.php";
session_start();
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"]!=="admin"){
    header("location: ../login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
    <head>
      <title>news</title>
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
        <p>Write an article for all users</p>
        <form action="create_news.php" method="post">
            <textarea type="text" name="text1" cols="40" rows="5"></textarea><br>
            <input type="submit">
        </form>
        
        
        <p>Write an article for admins</p>
        <form action="create_news.php" method="post">
            <textarea type="text" name="text2" cols="40" rows="5"></textarea><br>
            <input type="submit">
        </form>
        
        <?php  //echo($_POST["text1"]);  ?>


        </body>
</html>
<?php
    if(!empty($_POST['text1']))
    {
       file_put_contents("../news/users.txt",$_POST["text1"]);
        //file_put_contents("../news/users.txt",$_POST['WaferData']);
    }
    if(!empty($_POST['text2']))
    {
       file_put_contents("../news/admins.txt",$_POST["text2"]);
        //file_put_contents("../news/users.txt",$_POST['WaferData']);
    }
?>
<style>
textarea[type=text] {
    min-width:265px;
    min-height: 45px;
    border-width: 3px;
    border-color: rgba(50, 50, 50, 0.14);
    margin: 10px 10px 10px 0px;
}
</style>
