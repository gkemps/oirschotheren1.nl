<?php
require_once("classes/opstelling.class.php");

function positie($pos, $wed){
 	$posid = $pos->getId();
 	$wedid = $wed->getId();
 	$opst = new Opstelling();
 	$speler = $opst->getPlayer($wedid, $posid);
	print "<table id=\"table_$posid\" width=\"160px\">";
		print "<tr>";
			print "<td rowspan=\"5\" class=\"player_picbox\" id=\"pic_".$posid."\">";
				if(is_null($speler)){
					print "&nbsp;";
				}
				else{
					print "<img src=\"spelerfoto.php?id=".$speler->getId()."\" ".
						"class=\"player_picture\" alt=\"$posid\" 
						id=\"posplayer_".$speler->getId()."\">";
				}
			print "</td>";
		print "</tr>";
		print "<tr>";
			print "<td colspan=\"2\" class=\"bold_10\">";
				if(is_null($speler)){
					print "&nbsp;";
				}
				else{
					print $speler->shortName();
				}
			print "</td>";
		print "</tr>";
		print "<tr>";
			print "<td colspan=\"2\" class=\"normal_10\">";
				if(is_null($speler)){
					print "&nbsp;";
				}
				else{
					print $pos->toString();
				}
			print "</td>";
		print "</tr>";
		print "<tr>";
			if(is_null($speler))
				print "<td colspan=\"2\">";
			else
				print "<td colspan=\"2\" id=\"goal_".$posid."\">";
				if(is_null($speler)){
					print "&nbsp;";
				}
				else{
					$goals = $speler->getGoals($wed->getId());
					foreach($goals as $goal){
						print "<img src=\"images/goal.gif\" class=\"hockeybal\">";
					}
					$assists = $speler->getAssists($wed->getId());
					foreach($assists as $assist){
						print "<img src=\"images/assist.gif\">";
					}
					if(empty($assists) and empty($goals))
						print "&nbsp;";
				}
			print "</td>";
		print "</tr>";
		print "<tr>";
			if(is_null($speler))
				print "<td colspan=\"2\">";
			else
				print "<td colspan=\"2\" id=\"kaart_".$posid."\">";
				if(is_null($speler)){
					print "&nbsp;";
				}
				else{
					$kaarten = $speler->getCards($wed->getId());
					foreach($kaarten as $kaart){
					 	if($kaart->getColor()=="groen")
							print "<img src=\"images/groen.JPG\" class=\"kaart\">";
						elseif($kaart->getColor()=="geel")
							print "<img src=\"images/geel.jpg\" class=\"kaart\">";
						else
							print "<img src=\"images/rood.JPG\" class=\"kaart\">";
					}
					if(empty($kaarten))
						print "&nbsp;";
				}
			print "</td>";
		print "</tr>";
	print "</table>";
	if(!is_null($speler)){
	 	print "<script type=\"text/javascript\" language=\"javascript\">\r\n";
			print "// <![CDATA[\r\n";
			print "new Draggable('posplayer_".$speler->getId()."', {revert: true});";
			print "Droppables.add('goal_".$posid."', 
				{onDrop: function(drag, drop){goal_scored(drag, drop)},
				accept: 'goal', hoverclass: 'goals_hoover'});";
			print "Droppables.add('kaart_".$posid."', 
				{onDrop: function(drag, drop){kaart_scored(drag, drop)},
				accept: 'kaart', hoverclass: 'kaart_hoover'});";
			print "// ]]>\r\n";
		print "</script>\r\n";
	}
		
}

function positie_($pos, $wed){  
     $posid = $pos->getId();
     $wedid = $wed->getId();
     $opst = new Opstelling();
    $speler = $opst->getPlayer($wedid, $posid);
    print "<table width=\"160px\" class=\"playerbox\">";
        print "<tr>";
            print "<td rowspan=\"5\" class=\"player_picbox\">";
                if(is_null($speler)){
                    print "&nbsp;";
                }
                else{
                    print "<img src=\"spelerfoto.php?id=".$speler->getId()."\" ".
                        "class=\"player_picture\" alt=\"$posid\">";   
                }
            print "</td>";
        print "</tr>";
        print "<tr>";
            print "<td colspan=\"2\" class=\"bold_10\">";
                if(is_null($speler)){
                    print "&nbsp;";
                }
                else{
                    print $speler->shortName();
                }
            print "</td>";
        print "</tr>";
        print "<tr>";
            print "<td colspan=\"2\" class=\"normal_10\">";
                if(is_null($speler)){
                    print "&nbsp;";
                }
                else{
                    print $pos->toString();
                }
            print "</td>";
        print "</tr>";
        print "<tr>";
            if(is_null($speler))
                print "<td colspan=\"2\">";
            else
                print "<td colspan=\"2\">";
                if(is_null($speler)){
                    print "&nbsp;";
                }
                else{
                    $goals = $speler->getGoals($wed->getId());
                    foreach($goals as $goal){
                        print "<img src=\"images/goal.gif\" class=\"hockeybal\">";
                    }
                    $assists = $speler->getAssists($wed->getId());
                    foreach($assists as $assist){
                        print "<img src=\"images/assist.gif\" class=\"hockeybal\">";
                    }
                    if(empty($assists) and empty($goals))
                        print "&nbsp;";
                }
            print "</td>";
        print "</tr>";
        print "<tr>";
            if(is_null($speler))
                print "<td colspan=\"2\">";
            else
                print "<td colspan=\"2\">";
                if(is_null($speler)){
                    print "&nbsp;";
                }
                else{
                    $kaarten = $speler->getCards($wed->getId());
                    foreach($kaarten as $kaart){
                         if($kaart->getColor()=="groen")
                            print "<img src=\"images/groen.JPG\" class=\"kaart\">";
                        elseif($kaart->getColor()=="geel")
                            print "<img src=\"images/geel.jpg\" class=\"kaart\">";
                        else
                            print "<img src=\"images/rood.JPG\" class=\"kaart\">";
                    }
                    if(empty($kaarten))
                        print "&nbsp;";
                }
            print "</td>";
        print "</tr>";
    print "</table>"; 
}

