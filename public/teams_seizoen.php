<?php
	require_once("functions/layout.inc.php");
	pre();
		function showTeam($team, $seizoen, $i){
		 $values = $team->getValues();
		 $stand = new Stand(date("Y-m-d"));
		 $standrij = $stand->getTeamRow($team->getId());
		print "<table width=\"300px\" class=\"border_inv\" border=\"0\" id=\"team_$i\">\r\n";
			print "<tr>\r\n";
				print "<td rowspan=\"3\" width=\"100px\" class=\"team_logo\">\r\n";
					print "<img src=\"teamlogo.php?id=".$team->getId()."\" ".
							" class=\"team_logo\">\r\n";
				print "</td>\r\n";
				print "<td>\r\n";
					print "<b><a href=\"team.php?id=".$team->getId()."\">"
								.$team->toString()."</a></b>\r\n";
				print "</td>\r\n";
			print "</tr>\r\n";
			print "<tr>\r\n";
				print "<td align=\"left\">\r\n";
					print "<i>".$values['Woonplaats']."</i>";
				print "</td>\r\n";
			print "</tr>\r\n";
			print "<tr>\r\n";
				print "<td align=\"left\">\r\n";
					print $standrij['gespeeld'];
					print "<b> GS</b>";
					print " - ";
					print $standrij['gewonnen'];
					print "<b> GW</b>";
					print " - ";
					print $standrij['gelijk'];
					print "<b> GL</b>";
					print " - ";
					print $standrij['verloren'];
					print "<b> VL</b>";
				print "</td>\r\n";
			print "</tr>\r\n";
			print "</tr>\r\n";
		print "</table>\r\n";
	}
	
	$seizoen = new Seizoen();
	$seizoen->setSeason(date("Y-m-d"));
	print "<h2>Teams seizoen ".$seizoen->toString()."</h2>";
	$teams = $seizoen->getTeams();
	$helft = ceil(count($teams)/2);
	print "<center>";
	print "<table cellspacing=\"10\">\r\n";
	for($i=0;$i<$helft;$i++){
		print "<tr>\r\n";
			print "<td>\r\n";
				showTeam($teams[$i], $seizoen, $i);
			print "</td>\r\n";
			if(($helft + $i) < count($teams)){
				print "<td>\r\n";
					showTeam($teams[$helft + $i], $seizoen, ($helft + $i));
				print "</td>\r\n";
			}
			else{
				print "<td>\r\n";
					print "&nbsp;";
				print "</td>\r\n";
			}
		print "</tr>\r\n";
	}
	print "<script type=\"text/javascript\" language=\"javascript\">\r\n";
		for($i=0;$i<count($teams);$i++){
			if($i==0)
				print "Effect.Fade('team_".$i."', {duration: 0.3, from: 0.0, to: 1.0});\r\n";
			else
				print "Effect.Fade('team_".$i."', {duration: 0.3, from: 0.0, to: 1.0, queue: 'end'});\r\n";
		}
	print "</script>\r\n";
	print "</table>";
	print "</center>";
	post();
?>