<?php
require_once("functions/layout.inc.php");
	function playerOnPos($pos, $wed, $width){
	 	$posid = $pos->getId();
	 	$wedid = $wed->getId();
	 	$opst = new Opstelling();
	 	$speler = $opst->getPlayer($wedid, $posid);
		print "<table width=\"".$width."px\" border=\"0\">";
			print "<tr>";
				print "<td rowspan=\"5\" class=\"player_picbox\">";
					print "<img src=\"spelerfoto.php?id=".$speler->getId()."\" ".
						"class=\"player_picture\">";
				print "</td>";
			print "</tr>";
			print "<tr>";
				print "<td colspan=\"2\" class=\"bold_10\">";
					print $speler->shortName();
				print "</td>";
			print "</tr>";
			print "<tr>";
				print "<td colspan=\"2\" class=\"normal_10\">";
					print "<i>".$pos->toString()."</i>";
				print "</td>";
			print "</tr>";
			print "<tr>";
				print "<td colspan=\"2\">";
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
				print "</td>";
			print "</tr>";
			print "<tr>";
				print "<td colspan=\"2\" id=\"kaart_".$posid."\">";
					$cards = $speler->getCards($wed->getId());
					foreach($cards as $card){
						if($card->getColor()=="groen")
							print "<img src=\"images/groen.JPG\" class=\"kaart\">";
						elseif($card->getColor()=="geel")
							print "<img src=\"images/geel.jpg\" class=\"kaart\">";
						else
							print "<img src=\"images/rood.JPG\" class=\"kaart\">";
					}
					if(empty($cards))
						print "&nbsp;";
				print "</td>";
			print "</tr>";
		print "</table>";
	}
	
	function playerOnBench($speler, $wed){
		print "<table width=\"".$width."px\" border=\"0\">";
			print "<tr>";
				print "<td rowspan=\"5\" class=\"player_picbox\">";
					print "<img src=\"spelerfoto.php?id=".$speler->getId()."\" ".
						"class=\"player_picture\">";
				print "</td>";
			print "</tr>";
			print "<tr>";
				print "<td colspan=\"2\" class=\"bold_10\">";
					print $speler->shortName();
				print "</td>";
			print "</tr>";
			print "<tr>";
				print "<td colspan=\"2\" class=\"normal_10\">";
					print "<i>bank</i>";
				print "</td>";
			print "</tr>";
			print "<tr>";
				print "<td colspan=\"2\">";
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
				print "</td>";
			print "</tr>";
			print "<tr>";
				print "<td colspan=\"2\" id=\"kaart_".$posid."\">";
					$cards = $speler->getCards($wed->getId());
					foreach($cards as $card){
						if($card->getColor()=="groen")
							print "<img src=\"images/groen.JPG\" class=\"kaart\">";
						elseif($card->getColor()=="geel")
							print "<img src=\"images/geel.jpg\" class=\"kaart\">";
						else
							print "<img src=\"images/rood.JPG\" class=\"kaart\">";
					}
					if(empty($cards))
						print "&nbsp;";
				print "</td>";
			print "</tr>";
		print "</table>";
	}

	if($_GET['id']){
		$wedstrijd = new Wedstrijd($_GET['id']);
		$systeem = $wedstrijd->getSystem();
		$lines = array_reverse($systeem->getLines());
		$positions = $systeem->getPositions();
		print "<div class=\"veld\">";
		foreach($lines as $line){
		 	print "<table width=\"510px\" cellspacing=\"2\" border=\"0\">\r\n";
				print "<tr>";
					for($i=0;$i<$line;$i++){
						$boxwidth = 165;
					 	$width = 100 / $line;
					 	$pos = array_pop($positions);
					 	$id = $pos->getId();
						print "<td align=\"center\" id=\"box_$id\" width=\"$width%\" ".
							" style=\"padding:0px; opacity: 0;\">\r\n";
							playerOnPos($pos, $wedstrijd, $boxwidth);
						print "</td>\r\n";
					}
				print "</tr>\r\n";
			print "</table>\r\n";
		}
		print "</div>";
		$opstelling = new Opstelling();
		$bankspelers = $opstelling->getBenchPlayers($wedstrijd->getId());
		print "<div class=\"bank\">";
		print "<table cellspacing=\"2\" border=\"0\">\r\n";
			$i = 0;
			foreach($bankspelers as $speler){
				print "<tr>";
					print "<td align=\"left\" id=\"bank_$i\" ".
							"style=\"padding:0px; opacity: 0;\">";
						  playerOnBench($speler, $wedstrijd);
					print "</td>";
				print "</tr>";	
				$i++;
			}
		print "</table>";
		print "</div>";
		print "<script type=\"text/javascript\" language=\"javascript\">\r\n";
		$positions = $systeem->getPositions();
		for($i=0;$i<count($positions);$i++){
			$id = $positions[$i]->getId();
			if($i==0)
				print "Effect.Fade('box_".$id."', {duration: 0.3, from: 0.0, to: 1.0});\r\n";
			else
				print "Effect.Fade('box_".$id."', {duration: 0.3, from: 0.0, to: 1.0, queue: 'end'});\r\n";
		}
		for($i=0;$i<count($bankspelers);$i++){
			print "Effect.Fade('bank_".$i."', {duration: 0.3, from: 0.0, to: 1.0, queue: 'end'});\r\n";
		}
	print "</script>\r\n";
	}	

?>