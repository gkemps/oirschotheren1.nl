<?php
	include("functions/layout.inc.php");
	pre();
	  if(isset($_GET['id'])){
	   	$team = new Team($_GET['id']);
	   	$values = $team->getValues();
	   	print "<h2>Team Info</h2>";
	   	print "<center>";
		print "<table class=\"border\" border=\"0\" cellspacing=\"0\" style=\"width:600px\">";
		print "<tr>";
			print "<td rowspan=\"6\">";
				print "<img src=\"teamlogo.php?id=".$team->getId()."\" ".
						"class=\"big_team_logo\">";
			print "</td>";
		print "</tr>";
		print "<tr>";
			print "<td colspan=\"2\"class=\"td_big\">";
				print "<b>".$team->toString()."</b>";
			print "</td>";
		print "</tr>";
		print "<tr>";
			print "<td>";
				print "<b>Plaats:</b>";
			print "</td>";
			print "<td>";
				print $values['Woonplaats'];
			print "</td>";
			print "<td>";
				print "<b>Laatste GW:</b>";
			print "</td>";
			print "<td>";
				$wedstrijd = new Wedstrijd();
				$wedstrijd = $wedstrijd->lastResult($values['id'], 1);
				$wvalues = $wedstrijd->getValues();
				print "<i>".$wvalues['Datum']." </i>";
				print $wedstrijd->toString();
			print "</td>";
		print "</tr>";
		print "<tr>";
			print "<td>";
				print "<b>Website:</b>";
			print "</td>";
			print "<td>";
				print "<a href=\"".$values['website']."\" target=\"_blank\">Klik hier</a>";
			print "</td>";
			print "<td>";
				print "<b>Laatste GL:</b>";
			print "</td>";
			print "<td>";
				$wedstrijd = new Wedstrijd();
				$wedstrijd = $wedstrijd->lastResult($values['id'], 3);
				$wvalues = $wedstrijd->getValues();
				print "<i>".$wvalues['Datum']." </i>";
				print $wedstrijd->toString();
			print "</td>";
		print "</tr>";
		print "<tr>";
			print "<td colspan=\"2\">";
				print "&nbsp;";
			print "</td>";
			print "<td>";
				print "<b>Laatste VL:</b>";
			print "</td>";
			print "<td>";
				$wedstrijd = new Wedstrijd();
				$wedstrijd = $wedstrijd->lastResult($values['id'], 2);
				$wvalues = $wedstrijd->getValues();
				print "<i>".$wvalues['Datum']." </i>";
				print $wedstrijd->toString();
			print "</td>";
		print "</tr>";
		print "<tr>";
			print "<td colspan=\"4\" style=\"vertical-align:middle\">";
				$wins = $team->nrOfResults(1);
				$losses = $team->nrOfResults(2);
				$draws = $team->nrOfResults(3);
				print "<table cellspacing=\"0\">";
					print "<tr>";
						print "<td>";
							print ($wins + $losses + $draws);
						print "</td>";
						print "<td>";
							print "<b>GS</b>";
						print "</td>";
						print "<td>";
							print " - ";
						print "</td>";
						print "<td>";
							print $wins;
						print "</td>";
						print "<td>";
							print "<b>GW</b>";
						print "</td>";
						print "<td>";
							print " - ";
						print "</td>";
						print "<td>";
							print $draws;
						print "</td>";
						print "<td>";
							print "<b>GL</b>";
						print "</td>";
						print "<td>";
							print " - ";
						print "</td>";
						print "<td>";
							print $losses;
						print "</td>";
						print "<td>";
							print "<b>VL</b>";
						print "</td>";
					print "</tr>";
				print "</tabLe>";
			print "</td>";
		print "</tr>";
		print "</table>";
		$seizoenen = array_reverse($team->getSeasons());
		print "<dl class=\"accordion-menu\" ".
			"style=\"width:600px;font-size:14pt;\">";
		$i = 4;
		foreach($seizoenen as $seizoen){
		 	$svalues = $seizoen->getValues();
		 	$stand = new Stand($svalues['eind']);
		 	$standrij = $stand->getTeamRow($team->getId());
		 	$wedstrijden = $team->getMatches($seizoen->getId());
			print "<dt class=\"a-m-t\" style=\"color:#91BD93\">";
				print "<table  border=\"0\" cellspacing=\"0\">";
					print "<tr>";
						print "<td class=\"td_accordion\" width=\"200px\">";
							print "Seizoen ".$seizoen->toString();
						print "</td>";
						print "<td class=\"td_accordion\" align=\"right\" width=\"50px\">";
							print "(".$stand->tablePosition($team->getId())."e)";
						print "</td>";
						print "<td class=\"td_accordion\" align=\"right\" width=\"70px\">";
							print "&nbsp;";
						print "</td>";
						print "<td class=\"td_accordion_white\" width=\"15px\" ".
							">";
							print $standrij['gespeeld'];
						print "</td>";
					  print "<td class=\"td_accordion_white\" style=\"padding-right:10px\">";
							print "<b> GS</b>";
						print "</td>";
						print "<td class=\"td_accordion_white\" width=\"15px\">";
							print $standrij['gewonnen'];
						print "</td>";
					print "<td class=\"td_accordion_white\" style=\"padding-right: 10px\">";
							print "<b> GW</b>";
						print "</td>";
						print "<td class=\"td_accordion_white\" width=\"15px\">";
							print $standrij['gelijk'];
						print "</td>";
					print "<td class=\"td_accordion_white\" style=\"padding-right: 10px\">";
							print "<b> GL</b>";
						print "</td>";
					print "<td class=\"td_accordion_white\" style=\"padding-right: 10px\">"; 
							print $standrij['verloren'];
						print "</td>";
						print "<td class=\"td_accordion_white\">";
							print "<b> VL</b>";
						print "</td>";       
                        print "<td class=\"td_accordion_white\" width=\"15px\">";
                            print "(";
                            print $standrij['voor'];
                            print "-";
                            print $standrij['tegen']; 
                            print ")";
                        print "</td>"; 
					print "</tr>";
				print "</table>";
			print "</dt>";
			print "<dd class=\"a-m-d\">";
				print "<div class=\"bd\">";
					print "<table border=\"0\">";
						foreach($wedstrijden as $wedstrijd){
							$values = $wedstrijd->getValues();
							print "<tr>";
                                print "<td>";
                                    $title = "Match Screen&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;".$wedstrijd->homeTeam()->toString()." - ".
                                    $wedstrijd->awayTeam()->toString();
                                    if($wedstrijd->played())
                                        $title .= "&nbsp;&nbsp;&nbsp;(".$values['Scorethuis']."-".$values['Scoreuit'].")";
                                     
                                    $title .= "&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;".$wedstrijd->getDate();
                                    print "<a href=\"matchscreen.php?id=".$wedstrijd->getId()."\" title=\"$title\" onclick=\"Modalbox.show(this.href, {title: this.title, width: 600, height: 800});return false;\">";
                                    print "<img src=\"images/info.gif\" width=\"10px\" border=\"0\">";
                                    print "</a>";
                                print "</td>";
								print "<td>";
									print "<b>".$values['Datum']."</b>";
								print "</td>";
								print "<td width=\"80px\">";
									if($wedstrijd->homeTeam()->getId()==$team->getId())
										print "<b>".$wedstrijd->homeTeam()->toString()."</b>";
									else{
                                        print "<a href=\"team.php?id=".$wedstrijd->homeTeam()->getId()."\">";   
										print $wedstrijd->homeTeam()->toString();
                                        print "</a>";
                                    }
								print "</td>";
								print "<td>";
									print " - ";
								print "</td>";
								print "<td width=\"80px\">";
									if($wedstrijd->awayTeam()->getId()==$team->getId())
										print "<b>".$wedstrijd->awayTeam()->toString()."</b>";
									else{
                                        print "<a href=\"team.php?id=".$wedstrijd->awayTeam()->getId()."\">";   
										print $wedstrijd->awayTeam()->toString();
                                        print "</a>";
                                    }
								print "</td>";
								print "<td>";
									print $values['Scorethuis'];
								print "</td>";
								print "<td>";
									print " - ";
								print "</td>";
								print "<td>";
									print $values['Scoreuit'];
								print "</td>";
								if($wedstrijd->OirschotInMatch()){
									print "<td>";
										//print "[ <a href=\"wedstrijd.php?id=".
										//	$wedstrijd->getId()."\" target=\"new\">";
										//print "overzicht";
										//print " ]</a>";
									print "</td>";
								}
							print "</tr>";
						}
					print "</table>";
				print "</div>";
			print "</dd>";
		}
		print "</dl>";
		print "</center>";
	 }
	post();

?>
