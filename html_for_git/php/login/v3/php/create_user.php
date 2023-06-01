<?php
// Include config file
require_once "config.php";
 include "/var/www/html/php/login/v3/waf/waf_no_anti_xss.php";
// Define variables and initialize with empty values
$username = $password = $confirm_password = "";
$role="user";
$username_err = $password_err = $confirm_password_err = "";
$err="";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate username
    if(empty(trim($_POST["username"]))){
        $err = "Please enter a username.";
    } elseif(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))){
        $err = "Username can only contain letters, numbers, and underscores.";
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = trim($_POST["username"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $err = "This username is already taken.";
                } else{
                    $username = trim($_POST["username"]);
                    exec('mkdir /var/www/html/php/login/v3/cloud/cloud_v2'.$username);
                    exec('mkdir /var/www/html/php/login/v3/voctr/'.$username);
                    exec('mkdir /var/www/html/php/login/v3/voctr/'.$username.'/progress');
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Validate password
    if(empty(trim($_POST["password"]))){
        $err = "Please enter a password.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $err = "Password must have atleast 6 characters.";
    }
    else if(strlen(trim($_POST["new_password"])) > 96)
        {
            $login_err = "Password cannot have more than 96 characters.";
        } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $err = "Please confirm password.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($err) && ($password != $confirm_password)){
            $err = "Password did not match.";
        }
    }
    
    // Check input errors before inserting in database
    if(empty($err)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO users (username, password, role) VALUES (?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sss", $param_username, $param_password, $role);
            
            // Set parameters
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            $role="user";
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: /php/login/v3/login.php");
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Close connection
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
         <link rel="stylesheet" href="/php/login/css/style.css">
</head>
<body>
    <div class="wrapper">
    
  <div class="container">
    <h3 class="text-center">Create Account</h3>
    <form action="" method="post">
      <div class="form-group">
        <label for="username">New Username:</label>
        <input type="text" class="form-control" id="username" name="username" required>
      </div>
      <div class="form-group">
        <label for="pwd">New Password:</label>
        <input type="password" class="form-control" id="pwd" name="password" required>
      </div>
      <div class="form-group">
        <label for="pwd">Confirm New Password:</label>
        <input type="password" class="form-control" id="pwd" name="confirm_password" required>
      </div>
      <button type="submit" name="submit" class="btn btn-default">Create Account</button>
    </form>
    <p>By creating an acocunt you accept our <a href="/php/login/v3/php/privacy-policy.php">Privacy Policy</a></p>
    <center><p>Already have an account? <a href="../login.php">Login here</a>.</p></center>
  </div>
    <?php 
    if(!empty($err)){
        echo '<div class="alert alert-danger">' . $err . '</div>';
    }        
    ?>
</body>
</html>
