<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

if(!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']){
    header("location: login.php");
}
else{

require_once("classes/db.class.php");

require_once("classes/wedstrijd.class.php");



print "<html>";

	print "<head>";

	print "<title>Oirschot Heren 1 Admin Site</title>";

	print "</head>";

	print "<body bgcolor=\"#DAFFDB\">";

	print "<center>";

	

$wedstrijd = new Wedstrijd();

$seizoen = new Seizoen();

$seizoen->setSeason(date("Y-m-d"));

$wedstrijden = $wedstrijd->getTeamMatches(2, $seizoen->getId());

print "<form method=\"post\" action=\"invoer.php\">";

print "<select name=\"wedid\">";

foreach($wedstrijden as $wedstrijd){

 	if($_POST['wedid']==$wedstrijd->getId())

 		$sel = "selected";

 	else

 		$sel = "";

	print "<option value=\"".$wedstrijd->getId()."\" $sel>".

			$wedstrijd->toString()." (".$wedstrijd->getDate().")</option>";

}

print "</select>";

print "<input type=\"submit\" name=\"submit\" value=\"Deze Speelronde!\">";

print "<br><br>";

if(isset($_POST['submit']) or isset($_POST['submit2'])){

	$wedstrijd = new Wedstrijd($_POST['wedid']);

	$values = $wedstrijd->getValues();

	$matches = $seizoen->getRoundMatches($values['Speelronde']);

	print "<table>";

	foreach($matches as $match){

		print "<tr>";

			print "<td>";

				print $match->homeTeam()->toString();

			print "</td>";

			print "<td>";

				print " - ";

			print "</td>";

			print "<td>";

				print $match->awayTeam()->toString();

			print "</td>";

			print "<td>";

				$id = $match->getId();

				$scores = $match->getScores();

				print "<input type=\"textbox\" value=\"".$scores['Scorethuis']."\" name=\"home_$id\" size=\"3\">";

			print "</td>";

			print "<td>";

				print " - ";

			print "</td>";

			print "<td>";

				print "<input type=\"textbox\" value=\"".$scores['Scoreuit']."\" name=\"away_$id\"  size=\"3\">";

			print "</td>";

			print "<td>";

				if($match->played()){

					if($match->OirschotInMatch()){

						//print "[ <a href=\"verslag.php?wedid=".$match->getId()."\">Verslagen invoeren</a> ]";
                        print "[ <a href=\"dragdrop.php?wedid=".$match->getId()."\">Statistieken invoeren</a> ]";

					}

					//else

						//print "[ <a href=\"verslag.php?wedid=".$match->getId()."\">Verslagen invoeren</a> ]";

				}

			print "</td>";

		print "</tr>";	

	}

	print "<tr><td colspan=\"6\">";

		print "<input type=\"submit\" value=\"opslaan\" name=\"submit2\">";

	print "</td></tr>";

	print "</table>";

	print "</form>";

}

if(isset($_POST['submit2']) && $_POST['submit2']){

	foreach($_POST as $key => $value){

		if(substr($key, 0, 4)=="home" and (!empty($value) or $value=="0")){

			$wedid = substr($key, strpos($key, "_")+1);

			$wedstrijd = new Wedstrijd($wedid);

			if(!empty($_POST['away_'.$wedid]) or $_POST['away_'.$wedid]=="0") {

				$wedstrijd->setScores($value, $_POST["away_" . $wedid]);

			}

		}

	}

	print "Speelronde succesvol opgeslagen! Selecteer hierboven deze ronde opnieuw om statistieken in te voeren";

}



	print "</center>";

	print "</body>";

print "</html>";
}