function scores($wedstrijd){
	print "<table width=\"100%\">\r\n";
		print "<tr>\r\n";
			print "<td class=\"white_bold_12\" align=\"right\" width=\"40%\" id=\"tegenstander_".
					$wedstrijd->homeTeam()->getId()."\">\r\n";
				print $wedstrijd->homeTeam()->toString();
			print "</td>\r\n";
			print "<td class=\"white_bold_12\" align=\"center\" width=\"20%\">\r\n";
				$scores = $wedstrijd->getScores();
				print $scores['Scorethuis']."  -  ".$scores['Scoreuit'];
			print "</td>\r\n";
			print "<td class=\"white_bold_12\" align=\"left\" width=\"40%\" id=\"tegenstander_".
					$wedstrijd->awayTeam()->getId()."\">\r\n";
				print $wedstrijd->awayTeam()->toString();
			print "</td>\r\n";
			print "<td></td>";
		print "</tr>\r\n";
		$goals = $wedstrijd->getGoals();
		$helft = "";
		foreach($goals as $goal){
			$goal = $goal->getValues();
			if($helft==1 and $goal['Helft']==2){
				$helft = 2;
				print "<tr><td>&nbsp;</td></tr>";
			}
			else{
				$helft = $goal['Helft'];
			}
			print "<tr>\r\n";
				print "<td class=\"white_10\" align=\"right\">\r\n";
					if($goal['Team']->getId()==$wedstrijd->homeTeam()->getId()){
						if(empty($goal['Maker'])){
							print $goal['Team']->toString();
						}
						else{
							if(is_string($goal['Maker'])){
								print $goal['Maker'];
							}
							else{
								print $goal['Maker']->shortName();
							}
						}
						if(!empty($goal['Aangever'])){
							if(is_string($goal['Aangever'])){
							 print "<i> (".$goal['Aangever'].")</i>";
							}
							else{
							 print "<i> (".$goal['Aangever']->shortName().")</i>";
							}
							
						}
					}
				print "</td>\r\n";
				print "<td class=\"white_10\" align=\"center\">\r\n";
					print $goal['Volgorde'];
				print "</td>\r\n";
				print "<td class=\"white_10\" align=\"left\">\r\n";
					if($goal['Team']->getId()==$wedstrijd->awayTeam()->getId()){
						if(empty($goal['Maker'])){
							print $goal['Team']->toString();
                                                        if(is_string($goal['Aangever']) && !empty($goal['Aangever'])){
                                                         print "<i> (".$goal['Aangever'].")</i>";
                                                        }
						}
						else{
							if(is_string($goal['Maker'])){
								print $goal['Maker'];
							}
							else{
								print $goal['Maker']->shortName();
							}
							if(is_string($goal['Aangever'])){
							 print "<i> (".$goal['Aangever'].")</i>";
							}
							else{
							 print "<i> (".$goal['Aangever']->shortName().")</i>";
							}
						}
					}
				print "</td>\r\n";
				print "<td>\r\n";
					print "&nbsp;<a href=\"delete_goal.php?wedid=".$goal['wedid']."&id=".$goal['id']."\">".
								"<img src=\"images/delete.png\" border=\"0\"></a>";
				print "</td>";
			print "</tr>\r\n";
		}
	print "</table><br>\r\n";
	$kaart = new Kaart();
	$kaarten = $kaart->getMatchCards($wedstrijd->getId());
	print "<table>";
	foreach($kaarten as $kaart){
		$kaart = $kaart->getValues();
		print "<tr>";
			print "<td>";
				if($kaart['kleur']=="groen")
					print "<img src=\"images/groen.JPG\">";
				elseif($kaart['kleur']=="geel")
					print "<img src=\"images/geel.jpg\">";
				else
					print "<img src=\"images/rood.JPG\">";
			print "</td>";
			print "<td class=\"white_10\">";
				$speler = new Speler($kaart['ontvanger']);
				print "<b>".$speler->fullName()."</b>";
			print "</td>";
			print "<td class=\"white_10\">";
				print "<i>(".$kaart['reden'].")</i>";
			print "</td>";
			print "<td>";
				print "<a href=\"delete_card.php?wedid=".$wedstrijd->getId()."&id=".$kaart['id']."\">".
					"<img src=\"images/delete.png\" border=\"0\"></a>";
			print "</td>";
		print "</tr>";
	}
	print "</table>";
	print "<script type=\"text/javascript\" language=\"javascript\">\r\n";
		print "// <![CDATA[\r\n";
		print "Droppables.add('tegenstander_' + \$F('tegenstander'),".
					" {onDrop: function (drag, drop){goal_conceded(drag, drop)},".
					" accept: ['goal'], ".
					"hoverclass: 'goals_hoover'})\r\n";
		print "// ]]>\r\n";
	print "</script>\r\n";
}

function diff($in1, $in2){
	 	$in1 = strtolower($in1);
	 	$in2 = strtolower($in2);
	 	$lsh1 = levenshtein($in1, $in2);
		$lsh2 = levenshtein($in2, $in1);
		$len = strlen($in1) + strlen($in2);
		return ($lsh1 + $lsh2 / $len);
	}
    
function daysAgo($date1, $date2){
    $time1 = strtotime($date1);
    $time2 = strtotime($date2);
    return (round(($time2-$time1)/(24*60*60)));
}


?>
