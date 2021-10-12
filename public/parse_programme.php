<?php
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
	require_once("functions/layout.inc.php");

	

	function dutch_month($input){

		$result = str_replace("januari", "january", $input);

		$result = str_replace("februari", "february", $result);

		$result = str_replace("maart", "march", $result);

		$result = str_replace("mei", "may", $result);

		$result = str_replace("juni", "june", $result);

		$result = str_replace("augustus", "august", $result);

		$result = str_replace("oktober", "october", $result);

		return $result;

	}

	

	$path = getcwd()."/programma.txt";

	$lines = file($path);

	$team = new Team();

	$wedstrijd = new Wedstrijd();

	$i = 1;
    //print_r($lines);
	foreach($lines as $line){

		if(strtolower(substr($line, 0, 6))=="zondag"){

		 	$input = strtolower(trim(substr($line, 7)));

		 	$input = dutch_month($input);

			print "<br>".date("Y-m-d", strtotime($input));

			print "(".trim($input).")";

			$datum = date("Y-m-d", strtotime($input));

		}

		else{

		 	$team1 = trim(substr($line, 0, strpos($line, "H1")));

		 	$line = substr($line, strpos($line, "H1")+2);

		 	$team2 = trim(substr($line, 0, strpos($line, "H1")));

			$team1 = $team->findTeam($team1);

			$team2 = $team->findTeam($team2);



			print $team1->toString()." - ".$team2->toString();

			print "[".$datum." ".$team1->getId()." - ".$team2->getId()." ".

						"(".ceil($i/6).")]";

			 $wedstrijd->insert($datum, $team1->getId(), $team2->getId(), ceil($i/6));

			$i++;

		}

		print "<br>";

	}



?>
