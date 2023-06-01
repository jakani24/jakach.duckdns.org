<?php
	session_start();
	include "/var/www/html/php/login/v3/waf/waf.php";
	require_once "config.php";
	if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
		header("location:../login.php");
		exit;
	}
	$username=$_SESSION["username"];
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
</style>
<?php $color=$_SESSION["color"]; ?>
<?php echo(" <body style='background-color:$color'> ");?>
	<?php
		if(!isset($_GET["subject"]))
		{
				die("ERROR: no subject specified!");
		}
		//get points of this subject
		if(isset($_GET["update"]))
		{
			$update=(int)$_GET["update"];
			$filename=htmlspecialchars($_GET["subject"]);
			$fp=fopen("users/$username/$filename","r");
			$cp=fopen("users/$username/temp.txt","w");
			$cnt=0;
			$head=fgets($fp);
			fwrite($cp,$head);
			while(!feof($fp))
			{
				$cnt++;
				$buf1=fgets($fp);
				$buf2=fgets($fp);
				$buf3=fgets($fp);
				$buf4=fgets($fp);	
				if($cnt==$update)
				{
					$buf1=$_POST["theme"]."\n";
					$buf2=$_POST["date"]."\n";
					$buf3=(float)$_POST["grade"]."\n";
					$buf4=(float)$_POST["weight"]."\n";
					if((int)$buf3<1)
					{
						$buf3="1\n";
					}
					if((int)$buf3>6)
					{
						$buf3="6\n";
					}
				}
				fwrite($cp,$buf1);
				fwrite($cp,$buf2);
				fwrite($cp,$buf3);
				fwrite($cp,$buf4);
			}
			fclose($fp);
			fclose($cp);
			$fp=fopen("users/$username/$filename","w");
			$cp=fopen("users/$username/temp.txt","r");	
			while(!feof($cp))
			{
				$buf=fgets($cp);
				fwrite($fp,$buf);
			}		
		}
		if(isset($_GET["del"]))
		{
			$update=(int)$_GET["del"];
			$filename=htmlspecialchars($_GET["subject"]);
			$fp=fopen("users/$username/$filename","r");
			$cp=fopen("users/$username/temp.txt","w");
			$cnt=0;
			$head=fgets($fp);
			fwrite($cp,$head);
			while(!feof($fp))
			{
				$cnt++;
				$buf1=fgets($fp);
				$buf2=fgets($fp);
				$buf3=fgets($fp);
				$buf4=fgets($fp);	
				if($cnt!=$update)
				{
					fwrite($cp,$buf1);
					fwrite($cp,$buf2);
					fwrite($cp,$buf3);
					fwrite($cp,$buf4);
				}

			}
			fclose($fp);
			fclose($cp);
			$fp=fopen("users/$username/$filename","w");
			$cp=fopen("users/$username/temp.txt","r");	
			while(!feof($cp))
			{
				$buf=fgets($cp);
				fwrite($fp,$buf);
			}		
		}
		?>
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
		<!--gesamt-->
		<?php
			$filename=htmlspecialchars($_GET["subject"]);
			//if($_GET["counts"]==1)
			//{
				$points=0;
				if($_GET["counts"]==1)
				{
					echo("<h1>Pluspunkte von $filename</h1>");
				}
					$grf=fopen("users/$username/$filename","r");
					$grade_cur=0;
					$grade_cnt=0;
					$grade_add=(float)fgets($grf);
					while(!feof($grf))
					{
						$grade_add=(int)fgets($grf);//other data
						$grade_add=(int)fgets($grf);//other data
						$grade_add=(float)fgets($grf);
						$weight=(float)fgets($grf);//weight of the grade
						if($weight==0)
						{
							$weight=1;
						}
						if(!feof($grf))
						{
							$grade_cur+=($grade_add*($weight*100));
							//echo("hi:::".$weight);

						}
						$grade_cnt=$grade_cnt+(100*$weight);
					}
					if($grade_cnt<=100)
					{
						$grade_cnt=101;//to prevent div by 0
					}
					$grade_media=(float)$grade_cur/($grade_cnt-100);
					//echo($grade_media);
					if($grade_media>=4 && $grade_media!==0)
					{
						$points+=round((($grade_media-4)*2), 0)/2;
					}
					else if($grade_media!=0)
					{
						$points-=round(((4-$grade_media)*2), 0);
					}
				
				
				//$points=round($points,1);
			if($_GET["counts"]==1)
			{
				$counts=1;
				if($points<0)
				{
						echo("<p style='color:red'>$points</p>");
				}	
				else if($points>1)
				{
						echo("<p style='color:green'>$points</p>");
				}
				else if($points>0)
				{
						echo("<p style='color:orange'>$points</p>");
				}
				else if($points==0)
				{
						echo("<p>$points</p>");
				}	
			}
			else
			{
					$counts=0;
			}		
		?>
		<!-- Einzelnoten des faches -->
		
			<?php
				echo("<h1>Durchschnitt von $filename</h1>");
				echo(round($grade_media,3));
				echo("<h1>Noten von $filename</h1>");
			?>
			<!-- Notendurchschnitte anzeigen -->
			<div style="overflow-x: auto;">
			<table>
				<tr>
					<th>
						Noten
					</th>
					<th>
						Datum
					</th>
					<th>
						Thema
					</th>
					<th>
						Gewichtung
					</th>
					<th>
						Aktualisieren
					</th>
					<th>
						Löschen
					</th>
				</tr>
				<?php
				//alle noten bekommen und errechnen
				$filename=htmlspecialchars($_GET["subject"]);
				$grf=fopen("users/$username/$filename","r");
				$grade_cur=0;
				$grade_cnt=0;
				$grade_add=(float)fgets($grf);
				$cnt=0;
				while(!feof($grf))
				{
					$cnt++;
					echo("<tr>");
					$theme=fgets($grf);//thema
					$date=fgets($grf);//datum
					$grade=(float)fgets($grf);//note
					$weight=(float)fgets($grf);//gewichtung
					$theme=substr($theme,0,-1);
					$date=substr($date,0,-1);
					if($grade!=0)
					{

						echo("<form action='grades.php?update=$cnt&subject=$filename&counts=$counts' method='post'>");
						echo("<td><input type='number' min='1' max='6' step='0.01' value='$grade' id='grade' name='grade'></td>");
						echo("<td><input type='date' value='$date' id='date' name='date'></td>");
						echo("<td><input type='text' value='$theme' id='theme' name='theme'></td>");
						echo("<td><input type='text' value='$weight' id='weight' name='weight'></td>");
						echo("<td><input type='submit' value='aktualisieren'></td>");
						echo("<td><a href='grades.php?del=$cnt&subject=$filename&counts=$counts'>Löschen</a></td>");
						echo("</form>");
						echo("</tr>");
					}
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
				//echo("<tr><td>$filename</td><td>$grade_media</td><td><a href='grades.php?subject=$filename'>Genaue Daten</a></td></tr>");
				echo("</table></div>");
				?>
				<!-- google charts for diagramm -->
					<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
					<body>
					<div id="grade_chart" style="width:100%; max-width:600px; height:500px; z-index:9;"></div>

					<script>
					google.charts.load('current',{packages:['corechart']});
					google.charts.setOnLoadCallback(drawChart);

					function drawChart() {
					// Set Data
					var data = google.visualization.arrayToDataTable([
					  ['Test', 'Note'],
					  <?php
						  //get grades and write them [test-id,grade]
						$filename=htmlspecialchars($_GET["subject"]);
						$grf=fopen("users/$username/$filename","r");
						$grade_cur=0;
						$grade_cnt=0;
						$grade_add=(float)fgets($grf);
						$cnt=0;
						while(!feof($grf))
						{
							$cnt++;
							$theme=fgets($grf);//other data
							$date=fgets($grf);//other data
							$grade=(float)fgets($grf);
							$weight=(float)fgets($grf);
							$theme=substr($theme,0,-1);
							if($grade!=0)
							{
								if($cnt!=1)
								{
									echo(",");
								}
								echo("['$theme',$grade]");
							}

						}
						fclose($grf);				  
					  ?>
					]);
					// Set Options
					var options = {
						    animation:{
							  duration:700,
							  easing: 'linear',
							  startup: true
							},
					  title: 'Deine Leistung',
					  backgroundColor: <?php echo("'".$color."'"); ?>,
					  hAxis: { slantedText:true, slantedTextAngle:90},
					  vAxis: {title: 'Note',  ticks: [1, 2, 3, 4,5,6]},		  
					  legend: 'none'
					  
					};
					// Draw
					
					var chart = new google.visualization.LineChart(document.getElementById('grade_chart'));
					chart.draw(data, options);
					
					}
					</script>
				<br><br>
				<a href="main.php">Zurück zum Dashboard</a>
				<br><br><br>
		</center>
</body>
</html>
