<?php
	require_once("functions/layout.inc.php");
	pre();
	
	function printTable($table){
		print "<table>";
			$oirschot = false;
			for($i=0;$i<4;$i++){
				if($table[$i]['teamnaam']=="Oirschot")
					$oirschot = true;
				print "<tr>";
					print "<td>".($i + 1).".</td>";
					print "<td><b>".$table[$i]['teamnaam']."<b></td>";
					print "<td>".$table[$i]['gespeeld']."</td>";
					print "<td>".$table[$i]['punten']."</td>";
					print "<td>+".$table[$i]['saldo']."</td>";
				print "</tr>";
			}
			if(!$oirschot){
				for($i=4;$i<count($table);$i++){
					if($table[$i]['teamnaam']=="Oirschot"){
						print "<tr>";
							print "<td>".($i + 1).".</td>";
							print "<td><b>".$table[$i]['teamnaam']."<b></td>";
							print "<td>".$table[$i]['gespeeld']."</td>";
							print "<td>".$table[$i]['punten']."</td>";
							print "<td>+".$table[$i]['saldo']."</td>";
						print "</tr>";
					}
				}
			}
		print "</table>";
	}
	print "<h2>Winterkampioen vs. Kampioen</h2>";
	print "<table>";
	for($i=2002;$i<2008;$i++){
		print "<tr><td colspan=\"2\"><b><u>Seizoen ".$i."/".($i+1)."</u></b></td></tr>";
		print "<tr>";
		$winter = $i."-12-31";
		$zomer = ($i+1)."-6-31";
		$winterstand = new Stand($winter);
		$winterstand->calcTable();
		$table = $winterstand->getTable();
		print "<td style=\"text-align:center\">";
			printTable($table);
		print "</td>";
		$eindstand = new Stand($zomer);
		$eindstand->calcTable();
		$table = $eindstand->getTable();
		print "<td style=\"text-align:center\">";
			printTable($table);
		print "</td>";
		print "</tr>";
	}
	print "</table>";

	post();
?>