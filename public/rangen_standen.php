<?php
	require_once("functions/layout.inc.php");
	pre();
		print "<div class=\"div_left\" style=\"margin-right:40px\">";
			print "<h2>Meeste doelpunten</h2>";
			$seizoen = new Seizoen();
			$seizoen->setSeason(date("Y-m-d"));
			$goal = new Goal();
			$goals = $goal->getSeasonTopscoorders($seizoen->getId());
			print "<table width=\"200px\">";
			$i = 1;
			$last = 0;
			foreach($goals as $goal){
				print "<tr>";
					print "<td>";
						if($last!==$goal[0]){
							print $i.".";
							$last = $goal[0];
						}
					print "</td>";
					print "<td>";
                        print "<a href=\"speler.php?id=".$goal[1]->getId()."\">";   
						print "<i>".$goal[1]->toString()."</i>";
                        print "</a>";
					print "</td>";
					print "<td>";
						print "<b>".$goal[0]."</b>";
					print "</td>";
				print "</tr>";
				$i++;
			}	
			print "</table>";
		print "</div>";
		
		print "<div class=\"div_left\" style=\"margin-right:40px\">";
			print "<h2>Meeste assists</h2>";
			$seizoen = new Seizoen();
			$seizoen->setSeason(date("Y-m-d"));
			$goal = new Goal();
			$assists = $goal->getSeasonTopassists($seizoen->getId());
			print "<table>";
			$i = 1;
			$last = 0;
			foreach($assists as $assist){
				print "<tr>";
					print "<td>";
						if($last!==$assist[0]){
							print $i.".";
							$last = $assist[0];
						}
					print "</td>";
					print "<td>";
                        print "<a href=\"speler.php?id=".$assist[1]->getId()."\">"; 
						print "<i>".$assist[1]->toString()."</i>";
                        print "</a>";
					print "</td>";
					print "<td>";
						print "<b>".$assist[0]."</b>";
					print "</td>";
				print "</tr>";
				$i++;
			}	
			print "</table>";
		print "</div>";
		
		print "<div class=\"div_left\" style=\"margin-right:40px\">";
			print "<h2>Meeste wedstrijden</h2>";
			$seizoen = new Seizoen();
			$seizoen->setSeason(date("Y-m-d"));
			$opstelling = new Opstelling();
			$spelers = $opstelling->getSeasonTopApps($seizoen->getId());
			$list = array();
			foreach($spelers as $speler){
				$list[$speler[1]->getId()] = array($speler[0], $speler[0]);
			}
			//print_r($list);
			$spelers = $opstelling->getSeasonTopBenchApps($seizoen->getId());
			//print_r($spelers);
			foreach($spelers as $speler){
				$list[$speler[1]->getId()][1] = ($list[$speler[1]->getId()][0] - $speler[0]);
			}
			print "<table>";
			$i = 1;
			arsort($list);
			foreach($list as $key => $value){
			 	$speler = new Speler($key);
				print "<tr>";
					print "<td>$i.</td>";
					print "<td>";
                        print "<a href=\"speler.php?id=".$speler->getId()."\">"; 
						print "<b>".$speler->toString()."</b>";
                        print "</a>";
					print "</td>";
					print "<td>";
						print $value[0];
						print "(".$value[1].")";
					print "</td>";
				print "</tr>";
				$i++;
				if($i>10)
					break;
			}	
			print "</table>";
		print "</div>";
	post();
?>