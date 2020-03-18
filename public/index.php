<?php
ini_set("display_errors", 1);
error_reporting(E_ALL);

require_once("functions/layout.inc.php");
	pre();                                     
//		print "<div style=\"width:700px\">";
//			$nieuws = new Nieuws();
//			$items = $nieuws->getLatestNews(5);
//			print "<h2>Laatste Nieuws</h2>";
//			print "<dl class=\"accordion-menu\" >";
//			foreach($items as $item){
//				print "<dt class=\"a-m-t\" style=\"color:#91BD93\">";
//					print "<table>";
//						print "<tr>";
//							print "<td class=\"td_accordion_small\" width=\"100px\">";
//								print $item['datum'];
//							print "</td>";
//							print "<td class=\"td_accordion_small\">";
//								print "<i>".$item['titel']."</i>";
//							print "</td>";
//						print "</tr>";
//					print "</table>";
//				print "</dt>";
//				print "<dd class=\"a-m-d\">";
//					print "<div class=\"bd\">";
//                        $inhoud = nl2br($item['inhoud']);
//						print $inhoud;
//					print "</div>";
//				print "</dd>";
//			}
//			print "</dl>";
//		print "</div>";
		print "<div class=\"div_left\">";
			print "<h2>Huidige stand</h2>";
			print "<div class=\"border\" style=\"width:250px\">";
				$stand = new Stand(date("Y-m-d"));
				$rows = $stand->getTable();
				print "<table cellspacing=\"5\">";
				$i=1;
				foreach($rows as $row){
					print "<tr>";
						print "<td align=\"right\">";
							print ($row['teamid']==2) ? "<b>" : "";
							print $i++.".";
							print ($row['teamid']==2) ? "</b>" : "";
						print "</td>";
						print "<td style=\"width:120px\">";
							print ($row['teamid']==2) ? "<b>" : "";
                            print "<a href=\"team.php?id=".$row['teamid']."\">";
							print $row['teamnaam'];
                            print "</a>";
							print ($row['teamid']==2) ? "</b>" : "";
						print "</td>";
						print "<td>";
							print ($row['teamid']==2) ? "<b>" : "";
							print $row['gespeeld'];
							print ($row['teamid']==2) ? "</b>" : "";
						print "</td>";
						print "<td align=\"right\">";
							print ($row['teamid']==2) ? "<b>" : "";
							print $row['punten']."pt";
							print ($row['teamid']==2) ? "</b>" : "";
						print "</td>";
						print "<td align=\"right\">";
							print ($row['teamid']==2) ? "<b>" : "";
							if(substr($row['saldo'],0,1)!=="-")
								print "+";
							print $row['saldo'];
							print ($row['teamid']==2) ? "</b>" : "";
						print "</td>";
					print "</tr>";
				}
				print "</table>";
			print "</div>";
		print "</div>";
		print "<div class=\"div_left\" style=\"margin-left:50px\">";
			print "<h2>Laatste Wedstrijden</h2>";
			$wedstrijd = new Wedstrijd();
			$weds = $wedstrijd->getLastPlayed(2, 3);
			foreach($weds as $wed){
			 	$values = $wed->getValues();
                $title = "Match Screen&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;".$wed->homeTeam()->toString()." - ".
                          $wed->awayTeam()->toString()." (".$values['Scorethuis']."-".$values['Scoreuit'].")".
                          "&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;".$values['Datum'];
				print "<a href=\"#\" title=\"$title\"
					class=\"box\" style=\"width:390px\"
					>";
					print "<table border='0' width=\"100%\">";
						print "<tr>";
							print "<td class=\"td_accordion_small\">";
								print $values['Datum'];
							print "</td>";
							print "<td class=\"td_accordion_small\" style=\"width:90px\">";
								print $wed->homeTeam()->toString();
							print "</td>";
							print "<td class=\"td_accordion_small\">";
								print " - ";
							print "</td>";
							print "<td class=\"td_accordion_small\" style=\"width:90px\">";
								print $wed->awayTeam()->toString();
							print "</td>";
							print "<td class=\"td_accordion_small\">";
								print $values['Scorethuis'];
							print "</td>";
							print "<td class=\"td_accordion_small\">";
								print "-";
							print "</td>";
							print "<td class=\"td_accordion_small\">";
								print $values['Scoreuit'];
							print "</td>";
						print "</tr>";
					print "</table>";
				print "</a>";
			}
			print "<h2>Volgende Wedstrijden</h2>";
			$wedstrijd = new Wedstrijd();
			$weds = $wedstrijd->getNextPlayed(2, 3);
			foreach($weds as $wed){
			 	$values = $wed->getValues();
                $title = "Match Screen&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;".$wed->homeTeam()->toString()." - ".
                          $wed->awayTeam()->toString().
                          "&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;".$values['Datum'];
                print "<a href=\"#\" title=\"$title\"
                    class=\"box\" style=\"width:390px\"
                    >";
					print "<table width=\"100%\">";
						print "<tr>";
							print "<td class=\"td_accordion_small\">";
								print $values['Datum'];
							print "</td>";
							print "<td class=\"td_accordion_small\" style=\"width:120px\">";
								print $wed->homeTeam()->toString();
							print "</td>";
							print "<td class=\"td_accordion_small\">";
								print " - ";
							print "</td>";
							print "<td class=\"td_accordion_small\" style=\"width:120px\">";
								print $wed->awayTeam()->toString();
							print "</td>";
						print "</tr>";
					print "</table>";
				print "</a>";
			}
		print "</div>";
//		print "<div class=\"div_left\" style=\"width:700px;margin-top:20px\">";
//			print "<h2>Laatste gastenboek berichten</h2>";
//			$gastenboek = new Gastenboek();
//			$entries = $gastenboek->getEntries(3,0);
//			print "<table>";
//			foreach($entries as $entry){
//				$values = $entry->getValues();
//				print "<tr>";
//					print "<td>";
//						print $values['datum'];
//					print "</td>";
//					print "<td>";
//						print "<b>".$values['naam']."</b>";
//					print "</td>";
//					print "<td>";
//						print "<i>".substr(strip_tags($values['bericht']), 0, 70);
//						if(strlen($values['bericht'])>70)
//							print "....</i>";
//					print "</td>";
//				print "</tr>";
//			}
//			print "</table>";
//		print "</div>";
	post();        
?>
