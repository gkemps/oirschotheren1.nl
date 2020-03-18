<?php
	//3e helft van straattheater
	require_once("functions/layout.inc.php");
	pre();
		print "<div style=\"width:700px\">";
			$db = new DB();
			if(isset($_GET['id'])){
			 	$entry = $db->queryRow("SELECT * FROM 3ehelft WHERE id = '".$_GET['id']."'"); 
				$team = new Team($entry['teamid']);
				print "<h2>Oirschot in ".$team->toString()."</h2>";
				print $entry['verslag'];
			}
			else{
			print "<h2>Oirschot in de 3e helft</h2>";
			$entries = $db->query("SELECT * FROM 3ehelft ORDER BY id ASC");
			print "<i>Klik op een van de logo's voor een uitgebreid verslag van onze eigen Straattheater!</i>";
			print "<table>";
			foreach($entries as $entry){
			 	print "<tr>";
			 		print "<td>";
			 			print "<a href=\"3ehelft.php?id=".$entry['id']."\">";
			 			print "<table style=\"border: 1px solid #00937A;\">";
			 				print "<tr>";
			 					print "<td rowspan=\"5\">";
			 					  print "<a href=\"3ehelft.php?id=".$entry['id']."\">";
			 					  print "<img src=\"teamlogo.php?id=".$entry['teamid']."\" class=\"team_logo\" border=\"0\">";
			 					  print "</a>";
								print "</td>";
			 					$scores = array("Tegenstander" => $entry['tegenstander'], "Clubhuis" => $entry['clubhuis'], 
								 "Muziek" => $entry['muziek'], "Bier" => $entry['bier'], 
								 "Sluitingstijd" =>$entry['sluitingstijd']);
								$i = 0;
								foreach($scores as $key => $score){
								 	if($i++>0)
								 		print "<tr>";
										print "<td>";
					 						print "$key:";
					 					print "</td>";
					 					print "<td>";
						 					$sterren = floor($score / 2);
						 					$halvesterren = $score % 2;
						 					$legesterren = 5 - $sterren - $halvesterren;
						 					for($i=0;$i<$sterren;$i++){
												print "<img src=\"images/ster.gif\" width=\"15px\" height=\"15px\" border=\"0\">";
											}
											for($i=0;$i<$halvesterren;$i++){
												print "<img src=\"images/halve_ster.gif\" width=\"15px\" height=\"15px\" border=\"0\">";
											}
											for($i=0;$i<$legesterren;$i++){
												print "<img src=\"images/lege_ster.gif\" width=\"15px\" height=\"15px\" border=\"0\">";
											}
										print "</td>";
									print "</tr>";
								}
			 			print "</table>";
			 			print "</a>";
					print "</td>";
				print "</tr>";
			}
			print "</table>";
			}
		print "</div>";
	post();
?>