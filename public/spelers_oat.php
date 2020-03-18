<?php
	require_once("functions/layout.inc.php");
	pre();
		print "<h2>Alle Spelers</h2>";
		print "<table border=\"0\" width=\"100%\">";
		$k = 0;
		for($i=65;$i<=90;$i++){
			$x = chr($i);
			$speler = new Speler();
			$spelers = $speler->getFLPlayers($x);
			if($k % 4 == 0)
				print "<tr>";
			if(count($spelers)>0){
			 		$k++;
					print "<td valign=\"top\">";
						print "<table>";
							print "<tr><td><h3>$x</h3></td></tr>";
							foreach($spelers as $speler){
								print "<tr><td>";
									print "<a href=\"speler.php?id=".$speler->getId()."\">";
									print $speler->fullName();
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