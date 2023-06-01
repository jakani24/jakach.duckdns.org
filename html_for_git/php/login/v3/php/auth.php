<?php
include "/var/www/html/php/login/v3/waf/waf.php";
function auth_login()
{
	if(isset($_COOKIE['password']) and isset($_COOKIE['username']))
	{
		$a_hash=$_COOKIE['password'];
		$a_username=$_COOKIE['username'];
		$a_pwfile=fopen('/var/www/html/php/login/v2/database/login.txt','r');
		$a_exists=false;
		        while(!feof($a_pwfile))
		        {
		                if($a_exists==false)
		                {
		                        $a_iusername=fgets($a_pwfile);
		                        $a_ipassword=fgets($a_pwfile);
					$a_isalt=fgets($a_pwfile);
		                        $a_irole=fgets($a_pwfile);
		                }
		                else
		                {
		                        fgets($a_pwfile);
		                        fgets($a_pwfile);
					fgets($a_pwfile);
		                        fgets($a_pwfile);
		                }
		                if($a_username==$a_iusername)
		                {
		                        $a_exists=true;
		                }
				fgets($a_pwfile);
		        }
		        if($a_exists== true)
		        {
		                if($a_hash == $a_ipassword)
		                {
		                	//user logged in
					if($a_irole=="admin\n")
					{
						header('LOCATION:/php/login/v2/admin.php');
					}
					if($a_irole=="user\n")
					{
		                        	header('LOCATION:/php/login/v2/user.php');
		                        	die();
					}
		                }
		        }
		        fclose($a_pwfile);
	}
}

function get_role()
{
	if(isset($_COOKIE['password']) and isset($_COOKIE['username']))
	{
		$a_hash=$_COOKIE['password'];
		$a_username=$_COOKIE['username'];
		$a_pwfile=fopen('/var/www/html/php/login/v2/database/login.txt','r');
		$a_exists=false;
		        while(!feof($a_pwfile))
		        {
		                if($a_exists==false)
		                {
		                        $a_iusername=fgets($a_pwfile);
		                        $a_ipassword=fgets($a_pwfile);
					$a_isalt=fgets($a_pwfile);
		                        $a_irole=fgets($a_pwfile);
		                }
		                else
		                {
		                        fgets($a_pwfile);
		                        fgets($a_pwfile);
					fgets($a_pwfile);
		                        fgets($a_pwfile);
		                }
		                if($a_username==$a_iusername)
		                {
		                        $a_exists=true;
		                }
				fgets($a_pwfile);
		        }
		        if($a_exists== true)
		        {
		                if($a_hash == $a_ipassword)
		                {
					//all ok
		                }
		                else
		                {
		                        header('LOCATION:/php/login/v2/login.php');
					die();
		                }
		        }
		        else
		        {
		             	header('LOCATION:/php/login/v2/login.php');
		        	die();
		        }
		        fclose($a_pwfile);
	}
	else
	{
		header('LOCATION:/php/login/v2/login.php');
		die();
	}
	return $a_irole;

}

function auth_admin()
{
	if(isset($_COOKIE['password']) and isset($_COOKIE['username']))
	{
		$a_hash=$_COOKIE['password'];
		$a_username=$_COOKIE['username'];
		$a_pwfile=fopen('/var/www/html/php/login/v2/database/login.txt','r');
		$a_exists=false;
		        while(!feof($a_pwfile))
		        {
		                if($a_exists==false)
		                {
		                        $a_iusername=fgets($a_pwfile);
		                        $a_ipassword=fgets($a_pwfile);
					$a_isalt=fgets($a_pwfile);
		                        $a_irole=fgets($a_pwfile);
		                }
		                else
		                {
		                        fgets($a_pwfile);
		                        fgets($a_pwfile);
					fgets($a_pwfile);
		                        fgets($a_pwfile);
		                }
		                if($a_username==$a_iusername)
		                {
		                        $a_exists=true;
		                }
				fgets($a_pwfile);
		        }
		        if($a_exists== true)
		        {
		                if($a_hash == $a_ipassword)
		                {
		                	//user logged in
					if($a_irole=="admin\n")
					{
						//ok
					}
					else
					{
		                        	header('LOCATION:/php/login/v2/login.php');
		                        	die();
					}
		                }
		                else
		                {
		                        header('LOCATION:/php/login/v2/login.php');
					die();
		                }
		        }
		        else
		        {
		             	header('LOCATION:/php/login/v2/login.php');
		        	die();
		        }
		        fclose($a_pwfile);
	}
	else
	{
		header('LOCATION:/php/login/v2/login.php');
		die();
	}
	return $a_username;
}

function auth_user()
{
	if(isset($_COOKIE['password']) and isset($_COOKIE['username']))
	{
		$a_hash=$_COOKIE['password'];
		$a_username=$_COOKIE['username'];
		$a_pwfile=fopen('/var/www/html/php/login/v2/database/login.txt','r');
		$a_exists=false;
		        while(!feof($a_pwfile))
		        {
		                if($a_exists==false)
		                {
		                        $a_iusername=fgets($a_pwfile);
		                        $a_ipassword=fgets($a_pwfile);
					$a_isalt=fgets($a_pwfile);
		                        $a_irole=fgets($a_pwfile);
		                }
		                else
		                {
		                        fgets($a_pwfile);
		                        fgets($a_pwfile);
					fgets($a_pwfile);
		                        fgets($a_pwfile);
		                }
		                if($a_username==$a_iusername)
		                {
		                        $a_exists=true;
		                }
				fgets($a_pwfile);
		        }
		        if($a_exists== true)
		        {
		                if($a_hash == $a_ipassword)
		                {
		                	//user logged in
					if($a_irole=="admin\n" or $a_irole=="user\n")
					{
						//ok
					}
					else
					{
		                        	header('LOCATION:/php/login/v2/login.php');
		                        	die();
					}
		                }
		                else
		                {
		                        header('LOCATION:/php/login/v2/login.php');
					die();
		                }
		        }
		        else
		        {
		             	header('LOCATION:/php/login/v2/login.php');
		        	die();
		        }
		        fclose($a_pwfile);
	}
	else
	{
		header('LOCATION:/php/login/v2/login.php');
		die();
	}
	return $a_username;
}

?>
