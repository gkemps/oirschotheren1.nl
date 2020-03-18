<?php
	include("functions/layout.inc.php");
    include("classes/vroegah.class.php");
	pre();
	  if(isset($_GET['id'])){
	   	$speler = new Speler($_GET['id']);
        $sid = $speler->getId();
        $vroegah = new Vroegah();
	   	$svalues = $speler->getValues();
	   	$matches = $speler->getMatches();
	   	$benchmatches = $speler->getBenchMatches();
		$goals = $speler->getGoals();
		$assists = $speler->getAssists();
		$cards = $speler->getCards();
		$nrmatches = count($matches);
		$nrbenchmatches = count($benchmatches);
		$nrgoals = count($goals) + $vroegah->getTotalGoals($sid);
		$nrassists = count($assists) + $vroegah->getTotalAssists($sid);
		$nrcards = count($cards) + $vroegah->getTotalCards($sid);
	   	print "<h2>Speler Info</h2>";
	   	print "<center>";
		print "<table class=\"border\" border=\"0\" cellspacing=\"0\" style=\"width:600px\">";
		print "<tr>";
			print "<td rowspan=\"6\">";
				print "<img src=\"spelerfoto.php?id=".$speler->getId()."\" ".
						"class=\"big_player_picture\">";
			print "</td>";
		print "</tr>";
		print "<tr>";
			print "<td colspan=\"2\"class=\"td_big\">";
				print "<b>".$speler->fullName()."</b>";
			print "</td>";
		print "</tr>";
		print "<tr>";
			print "<td>";
				print "<b>Actief:</b>";
			print "</td>";
			print "<td>";
				$seizoen = new Seizoen();
				$seizoen->setSeason(date("Y-m-d"));
				if($speler->isActive($seizoen->getId()))
					print "ja";
				else
					print "nee";
			print "</td>";
			print "<td>";
				print "<b>Laatste goal:</b>";
			print "</td>";
			print "<td>";
				if($nrgoals > 0){
					$goal = array_pop($goals);
					$values = $goal->getValues();
					$wedstrijd = new Wedstrijd($values['wedid']);
					$wvalues = $wedstrijd->getValues();
					print "<i>".$wvalues['Datum']."</i>";
					print "&nbsp;";
					print $wedstrijd->OirschotMatch();
				}
			print "</td>";
		print "</tr>";
		print "<tr>";
			print "<td>";
				print "<b>Positie:</b>";
			print "</td>";
			print "<td>";
				print $speler->getType();
			print "</td>";
			print "<td>";
				print "<b>Laatste assist:</b>";
			print "</td>";
			print "<td>";
				if($nrassists > 0){
					$assist = array_pop($assists);
					$values = $assist->getValues();
					$wedstrijd = new Wedstrijd($values['wedid']);
					$wvalues = $wedstrijd->getValues();
					print "<i>".$wvalues['Datum']."</i>";
					print "&nbsp;";
					print $wedstrijd->OirschotMatch();
				}
			print "</td>";
		print "</tr>";
		print "<tr>";
			print "<td>";
				print "<b>Leeftijd:</b>";
			print "</td>";
			print "<td>";
				print $svalues['Geboortedatum'];
				print " (".$speler->getAge().")";
			print "</td>";
			print "<td>";
				print "<b>Laatste kaart:</b>";
			print "</td>";
			print "<td>";
				if($nrcards > 0){
					$card = array_pop($cards);
					$values = $card->getValues();
					$wedstrijd = new Wedstrijd($values['wedid']);
					$wvalues = $wedstrijd->getValues();
					print "<i>".$wvalues['Datum']."</i>";
					print "&nbsp;";
					print $wedstrijd->OirschotMatch();
				}
			print "</td>";
		print "</tr>";
		print "<tr>";
			print "<td colspan=\"4\" style=\"vertical-align:middle\">";
				print "<table cellspacing=\"0\">";
					print "<tr>";
						print "<td>";
							print "<b>";
								print $nrmatches - $nrbenchmatches;
							print "</b>";
						print "</td>";
						print "<td>";
							print "<img src=\"images/wedstrijd.gif\" class=\"icon\">";
						print "</td>";
						print "<td>";
							print " - ";
						print "</td>";
						print "<td>";
							print "<b>";
								print $nrbenchmatches;
							print "</b>";
						print "</td>";
						print "<td>";
							print "<img src=\"images/bank.gif\" class=\"icon\">";
						print "</td>";
						print "<td>";
							print " - ";
						print "</td>";
						print "<td>";
							print "<b>";
								print $nrgoals;
							print "</b>";
						print "</td>";
						print "<td>";
							print "<img src=\"images/goal.gif\" class=\"icon\">";
						print "</td>";
						print "<td>";
							print " - ";
						print "</td>";
						print "<td>";
							print "<b>";
								print $nrassists;
							print "</b>";
						print "</td>";
						print "<td>";
							print "<img src=\"images/assist.gif\" class=\"icon\">";
						print "</td>";
						print "<td>";
							print " - ";
						print "</td>";
						print "<td>";
							print "<b>";
								print $nrcards;
							print "</b>";
						print "</td>";
						print "<td>";
							print "<img src=\"images/kaarten.jpg\" class=\"icon\">";
						print "</td>";
					print "</tr>";
				print "</tabLe>";
			print "</td>";
		print "</tr>";
		print "</table>";
		$seizoenen = array_reverse($speler->getSeasons());
		print "<dl class=\"accordion-menu\" ".
			"style=\"width:600px;font-size:14pt;\">";
		$i = 4;
		foreach($seizoenen as $seizoen){
		 	$values = $seizoen->getValues();
		 	$wedstrijden = $speler->getMatches($values['id']);
		 	$goals = $speler->getSeasonGoals($values['id']);
		 	$assists = $speler->getSeasonAssists($values['id']);
		 	$green = $speler->getSeasonCards($values['id'], "groen");
		 	$yellow = $speler->getSeasonCards($values['id'], "geel");
		 	$red = $speler->getSeasonCards($values['id'], "rood");
			print "<dt class=\"a-m-t\" style=\"color:#91BD93\">";
				print "<table  border=\"0\" cellspacing=\"0\">";
					print "<tr>";
						print "<td class=\"td_accordion\" width=\"350px\">";
							print "Seizoen ".$seizoen->toString();
						print "</td>";
						print "<td class=\"td_accordion_white\" width=\"15px\">";
							print "<b>";
							print count($wedstrijden);
							print "</b>";
						print "</td>";
						print "<td class=\"align_left\" style=\"padding-right: 20px\">";
							print "<img src=\"images/wedstrijd.gif\" class=\"icon\">";
						print "</td>";
						print "<td class=\"td_accordion_white\" width=\"15px\">";
							print "<b>";
							print count($goals);
							print "</b>";
						print "</td>";
						print "<td class=\"align_left\" style=\"padding-right: 20px\">";
							print "<img src=\"images/goal.gif\">";
						print "</td>";
						print "<td class=\"td_accordion_white\" width=\"15px\">";
							print "<b>";
							print count($assists);
							print "</b>";
						print "</td>";
						print "<td class=\"align_left\" style=\"padding-right: 20px\">";
							print "<img src=\"images/assist.gif\">";
						print "</td>";
						print "<td class=\"td_accordion_white\" width=\"10px\">";
							print "<b>";
							print count($red) + count($yellow) + count($green);
							print "</b>";
						print "</td>";
						print "<td class=\"align_left\">";
							print "<img src=\"images/kaarten.jpg\">";
						print "</td>";
					print "</tr>";
				print "</table>";
			print "</dt>";
			print "<dd class=\"a-m-d\">";
				print "<div class=\"bd\">";
					print "<table border=\"0\">";
						foreach($wedstrijden as $wedstrijd){
							$values = $wedstrijd->getValues();
							$opst = new Opstelling();
							$pos = $opst->getPos($speler->getId(), $values['id']);
							print "<tr>";
                                print "<td>";
                                    $title = "Match Screen&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;".$wedstrijd->homeTeam()->toString()." - ".
                                    $wedstrijd->awayTeam()->toString().
                                     "&nbsp;&nbsp;&nbsp;(".$values['Scorethuis']."-".$values['Scoreuit'].")".
                                     "&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;".$wedstrijd->getDate();
                                    print "<a href=\"matchscreen.php?id=".$wedstrijd->getId()."\" title=\"$title\"
                                        onclick=\"Modalbox.show(this.href, {title: this.title, width: 600, height: 800});return false;\">";
                                    print "<img src=\"images/info.gif\" width=\"10px\" border=\"0\">";
                                    print "</a>";
                                print "</td>";
								print "<td>";
									print "<b>".$values['Datum']."</b>";
								print "</td>";
								print "<td width=\"80px\">";
                                    print "<a href=\"team.php?id=".$wedstrijd->homeTeam()->getId()."\">";   
									if($wedstrijd->homeTeam()->getId()==2)
										print "<b>".$wedstrijd->homeTeam()->toString()."</b>";
									else
										print $wedstrijd->homeTeam()->toString();
                                    print "</a>";
								print "</td>";
								print "<td>";
									print " - ";
								print "</td>";
								print "<td width=\"80px\">";
                                    print "<a href=\"team.php?id=".$wedstrijd->awayTeam()->getId()."\">";   
									if($wedstrijd->awayTeam()->getId()==2)
										print "<b>".$wedstrijd->awayTeam()->toString()."</b>";
									else
										print $wedstrijd->awayTeam()->toString();
                                    print "</a>";
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
								print "<td style=\"padding-left:35px\">";
								  if($pos->isBasis())
									print "<img src=\"images/wedstrijd.gif\" class=\"icon\">";
								  elseif($pos->isBench())
									print "<img src=\"images/bank.gif\" class=\"icon\">";
								  $goals = $speler->getGoals($values['id']);
								  foreach($goals as $goal){
									print "<img src=\"images/goal.gif\" class=\"icon\">";
								  }
								  $assists = $speler->getAssists($values['id']);
								  foreach($assists as $assist){
									print "<img src=\"images/assist.gif\" class=\"icon\">";
								  }
								  $cards = $speler->getCards($values['id']);
								  foreach($cards as $card){
									if($card->getColor()=="groen")
										print "<img src=\"images/groen.JPG\" class=\"icon\">";
									elseif($card->getColor()=="geel")
										print "<img src=\"images/geel.jpg\" class=\"icon\">";
									else
										print "<img src=\"images/rood.JPG\" class=\"icon\">";
								  }
								print "</td>";
							print "</tr>";
						}
					print "</table>";
				print "</div>";
			print "</dd>";
		}
		
        $vroegah_seasons = $vroegah->getVroegah($sid);
        foreach($vroegah_seasons as $vroegah){
              $goals =   $vroegah->getGoals();
              $assists = $vroegah->getAssists();
              $cards = $vroegah->getCards();
              print "<dt class=\"a-m-t-vroegah\" style=\"color:#91BD93\">";     
                print "<table  border=\"0\" cellspacing=\"0\">";
                    print "<tr>";
                        print "<td class=\"td_accordion\" width=\"350px\">";
                            print "Seizoen ".$vroegah->getSeason()->toString();
                        print "</td>";
                        print "<td class=\"td_accordion_white\" width=\"15px\">";
                            print "<b>";
                            print "??";
                            print "</b>";
                        print "</td>";
                        print "<td class=\"align_left\" style=\"padding-right: 20px\">";
                            print "<img src=\"images/wedstrijd.gif\" class=\"icon\">";
                        print "</td>";
                        print "<td class=\"td_accordion_white\" width=\"15px\">";
                            print "<b>";
                            print (is_null($goals)) ? "?" : $goals;
                            print "</b>";
                        print "</td>";
                        print "<td class=\"align_left\" style=\"padding-right: 20px\">";
                            print "<img src=\"images/goal.gif\">";
                        print "</td>";
                        print "<td class=\"td_accordion_white\" width=\"15px\">";
                            print "<b>";
                            print (is_null($assists)) ? "?" : $assists;  
                            print "</b>";
                        print "</td>";
                        print "<td class=\"align_left\" style=\"padding-right: 20px\">";
                            print "<img src=\"images/assist.gif\">";
                        print "</td>";
                        print "<td class=\"td_accordion_white\" width=\"10px\">";
                            print "<b>";
                            print (is_null($cards)) ? "?" : $cards;    
                            print "</b>";
                        print "</td>";
                        print "<td class=\"align_left\">";
                            print "<img src=\"images/kaarten.jpg\">";
                        print "</td>";
                    print "</tr>";
                print "</table>";
              print "<dt>";
        }
        print "</dl>";   
		print "</center>";
	 }
	post();

?>
