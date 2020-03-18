<?php
	require_once("functions/layout.inc.php");
	pre();
		print "<div class=\"div_left\" style=\"width:700px\">";
			print "<center>";
			$gastenboek = new Gastenboek();
			$start = 0;
			if(isset($_GET['start']))
				$start = $_GET['start'];
			$entries = $gastenboek->getEntries(10, $start);
			print "<table width=\"80%\" style=\"margin-top:20px\">";
			foreach($entries as $entry){
				$values = $entry->getValues();
				print "<tr>";
					print "<td>";
						print "<table>";
							print "<tr>";
								print "<td rowspan=\"2\" width=\"140px\" valign=\"top\"
										style=\"text-align:right\">";
									print $values['datum']."<br>";
									print "<div class=\"div_tiny\">".
											$entry->getPostNr()."e bericht";
									print "</div>";
									print "<div class=\"div_tiny\">".
											$entry->getNrOfUserPosts($values['naam']).
													" geschreven";
									print "</div>";
								print "</td>";
								print "<td>";
									print "<b>".strtoupper($values['naam'])."</b>";
								print "<td>";
							print "</tr>";
							print "<tr>";
								print "<td>";
								$bericht = stripslashes($values['bericht']);
								$bericht = nl2br($bericht);
								$bericht = strip_tags($bericht, '<a><b><i><u><p><br>');
								$bericht = str_replace(":)", "<img src=\"images/emoticons/smile.gif\">", $bericht);
								$bericht = str_replace(";)", "<img src=\"images/emoticons/wink.gif\">", $bericht);
								$bericht = str_replace(":p", "<img src=\"images/emoticons/tongue.gif\">", $bericht);
								$bericht = str_replace(":D", "<img src=\"images/emoticons/bigsmile.gif\">", $bericht);
								$bericht = str_replace(":o", "<img src=\"images/emoticons/amazed.gif\">", $bericht);
								$bericht = str_replace(":@", "<img src=\"images/emoticons/angry.gif\">", $bericht);
								$bericht = str_replace(":(", "<img src=\"images/emoticons/sad.gif\">", $bericht);
								$bericht = str_replace(":s", "<img src=\"images/emoticons/sick.gif\">", $bericht);
								$bericht = str_replace("(H)", "<img src=\"images/emoticons/sun.gif\">", $bericht);
								$bericht = str_replace(":|", "<img src=\"images/emoticons/stoned.gif\">", $bericht);
								$bericht = str_replace(":$", "<img src=\"images/emoticons/shame.gif\">", $bericht);
								$bericht = str_replace("(B)", "<img src=\"images/emoticons/beer.gif\">", $bericht);
								$bericht = str_replace("(C)", "<img src=\"images/emoticons/coffee.gif\">", $bericht);
								$bericht = str_replace("(Y)", "<img src=\"images/emoticons/yes.gif\">", $bericht);
								$bericht = str_replace("(N)", "<img src=\"images/emoticons/no.gif\">", $bericht);
								print $bericht;
								print "</td>";
							print "</tr>";
						print "</table>";
						print "<hr>";
					print "</td>";
				print "</tr>";
			}
			print "</table>";
			if($start > 0)
				print "<a href=\"gastenboek_bekijken.php?start=".($start-10)."\">Vorig pagina</a>";
			if($start < $gastenboek->getNrOfPosts())
				print "&nbsp;&nbsp;&nbsp;";
				print "<a href=\"gastenboek_bekijken.php?start=".($start+10)."\">Volgende pagina</a>";
			print "</center>";
		print "</div>";
	post();
?>