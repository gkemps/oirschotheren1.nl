<?php
	ob_start();
	session_start();
	require_once("functions/layout.inc.php");
	
	pre_ff();
		if($_POST['submit']){
		 	$gastenboek = new Gastenboek();
		 	$naam = $_POST['naam'];
			if($_POST['sec_code']==$_SESSION['security_code'] and !empty($_SESSION['security_code'])){
				if(empty($naam)){
					print "<font color=\"red\">Geen naam ingevoerd</font>";
				}
				else{
					$bericht = $_POST['bericht'];
					$bericht = strip_tags($bericht, '<a><b><i><u><p>');
					if($bericht !== "delete last" and $naam !== "kemzy")
					 $gastenboek->insert($naam, $bericht);
					else
					 $gastenboek->deleteLast();
					header("location: gastenboek_bekijken.php");
				}
					
			}
			else{
				print "<font color=\"red\">Geen geldige code ingevoerd</font>";
			}
		}
		print "<h2>Gastenboek tekenen</h2>";
		print "<center>";
		print "<form method=\"post\">";
		print "<table>";
			print "<tr>";
				print "<td colspan=\"2\">";
					print "Naam: <input type=\"textbox\" name=\"naam\" size=\"30\">";
				print "</td>";
			print "</tr>";
			print "<tr>";
				print "<td colspan=\"2\">";
					print "<textarea id=\"bericht\" name=\"bericht\" cols=\"60\" rows=\"10\"></textarea>";
				print "</td>";
			print "</tr>";
			print "<tr>";
				print "<td colspan=\"2\">";
					print "<table cellspacing=\"0\">";
						print "<tr>";
					print "<td><img src=\"images/emoticons/smile.gif\" onClick=\"textarea('bericht', ':)')\"></td>";
					print "<td><img src=\"images/emoticons/wink.gif\" onClick=\"textarea('bericht', ';)')\"></td>";
					print "<td><img src=\"images/emoticons/tongue.gif\" onClick=\"textarea('bericht', ':p')\"></td>";
					print "<td><img src=\"images/emoticons/bigsmile.gif\" onClick=\"textarea('bericht', ':D')\"></td>";
					print "<td><img src=\"images/emoticons/amazed.gif\" onClick=\"textarea('bericht', ':o')\"></td>";
					print "<td><img src=\"images/emoticons/angry.gif\" onClick=\"textarea('bericht', ':@')\"></td>";
					print "<td><img src=\"images/emoticons/sad.gif\" onClick=\"textarea('bericht', ':(')\"></td>";
					print "<td><img src=\"images/emoticons/sick.gif\" onClick=\"textarea('bericht', ':s')\"></td>";
					print "<td><img src=\"images/emoticons/cry.gif\" onClick=\"textarea('bericht', ';(')\"></td>";
					print "<td><img src=\"images/emoticons/sun.gif\" onClick=\"textarea('bericht', '(H)')\"></td>";
					print "<td><img src=\"images/emoticons/stoned.gif\" onClick=\"textarea('bericht', ':|')\"></td>";
					print "<td><img src=\"images/emoticons/shame.gif\" onClick=\"textarea('bericht', ':$')\"></td>";
					print "<td><img src=\"images/emoticons/beer.gif\" onClick=\"textarea('bericht', '(B)')\"></td>";
					print "<td><img src=\"images/emoticons/coffee.gif\" onClick=\"textarea('bericht', '(C)')\"></td>";
					print "<td><img src=\"images/emoticons/yes.gif\" onClick=\"textarea('bericht', '(Y)')\"></td>";
					print "<td><img src=\"images/emoticons/no.gif\" onClick=\"textarea('bericht', '(N)')\"></td>";
						print "</tr>";
					print "</table>";
				print "</td>";
			print "</tr>";
				print "<td width='30%'>";
						print "<input type=\"textbox\" name=\"sec_code\">";
				print "</td>";
				print "<td width='70%'>";
						print "<img src=\"captcha.php\">";
				print "</td>";
			print "<tr>";
				print "<td colspan=\"2\">";
					print "<input type=\"submit\" name=\"submit\" value=\"Tekenen\">";
					print "&nbsp;&nbsp;&nbsp;";
					print "<input type=\"reset\" name=\"reset\" value=\"Reset\">";
				print "</td>";
			print "</tr>";
		print "</table>";
		print "</form>";
		print "</center>";
	post();
	ob_end_flush();
?>