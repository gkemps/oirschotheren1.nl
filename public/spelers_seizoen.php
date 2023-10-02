<?php
	include("functions/layout.inc.php");
	function showSpeler($speler, $seizoen, $i){
		print "<table width=\"225px\" class=\"border_inv\" border=\"0\" id=\"speler_$i\">\r\n";
			print "<tr>\r\n";
				print "<td rowspan=\"3\" width=\"60px\" class=\"player_picture\">\r\n";
					print "<img src=\"spelerfoto.php?id=".$speler->getId()."\" ".
							" class=\"player_picture\">\r\n";
				print "</td>\r\n";
				print "<td>\r\n";
					print "<b><a href=\"speler.php?id=".$speler->getId()."\">"
								.$speler->fullName()."</a></b>\r\n";
				print "</td>\r\n";
				print "<td width=\"20px\" align=\"left\">\r\n";
					print "(".$speler->getRugnummer($seizoen->getId()).")";
				print "</td>\r\n";
			print "</tr>\r\n";
			print "<tr>\r\n";
				print "<td colspan=\"2\" align=\"left\">\r\n";
					print $speler->getType();
				print "</td>\r\n";
			print "</tr>\r\n";
			print "<tr>\r\n";
				print "<td align=\"left\" colspan=\"2\">\r\n";
					$basis = count($speler->getMatches($seizoen->getId()));
					$bank = count($speler->getBenchMatches($seizoen->getId()));
					print "<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\"><tr>";
					print "<td style=\"padding:0px\"><b>".($basis - $bank)."</b></td>";
					print "<td style=\"padding:0px\"><img src=\"images/wedstrijd.gif\"></td>";
					print "<td>-</td>";
					print "<td style=\"padding:0px\"><b>".$bank."</b></td>";
					print "<td style=\"padding:0px\"><img src=\"images/bank.gif\"></td>";
					print "<td>-</td>";
					print "<td style=\"padding:0px\"><b>".count($speler->getSeasonGoals($seizoen->getId()))."</b></td>";
					print "<td style=\"padding:0px\"><img src=\"images/goal.gif\"></td>";
					print "<td>-</td>";
					print "<td style=\"padding:0px\"><b>".count($speler->getSeasonAssists($seizoen->getId()))."</b></td>";
					print "<td style=\"padding:0px\"><img src=\"images/assist.gif\"></td>";
					print "</tr></table>";
				print "</td>\r\n";
			print "</tr>\r\n";
			print "</tr>\r\n";
		print "</table>\r\n";
	}

    function showCoach($i){
		print "<table width=\"225px\" class=\"border_inv\" border=\"0\" id=\"speler_$i\">\r\n";
			print "<tr>\r\n";
				print "<td rowspan=\"3\" width=\"60px\" class=\"player_picture\">\r\n";
					print "<img src=\"images/pasfotos/zeeshan.png\" ".
							" class=\"player_picture\">\r\n";
				print "</td>\r\n";
				print "<td>\r\n";
					print "<b>Zeeshan Ali</b>\r\n";
				print "</td>\r\n";
			print "</tr>\r\n";
			print "<tr>\r\n";
				print "<td colspan=\"2\" align=\"left\">\r\n";
					print "Trainer-Coach";
				print "</td>\r\n";
			print "</tr>\r\n";
		print "</table>\r\n";
	}

    function showManager($i){
		print "<table width=\"225px\" class=\"border_inv\" border=\"0\" id=\"speler_$i\">\r\n";
			print "<tr>\r\n";
				print "<td rowspan=\"3\" width=\"60px\" class=\"player_picture\">\r\n";
					print "<img src=\"spelerfoto.php?id=37\" ".
							" class=\"player_picture\">\r\n";
				print "</td>\r\n";
				print "<td>\r\n";
					print "<b>Rick Speekenbrink</b>\r\n";
				print "</td>\r\n";
			print "</tr>\r\n";
			print "<tr>\r\n";
				print "<td colspan=\"2\" align=\"left\">\r\n";
					print "Manager";
				print "</td>\r\n";
            print "</tr>\r\n";
		print "</table>\r\n";
	}
	
	pre();
		$seizoen = new Seizoen();
		$seizoen->setSeason(date("Y-m-d"));
		print "<h2>Spelers seizoen ".$seizoen->toString()."</h2>";
		$spelers = $seizoen->getPlayers();
		$derde = ceil(count($spelers)/3);
        $show_coach = true;
        $show_manager = false;
		print "<table cellspacing=\"10\">\r\n";
		for($i=0;$i<$derde;$i++){
			print "<tr>\r\n";
				print "<td>\r\n";
					showSpeler($spelers[$i], $seizoen, $i);
				print "</td>\r\n";
				print "<td>\r\n";
					showSpeler($spelers[$derde + $i], $seizoen, ($derde + $i));
				print "</td>\r\n";
				if((2*$derde + $i)<count($spelers)){
					print "<td>\r\n";
						showSpeler($spelers[(2 * $derde) + $i], $seizoen, (2*$derde + $i));
					print "</td>\r\n";
				}
				else{
					if($show_coach){
                        print "<td>\r\n";
                            showCoach((2*$derde + $i));
                        print "</td>\r\n";
                        $show_coach = false;
                    }
                    elseif($show_manager){
                        print "<td>\r\n";
                            showManager((2*$derde + $i));
                        print "</td>\r\n";
                        $show_manager = false;
                    }

				}
			print "</tr>\r\n";
		}
		print "<script type=\"text/javascript\" language=\"javascript\">\r\n";
			for($i=0;$i<count($spelers)+2;$i++){
				if($i==0)
					print "Effect.Fade('speler_".$i."', {duration: 0.3, from: 0.0, to: 1.0});\r\n";
				else
					print "Effect.Fade('speler_".$i."', {duration: 0.3, from: 0.0, to: 1.0, queue: 'end'});\r\n";
			}
		print "</script>\r\n";
		print "</table>";
	post();
?>