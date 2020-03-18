<?php
	require_once("functions/layout.inc.php");
	pre();
		print "<h2>Alle Teams</h2>";
		print "<table border=\"0\" width=\"100%\">";
		$k = 0;
		for($i=65;$i<=90;$i++){
			$x = chr($i);
			$team = new Team();
			$teams = $team->getFLTeams($x);
			if($k % 4 == 0)
				print "<tr>";
			if(count($teams)>0){
			 		$k++;
					print "<td valign=\"top\">";
						print "<table>";
							print "<tr><td><h3>$x</h3></td></tr>";
							foreach($teams as $team){
								print "<tr><td>";
									print "<a href=\"team.php?id=".$team->getId()."\">";
									print $team->toString();
									print "</a>";
								print "</td></tr>";
							}
						print "</table>";
					print "</td>";	
			}
			if($k % 4 == 0)
				print "</tr>";
		}
		print "</table>";
	post();

?>