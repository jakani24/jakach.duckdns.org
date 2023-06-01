<!--
DB structure:
	theme
	date
	grade
-->

<?php

	session_start();
	require_once "config.php";
	include "/var/www/html/php/login/v3/waf/waf.php";
	if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
		header("location:../login.php");
		exit;
	}
	$username=$_SESSION["username"];
	//if($username!="janis" && $username!="grades")
	//{
	//	die("Jakach.com is currently down for maintenance! Please come back later");
	//}
?>
<!DOCTYPE html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Schulnetz v2</title>
</head>
<style>
		* {
		  box-sizing: border-box;
		}
		table, td, th
		{
				border:1px solid;
				border-collapse: collapse;
				height:22px;
		}
		tr:nth-child(odd){background-color:#DCDCDC}
		tr:hover{background-color:#aaa}
		th{background-color:gray;text-align: left;}
		
		.grades {
		  float: left;
		  width: 33.33%;
		}
		.grades_master 
		{
		  float: left;
		  width: 33.33%;
		  padding: 15px;			
		}
		.grades_input
		{
		  float: left;
		  width: 33.33%;
		  padding: 15px;				
		}
		.grades_get
		{
			width:100%;
			float:left;
		}
		@media screen and (max-width:1080px) {
		  .grades {
			width: 100%;
		  }
		}
		@media screen and (max-width:1080px) {
		  .grades_master {
			width: 100%;
		  }
		}
		@media screen and (max-width:1080px) {
		  .grades_input {
			width: 100%;
		  }
		}
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
	<?php
	if(isset($_POST["grade"]))
	{
		$subject=$_POST["subject"];
		$autoselect=$subject;
		$grade=$_POST["grade"];
		$theme=htmlspecialchars($_POST["theme"]);
		$date= htmlspecialchars($_POST["date"]);
		$weight=(float)htmlspecialchars($_POST["weight"]);
		$grade_to_file=(float)$grade;
		if($grade_to_file>6)
		{
			$grade_to_file=6;
		}
		if($grade_to_file<0)
		{
			$grade_to_file=0;
		}
		$fp=fopen("users/$username/$subject","a");
		fwrite($fp,$theme."\n");
		fwrite($fp,$date."\n");
		fwrite($fp,$grade_to_file."\n");
		fwrite($fp,$weight."\n");
		fclose($fp);
		unset($_POST["grade"]);
		
	}
	if(isset($_POST["sub"]))
	{
		$sub=$_POST["sub"];
		$fp=fopen("id/$username.id","a");
		fwrite($fp,htmlspecialchars($sub)."\n");
		if($_POST["counts"]=="counts")
		{
			fwrite($fp,"1\n");
		}
		else
		{
			fwrite($fp,"0\n");
		}
		fclose($fp);
		$fp=fopen("users/$username/$sub","w");
		if($fp==NULL)
		{
			die("ERROR; could not create database!");
		}
		else
		{
			fwrite($fp,"\n");
			fclose($fp);
		}
	}
	
	?>
	<div class="grades_master">
		<center>
		<h1>Pluspunkte</h1>
		<!--gesamt-->
		<h3>Gesamte Pluspunkte</h3>
		<?php
			$points_neg=0;
			$all_grades=0;
			$all_grades_cnt=0;
			$points=0;
			$fp=fopen("id/$username.id","r");
			$filename="";
			if($fp==NULL)
			{
				echo("<br><h1><a href='activate.php'>Register my account for schulnetz system (BETA)</a><br></h1>");
				//echo("<style>.grades_master{visible='false'}</style>");
				die("<br>Please register before using Schulnetz v2");
			}
			while(!feof($fp))
			{
					$filename=fgets($fp);
					$counts=(int)fgets($fp);
					//$counts=1;
					//$filename="";
					if(!feof($fp))
					{
						$filename=substr($filename, 0, -1);
						$grf=fopen("users/$username/$filename","r");
						$grade_cur=0;
						$grade_cnt=0;
						$grade_add=(float)fgets($grf);
						while(!feof($grf))
						{
							$grade_add=(int)fgets($grf);//other data
							$grade_add=(int)fgets($grf);//other data
							$grade_add=(float)fgets($grf);//grade
							$weight=(float)fgets($grf);//weight of the grade
							if($weight==0)
							{
								$weight=1;
							}
							if(!feof($grf))
							{
								$grade_cur+=($grade_add*($weight*100));

							}
							$grade_cnt=$grade_cnt+(100*$weight);
						}
						if($grade_cnt<=100)
						{
							$grade_cnt=101;//to prevent div by 0
						}
						if($counts==1)
						{
							$grade_media=(float)$grade_cur/($grade_cnt-100);
							$all_grades+=$grade_media;
							$all_grades_cnt++;
						}
						else
						{
							$grade_media=0;
						}
						//echo($grade_media);
						if($grade_media>=4 && $grade_media!==0)
						{
							$points+=round((($grade_media-4)*2), 0)/2;
						}
						else if($grade_media!=0)
						{
							$points-=round(((4-$grade_media)*2), 0);
							$points_neg-=round(((4-$grade_media)*2), 0);
						}
					}
			}	
			//$points=round($points,1);
			echo("<table style='text-align: center;'><tr><th>neg.</th><th>media</th><th>pos.</th></tr>");
			echo("<tr>");
			echo("<td><p style='color:red'>$points_neg</p></td>");
			if($points<0)
			{
					echo("<td><p style='color:red'>$points</p></td>");
			}	
			else if($points>=8)
			{
					echo("<td><p style='color:green'>$points</p></td>");
			}
			else if($points>0)
			{
					echo("<td><p style='color:orange'>$points</p></td>");
			}
			else if($points==0)
			{
					echo("<p>$points</p>");
			}
			echo("<td><p style='color:green;'>".$points-$points_neg."</p></td>");
			echo("</tr></table>");			
		?>
		<h3>Gesamtdurchschnittsnote der Promotionsfächer</h3>
		<?php
			if($all_grades_cnt==0)
			{
				$all_grades_cnt=1;
			}
			$all_time_media=round($all_grades/$all_grades_cnt,3);
			if($all_time_media<4)
			{
					echo("<p style='color:red'>$all_time_media</p>");
			}	
			else if($all_time_media>5)
			{
					echo("<p style='color:green'>$all_time_media</p>");
			}
			else if($all_time_media>4)
			{
					echo("<p style='color:orange'>$all_time_media</p>");
			}
			//echo("<p style='color:red'>".$all_time_media."</p>");
		?>
		</center>
	</div>
	<div class="grades">
		<center>
			<h1>Noten Dashboard</h1>
			<!-- Notendurchschnitte anzeigen -->
			<div style="overflow-x: auto;">
			<table style="cursor: pointer;">
				<tr>
					<th>
						Fach
					</th>
					<th>
						Notendurchschnitt
					</th>
					<th>
						Zeugnisnote
					</th>
					<!--<th>
						Genaue Daten
					</th>-->
				</tr>
				<?php
				//alle notendurchschnitte bekommen und errechnen
					$fp=fopen("id/$username.id","r");
					$filename="";
					if($fp==NULL)
					{
						die("Could not open file!");
					}
					while(!feof($fp))
					{
							$filename=fgets($fp);
							$counts=fgets($fp);
							if(!feof($fp) && $filename!="\n")
							{
								$filename=substr($filename, 0, -1);
								$grf=fopen("users/$username/$filename","r");
								$grade_cur=0;
								$grade_cnt=0;
								$grade_add=(float)fgets($grf);
								while(!feof($grf))
								{
									$grade_add=(int)fgets($grf);//other data
									$grade_add=(int)fgets($grf);//other data
									$grade_add=(float)fgets($grf);
									$weight=(float)fgets($grf);
									if($weight==0)
									{
										$weight=1;
									}
									if(!feof($grf))
									{
										$grade_cur+=($grade_add*($weight*100));
									}
									$grade_cnt=$grade_cnt+(100*$weight);
								}
								if($grade_cnt<=100)
								{
									$grade_cnt=101;//to prevent div by 0
								}
								fclose($grf);
								$grade_media=(float)$grade_cur/($grade_cnt-100);
								//echo($grade_cur);
								$grade_media=round($grade_media,3);
								$counts=substr_replace($counts,"",-1);
								if($grade_media==0)
								{
									$grade_media="-";
									$col="black";
								}
								else if(round((($grade_media)*2), 0)/2 >=5)
								{
									$col="green";
								}
								else if(round((($grade_media)*2), 0)/2 <4)
								{
									$col="red";
								}
								else
								{
									$col="orange";
								}
								if($grade_media=="-")
								{
									echo("<tr onclick='document.location=\"grades.php?subject=$filename&counts=$counts\";'><td>$filename</td><td><label style='color:black'>$grade_media</label></td><td>-</td></tr>");//<td><a href='grades.php?subject=$filename&counts=$counts'>Genaue Daten</a></td>
								}
								else if($grade_media>=5)
								{
									echo("<tr onclick='document.location=\"grades.php?subject=$filename&counts=$counts\";'><td>$filename</td><td><label style='color:green'>$grade_media</label></td><td><label style='color:$col'>".round((($grade_media)*2), 0)/2 ."</label></td></tr>");
								}
								else if($grade_media<4)
								{
									echo("<tr onclick='document.location=\"grades.php?subject=$filename&counts=$counts\";'><td>$filename</td><td><label style='color:red'>$grade_media</label></td><td><label style='color:$col'>".round((($grade_media)*2), 0)/2 ."</label></td></tr>");
								}
								else
								{
									echo("<tr onclick='document.location=\"grades.php?subject=$filename&counts=$counts\";'><td>$filename</td><td><label style='color:orange'>$grade_media</label></td><td><label style='color:$col'>".round((($grade_media)*2), 0)/2 ."</label></td></tr>");
								}
							}
					}
					fclose($fp);
					echo("</table>");
					echo("Drücke auf irgendein Fach für Detailansicht");
					echo("</div><br><br>");
				?>
				</center>
			</div>
			<div class="grades_input">
			<center>
			<!-- Noten hinzufügen -->
			<h1>Daten hinzufügen</h1>
			<h3>Note hinzufügen</h3>
			<?php
				$sub="";
				$fp=fopen("id/$username.id","r");
				echo('<form action="main.php" method="post">');
				echo('<label for="subject">Fach auswählen:</label>');
				echo('<select id="subject" name="subject" required>');
				$cnt_=0;
				while(!feof($fp))
				{
					$sub=substr(fgets($fp), 0, -1);
					//$cnt_=0;
					if(!feof($fp))
					{
						$cnt_++;
						if($cnt_ % 2 !=0)
						{
							if($sub==$autoselect)
							{
								echo('<option value="'.$sub.'" selected>'.$sub.'</option>');
							}
							else
							{
								echo('<option value="'.$sub.'">'.$sub.'</option>');
							}
						}
					}
				}
				fclose($fp);
				echo("</select>");
				echo("<br>");
				echo('<label for="grade">Note:</label>');
				echo('<input type="number" step="0.01" min="1" max="6" id="grade" name="grade" required>');
				echo("<br><br>");
				echo('<label for="date">Datum:</label>');
				echo('<input type="date" id="date" name="date">');
				echo("<br><br>");
				echo('<label for="theme">Thema:</label>');
				echo('<input type="text" id="theme" name="theme">');
				echo("<br><br>");
				echo('<label for="weight">Gewichtung:</label>');
				echo('<input type="text" id="weight" name="weight" value="1">');
				echo("<br><br>");
				echo('<input type="submit" value="hinzufügen">');
				echo('</form>');

			?>
			<br><br>
			<h3>Fach hinzufügen</h3>
			<?php
				echo('<form action="main.php" method="post">');
				echo('<label for="sub">Fach Name:</label>');
				echo('<input type="text" id="sub" name="sub" required>');
				echo("<br>");
				echo('<input type="checkbox" id="counts" name="counts" value="counts">
				<label for="counts"> Dieses Fach ist ein Promotionsfach</label><br>');
				echo('<input type="submit" value="hinzufügen">');
				echo('</form><br><br>');
			?>
			

		</center>
	</div>
	<div class="grades_get" id="grades_get">
		<center>
			<h1>Note für Schnitt berrechnen</h1>
			<div style="overflow-x: auto;">
			<form action="main.php#grades_get" method="POST">
				<table> 
					<tr><th>Fach</th><th>Wunschschnitt</th><th>Berechnen</th></tr>
					<tr><td>
					<!-- auflistung der Fächer -->
					<?php
					echo('<select id="subject" name="subject" required>');
					$sub="";
					$fp=fopen("id/$username.id","r");
					$cnt_=0;
					while(!feof($fp))
					{
						$sub=substr(fgets($fp), 0, -1);
						if(!feof($fp))
						{
							$cnt_++;
							if($cnt_ % 2 !=0)
							{
								if($sub==$_POST["subject"])
								{
									echo('<option value="'.$sub.'" selected>'.$sub.'</option>');
								}
								else
								{
									echo('<option value="'.$sub.'">'.$sub.'</option>');
								}
							}
						}
					}
					fclose($fp);
					echo("</select>");
					?>
					<?php
						if(isset($_POST["Berrechnen"]))
						{
							$subject=htmlspecialchars($_POST["subject"]);
							$schnitt=(float)htmlspecialchars($_POST["schnitt"]);
							echo('</td><td><input type="number" min="1" max="6" step="0.01" id="schnitt" name="schnitt" value="'.$schnitt.'"></td><td><input type="Submit" value="Berechnen" id="Berrechnen" name="Berrechnen"></td></tr></table>');
							echo("<br><br>");
							//get all the grades of this subject and the current schnitt
							
							$filename=htmlspecialchars($_POST["subject"]);
							$grf=fopen("users/$username/$filename","r");
							$grade_cur=0;
							$grade_cnt=0;
							$grade_add=(float)fgets($grf);
							$cnt=0;
							while(!feof($grf))
							{
								$cnt++;
								fgets($grf);//thema
								fgets($grf);//datum
								$grade_add=(float)fgets($grf);//note
								$weight=(float)fgets($grf);//gewichtung
								if($weight==0)
								{
									$weight=1;
								}
								if(!feof($grf))
								{
									$grade_cur+=($grade_add*($weight*100));
								}
								$grade_cnt=$grade_cnt+(100*$weight);
							}
							if($grade_cnt<=100)
							{
								$grade_cnt=101;//to prevent div by 0
							}
							fclose($grf);
							$schnittbu=$schnitt;
							$grade_media=(float)$grade_cur/($grade_cnt-100);
							$schnitt=(float)$schnitt*($grade_cnt);
							$schnitt=$schnitt-$grade_cur;
							$schnitt=$schnitt/100;
							
							$schnitt2=(float)$schnittbu*($grade_cnt+100);
							$schnitt2=$schnitt2-$grade_cur;
							//echo($schnitt2."<br>".$grade_cur."<br>".$grade_cnt."<br>".$schnittbu);
							$schnitt2=$schnitt2/200;
							
							$schnitt3=(float)$schnittbu*($grade_cnt+200);
							$schnitt3=$schnitt3-$grade_cur;
							//echo($schnitt2."<br>".$grade_cur."<br>".$grade_cnt."<br>".$schnittbu);
							$schnitt3=$schnitt3/300;
							
							//next part of the code	
							if($schnitt<1)
								echo("Du bräuchtest eine Note<1 in der nächsten Prüfung.<br>");
							else if($schnitt>6)
								echo("Du bräuchtest eine Note>6 in der nächsten Prüfung.<br>");
							else
								echo("Du bräuchtest eine ".round($schnitt,3)." in der nächsten Prüfung.<br>");
								
							echo("Oder<br>");
								if($schnitt2<1)
									echo("Du bräuchtest zweimal eine Note<1 in den nächsten Prüfungen.<br>");
								else if($schnitt2>6)
									echo("Du bräuchtest zweimal eine Note>6 in den nächsten Prüfungen.<br>");
								else
									echo("Du bräuchtest zweimal eine ".round($schnitt2,3)." in den nächsten Prüfungen.<br>");
									
							echo("Oder<br>");
								if($schnitt3<1)
									echo("Du bräuchtest dreimal eine Note<1 in den nächsten Prüfungen.<br>");
								else if($schnitt3>6)
									echo("Du bräuchtest dreimal eine Note>6 in den nächsten Prüfungen.<br>");
								else
									echo("Du bräuchtest dreimal eine ".round($schnitt3,3)." in den nächsten Prüfungen.<br>");
						}
						else
						{
							echo('</td><td><input type="number" min="1" max="6" step="0.01" id="schnitt" name="schnitt"></td><td><input type="Submit" value="Berechnen" id="Berrechnen" name="Berrechnen"></td></tr></table>');
						}
					?>
				
				
			</form>
			</div>
		</center>
		<br><br><br>
	</div>
	<!--<br><br><br>-->
<!--	<div style="width:100%;bottom:0px;position:fixed;padding: 0px 0px 0px 0px;background:#333;height:auto" >
	<center style="color:white">&copy; <a style="color:light-blue" href="mailto:janis.steiner@protonmail.com">janis.steiner@protonmail.com</a></center>
	</div>-->
	<!--
	hooooooooooooooooooooooooooooooooooooooiiiiiiiiiiiiiii
	-->
</body>
</html>
