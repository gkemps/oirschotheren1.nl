<?php
    ob_start();
	header('Content-type: text/html; charset=utf-8');
	require_once("functies.php");
?>
<html>
	<head>
		<link href="style.css" rel="stylesheet" type="text/css">
		<title>Sportman, Sportvrouw en Sportploeg nominaties 2008/2009</title>
		<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	</head>
	
	<body>
		
		<div>
		
		<?php
		
			switch($_REQUEST['s']){
				
				case 'stem':
				   $mannen[] = "Rick Speekenbrink";
				   $mannen[] = "Peter den Ouden";
				   $mannen[] = "Frank van de Putte";

				   $vrouwen[] = "Chantal van de Noort";
				   $vrouwen[] = "Marjolijn Hendriks";
				   $vrouwen[] = "Janne van Rooy";

				   $ploegen[] = "Jongens B1";
				   $ploegen[] = "Jongens C1";
				   $ploegen[] = "Dames 3";
					print "<h2>Stemming 2010/2011</h2>";
					print "<p><i>Maak hieronder uw keuze (volgorde van de kandidaten is random): ";
					print "<form method=\"post\">";
					print "<table>";
						print "<tr>";
							print "<td valign=\"top\">";
								print "Sportman:";
							print "</td>";
							print "<td>";
								stemlijst("sportman", $mannen);
							print "</td>";
						print "</tr>";
						print "<tr>";
							print "<td valign=\"top\">";
								print "Sportvrouw:";
							print "</td>";
							print "<td>";
								stemlijst("sportvrouw", $vrouwen);
							print "</td>";
						print "</tr>";
						print "<tr>";
							print "<td valign=\"top\">";
								print "Sportploeg:";
							print "</td>";
							print "<td>";
								stemlijst("sportploeg", $ploegen);
							print "</td>";
						print "</tr>";
						print "<tr>";
							print "<td valign=\"top\">";
								print "Motivatie:";
							print "</td>";
							print "<td>";
								print "<textarea name=\"motivatie\" rows=\"5\" cols=\"40\">optioneel</textarea>";
							print "</td>";
						print "</tr>";
					print "</table>";
					print "<input type=\"hidden\" name=\"s\" value=\"opslaan\">";
					print "<input type=\"submit\" name=\"submit\" value=\"Stem!\">";
					print "</form>";
				break;
                
                case 'nomineren':
                    print "<h2>Nominatie 20010/2011</h2>";
                    print "<p><i>Maak hieronder uw keuze (begin keuze is random):";
                    print "<form method=\"post\">";
                    print "<table>";
                        print "<tr>";
                            print "<td valign=\"top\">";
                                print "Sportman:";
                            print "</td>";
                            print "<td>";
                                nomineerlijst("M");
                            print "</td>";
                        print "</tr>";
                        print "<tr>";
                            print "<td valign=\"top\">";
                                print "Sportvrouw:";
                            print "</td>";
                            print "<td>";
                                nomineerlijst("V");   
                            print "</td>";
                        print "</tr>";
                        print "<tr>";
                            print "<td valign=\"top\">";
                                print "Sportploeg:";
                            print "</td>";
                            print "<td>";
                                nomineerlijst("P");   
                            print "</td>";
                        print "</tr>";
                    print "</table>";
                    print "<input type=\"hidden\" name=\"s\" value=\"opslaan_nom\">";
                    print "<input type=\"submit\" name=\"submit\" value=\"Nomineer!\">";
                    print "</form>";    
                break;
				
                case 'opslaan':
                        //stemmen($_REQUEST['M']);
                        //stemmen($_REQUEST['V']);
                        //ploegen($_REQUEST['P']);
                        stemmen($_REQUEST["sportman"], $_REQUEST["sportvrouw"], $_REQUEST["sportploeg"], $_REQUEST['motivatie']);
                        header("location: index.php");
                break;
                
                case "opslaan_nom":
                     nomineren($_REQUEST['V']);
                     nomineren($_REQUEST['M']); 
                     ploegen($_REQUEST['P']); 
                     print "<b>Bedankt voor je nominaties!</b>";
                break;
				
				
                
                default:
					print "<h2>Sportman, Sportvrouw & Sportploeg 2010/2011</h2>";
					print "<p><i>De afgelopen weken heb je jouw favoriete sportman, sportvrouw en sportploeg 
                    kunnen nomineren. Hieruit hebben we voor elke categorie de besten geselecteerd. Iedereen 
                    mag nog 1 maal zijn stem uitbrengen in iedere categorie. De uitslag hiervan zal bekend gemaakt 
                    worden op de BBQ van 29 mei. </i></p>";
                                        print "<p>Hieronder lichten we de genomineerden toe voor zover ze nog niet bij iedereen bekend zijn:</p>";
?>
                    <table>
                        <tr>
                            <td><b>Sportman</b></td>
                        </tr>
                        <tr>
                            <td valign="top">Peter den Ouden</td>
                            <td valign="top"><i>Voorzitter materiaal commissie. Keeper van HVC en Heren 2 zaal. Vader van Amber en Merle.</i></td>
                        </tr>
                        <tr>
                            <td valign="top">Frank van de Putte</td>
                            <td valign="top"><i>Lid barcommissie. Voorzitter Jeugd Toernooi Commissie. Vader van Jelle en Lisa</i></td>
                        </tr>
                        <tr>
                            <td valign="top">Rick Speekenbrink</td>
                            <td valign="top"><i>Lid Jeugd Toernooi Commissie. Speler van Heren 2 (zaal), (reserve) keeper van Heren 1</i></td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td><b>Sportvrouw</b></td>
                        </tr>
                        <tr>
                            <td valign="top">Marjolijn Hendriks</td>
                            <td valign="top"><i>Verantwoordelijk voor bar- en vervoerschema in Jeugd commissie (aftredend). Moeder van Bram en Carlijn.</i></td>
                        </tr>
                        <tr>
                            <td valign="top">Chantal van de Noort</td>
                            <td valign="top"><i>Trainster van JE3 & ME2. Moeder van Minke en Hidde.</i></td>
                        </tr>
                        <tr>
                            <td valign="top">Janne van Rooy</td>
                            <td valign="top"><i>Trainer/coach Meisjes MB2. Speelster van Dames 1.</i></td>
                        </tr>
                    </table>
                    <br />
                    <?php
					if(!gestemd()){
						print "Je hebt nog niet gestemd, klik <a href=\"?s=stem\">hier</a> om dat nu te doen.";
					}
					else{
						print "<b>Je hebt al gestemd!</b>";
					}
                break;

                /**
                default:
                    print "<h2>Sportman, Sportvrouw & Sportploeg 2010/2011</h2>";
                    print "<p><i>In de komende weken zal er weer een Sportman, Sportvrouw en Sportploeg
                            verkiezing gehouden worden. Jullie kunnen in de eerste week aangeven wie jullie
                            in elke categorie graag willen nomineren. In de laatste week voorafgaand aan de
                            jaarlijkse BBQ (29 mei) wordt uit de top 3 van elke categorie uiteindelijk een
                            definitieve keuze gemaakt.</i></p>

                            <p><i>Vorig jaar won Annemarie Raats bij de vrouwen, was het Sjors van de
                            Schoot bij de heren en ging Heren Veteranen A er
                                    met de Sportploeg van het jaar vandoor.</i></p>";
                   if(!genomineerd()){
                        print "Je hebt vandaag nog niet genomineerd, klik <a href=\"?s=nomineren\">hier</a> om dat nu te doen.";
                    }
                    else{
                        print "<b>Je hebt vandaag je nominaties al doorgegeven! Probeer het morgen nog een keer.</b>";
                    }             
                break;
                
			
                 * 
                 */
		}
		?>
		
		</div>
	
	</body>

</html>
<?php 
    ob_end_flush();
?>