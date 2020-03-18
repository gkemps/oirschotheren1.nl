<?php
	require_once("functions/layout.inc.php");
	function findTeam($rows, $teamid){
		foreach($rows as $row){
			if($row['teamid'] == $teamid)
				return $row;
		}
	}
	
	pre();
		print "<div style=\"width:700px\">";
			print "<h2>Stand van zaken in 3e klasse C</h2>";
			$stand = new Stand(date("Y-m-d"));
			$rows = $stand->getTable();
			$homerows = $stand->getHomeTable();
			$awayrows = $stand->getAwayTable();
			//oude stand opvragen
			$wedstrijd = new Wedstrijd();
			$lastplayed = $wedstrijd->getLastPlayed(2, 1);
			$values = $lastplayed[0]->getValues(); 
            $date = date("Y-m-d", strtotime($values['Datum'])-24*60*60);                    
			$oudestand = new Stand($date);
			$oudestand = $oudestand->getTeamPositions();      
			print "<table cellspacing=\"0\" style=\"border: 2px solid #00937A;\">";
			$i=1;
			print "<tr>";
				print "<td align=\"center\" colspan=\"11\" class=\"stand_header\">";
					print "Totaal";
				print "</td>";
				print "<td align=\"center\" colspan=\"3\" class=\"stand_header\" style=\"border-left: 2px solid #00937A\">";
					print "Thuis";
				print "</td>";
				print "<td align=\"center\" colspan=\"3\" class=\"stand_header\" style=\"border-left: 2px solid #00937A\">";
					print "Uit";
				print "</td>";
				print "<td align=\"center\" class=\"stand_header\" style=\"border-left: 2px solid #00937A\">";
					print "Vorm";
				print "</td>";
			print "</tr>";
			print "<tr>";
				print "<td align=\"right\" class=\"stand_header\">";
					print "Ps";
				print "</th>";
				print "<td style=\"width:120px\" class=\"stand_header\" align=\"center\">";
					print "Team";
				print "</td>";
				print "<td class=\"stand_header\">";
					print "GS";
				print "</td>";
				print "<td class=\"stand_header\">";
					print "GW";
				print "</td>";
				print "<td class=\"stand_header\">";
					print "GL";
				print "</td>";
				print "<td class=\"stand_header\">";
					print "VL";
				print "</td>";
				print "<td class=\"stand_header\">";
					print "PT";
				print "</th>";
				print "<td class=\"stand_header\">";
					print "DV";
				print "</td>";
				print "<td class=\"stand_header\">";
					print "DT";
				print "</td>";
				print "<td class=\"stand_header\">";
					print "DS";
				print "</td>";
				print "<td class=\"stand_header\">";
					print "&nbsp;";
				print "</td>";
				print "<td class=\"stand_header\" style=\"border-left: 2px solid #00937A\">";
					print "GS";
				print "</td>";
				print "<td class=\"stand_header\">";
					print "PT";
				print "</th>";
				print "<td class=\"stand_header\">";
					print "DS";
				print "</td>";
				print "<td class=\"stand_header\" style=\"border-left: 2px solid #00937A\">";
					print "GS";
				print "</td>";
				print "<td class=\"stand_header\">";
					print "PT";
				print "</th>";
				print "<td class=\"stand_header\">";
					print "DS";
				print "</td>";
				print "<td class=\"stand_header\" style=\"border-left: 2px solid #00937A\">";
					print "&nbsp;";
				print "</td>";
			print "</tr>";
			foreach($rows as $teamid => $row){
				print "<tr>";
					print "<td class=\"stand\">";
						print ($teamid==2) ? "<b>" : "";
						print $i.".";
						print ($teamid==2) ? "</b>" : "";
					print "</td>";
					print "<td style=\"width:120px;text-align:left\" class=\"stand\">";
						print ($teamid==2) ? "<b>" : "";
                        print "<a href=\"team.php?id=".$teamid."\">";   
						print $row['teamnaam'];
                        print "</a>";
						print ($teamid==2) ? "</b>" : "";
					print "</td>";
					print "<td class=\"stand\">";
						print ($teamid==2) ? "<b>" : "";
						print $row['gespeeld'];
						print ($teamid==2) ? "</b>" : "";
					print "</td>";
					print "<td class=\"stand\">";
						print ($teamid==2) ? "<b>" : "";
						print $row['gewonnen'];
						print ($teamid==2) ? "</b>" : "";
					print "</td>";
					print "<td class=\"stand\">";
						print ($teamid==2) ? "<b>" : "";
						print $row['gelijk'];
						print ($teamid==2) ? "</b>" : "";
					print "</td>";
					print "<td class=\"stand\">";
						print ($teamid==2) ? "<b>" : "";
						print $row['verloren'];
						print ($teamid==2) ? "</b>" : "";
					print "</td>";
					print "<td class=\"stand\">";
						print "<b>".$row['punten']."</b>";
					print "</td>";
					print "<td class=\"stand\">";
						print "(";
						print ($teamid==2) ? "<b>" : "";
						print $row['voor'];
						print ($teamid==2) ? "</b>" : "";
					print "</td>";
					print "<td  class=\"stand\">";
						print ($teamid==2) ? "<b>" : "";
						print $row['tegen'];
						print ($teamid==2) ? "</b>" : "";
						print ")";
					print "</td>";
					print "<td  class=\"stand\">";
						print ($teamid==2) ? "<b>" : "";
						if(substr($row['saldo'],0,1)!=="-")
							print "+";
						print $row['saldo'];
						print ($teamid==2) ? "</b>" : "";
					print "</td>";
					print "<td>";
						if($oudestand[$teamid]>$i){
							print "<img src=\"images/arrow-up.gif\">";
                            print "<small>+".($oudestand[$teamid] - $i)."</small>";
                        }
						elseif($oudestand[$teamid]<$i){
							print "<img src=\"images/arrow-down.gif\">";
                            print "<small>-".abs($oudestand[$teamid] - $i)."</small>";  
                        }
						else
							print "&nbsp;";
					print "</td>";
					$home = $homerows[$teamid];
					print "<td  class=\"stand\" style=\"border-left: 2px solid #00937A\">";
						print ($teamid==2) ? "<b>" : "";
						print $home['gespeeld'];
						print ($teamid==2) ? "</b>" : "";
					print "</td>";
					print "<td  class=\"stand\">";
						print ($teamid==2) ? "<b>" : "";
						print "<b>".$home['punten']."</b>";
						print ($teamid==2) ? "</b>" : "";
					print "</td>";
					print "<td  class=\"stand\">";
						print ($teamid==2) ? "<b>" : "";
						if($home['saldo']>0)
							print "+";
						print $home['saldo'];
						print ($teamid==2) ? "</b>" : "";
					print "</td>";
					$away = $awayrows[$teamid];
					print "<td  class=\"stand\" style=\"border-left: 2px solid #00937A\">";
						print ($teamid==2) ? "<b>" : "";
						print $away['gespeeld'];
						print ($teamid==2) ? "</b>" : "";
					print "</td>";
					print "<td  class=\"stand\">";
						print "<b>".$away['punten']."</b>";
					print "</td>";
					print "<td  class=\"stand\">";
						print ($teamid==2) ? "<b>" : "";
						if($away['saldo']>0)
							print "+";
						print $away['saldo'];
						print ($teamid==2) ? "</b>" : "";
					print "</td>";
					print "<td  class=\"stand\" style=\"border-left:2px solid #00937A;text-align:left;letter-spacing: 2px;\">";
                        print $wedstrijd->getTeamForm($teamid);
					print "</td>";
				print "</tr>";
				$i++;
			}
			print "</table>";
		print "</div>";
		print "<div class=\"left\" style=\"width:350px\">";
			print "<h2>Laatste speelronde</h2>";
			$seizoen = new Seizoen();
			$seizoen->setSeason(date("Y-m-d"));
			$bounds = $seizoen->getValues();
			$lastmatches = $wedstrijd->getLastPlayed(2, 1);            
			$lastround = $lastmatches[0]->getRound();
			$matches = $wedstrijd->getRoundMatches($bounds['start'], $bounds['eind'], $lastround);
            if(count($matches)>0){
			    print "<table>";
			    foreach($matches as $match){
				    $scores = $match->getScores();
				    print "<tr>";
					    print "<td>";
						    print "<b>".$match->getDate()."</b>";
					    print "</td>";
					    print "<td>";
                            print "<a href=\"team.php?id=".$match->homeTeam()->getId()."\">";   
						    print $match->homeTeam()->toString();
                            print "</a>";
					    print "</td>";
					    print "<td>";
						    print " - ";
					    print "</td>";
					    print "<td>";
                            print "<a href=\"team.php?id=".$match->awayTeam()->getId()."\">";   
						    print $match->awayTeam()->toString();
                            print "</a>";
					    print "</td>";
					    print "<td>";
						    print $scores['Scorethuis'];
					    print "</td>";
					    print "<td>";
						    print " - ";
					    print "</td>";
					    print "<td>";
						    print $scores['Scoreuit'];
					    print "</td>";
                        print "<td>";
                            $title = "Match Screen&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;".$match->homeTeam()->toString()." - ".
                                        $match->awayTeam()->toString().
                                     "&nbsp;&nbsp;&nbsp;(".$scores['Scorethuis']."-".$scores['Scoreuit'].")".
                                     "&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;".$match->getDate();
//                            print "<a href=\"matchscreen.php?id=".$match->getId()."\" title=\"$title\"
//                                onclick=\"Modalbox.show(this.href, {title: this.title, width: 600, height: 800});return false;\">";
//                            print "<img src=\"images/info.gif\" width=\"10px\" border=\"0\">";
//                            print "</a>";
                        print "</td>";
				    print "</tr>";
			    }
			    print "</table>";
            }
            else
                print "<i>Geen laatste speelronde gevonden</i>";
		print "</div>";
		print "<div class=\"left\" style=\"width:350px\">";
			print "<h2>Volgende speelronde</h2>";
			$seizoen = new Seizoen();
			$seizoen->setSeason(date("Y-m-d"));
			$bounds = $seizoen->getValues();
			$nextmatches = $wedstrijd->getNextPlayed(2, 1);
            if(count($nextmatches)>0){
			    $nextround = $nextmatches[0]->getRound();
			    $matches = $wedstrijd->getRoundMatches($bounds['start'], $bounds['eind'], $nextround);
			    print "<table>";
			    foreach($matches as $match){
				    print "<tr>";
					    print "<td>";
						    print "<b>".$match->getDate()."</b>";
					    print "</td>";
					    print "<td>";
                            print "<a href=\"team.php?id=".$match->homeTeam()->getId()."\">";   
						    print $match->homeTeam()->toString();
                            print "</a>";
					    print "</td>";
					    print "<td>";
						    print " - ";
					    print "</td>";
					    print "<td>";
                            print "<a href=\"team.php?id=".$match->awayTeam()->getId()."\">";   
						    print $match->awayTeam()->toString();
                            print "</a>";
					    print "</td>";
                        print "<td>";
                            $title = "Match Screen&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;".$match->homeTeam()->toString()." - ".
                            $match->awayTeam()->toString()."&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;".$match->getDate();
//                            print "<a href=\"matchscreen.php?id=".$match->getId()."\" title=\"$title\"
//                                onclick=\"Modalbox.show(this.href, {title: this.title, width: 600, height: 800});return false;\">";
//                            print "<img src=\"images/info.gif\" width=\"10px\" border=\"0\">";
//                            print "</a>";
                        print "</td>";
				    print "</tr>";
			    }
			    print "</table>";
            }
            else
                print "<i>Geen volgende speelronde gevonden</i>";
		print "</div>";
	post();
?>