<?php
	require_once("functions/layout.inc.php");
	
	//gescoord en meest aaneengesloten wedstrijden
	function consGoals(){
	 	$goal = new Goal();
		$speler = new Speler();
		$spelers = $speler->getList();
		$max = 0;
		$maxer = "";
		$maxweds = array();
		foreach($spelers as $speler){
		 	$maxspeler = 0;
			$matches = $speler->getMatches();
			$serie = 0;
			$weds = array();
			$spelerweds = array();
			foreach($matches as $match){
				if($goal->playerScored($speler->getId(), $match->getId())){
					$serie++;
					array_push($weds, $match->getDate());
				}
				else{
					if($serie>$maxspeler){
						$maxspeler = $serie;
						$spelerweds = $weds;
					}
					$serie = 0;
					$weds = array();
				}
			}
			if($serie>$maxspeler){
				$maxspeler = $serie;
				$spelerweds = $weds;
			}
			if($maxspeler>$max){
				$max = $maxspeler;
				$maxer = $speler;
				$maxweds = $spelerweds;
			}
		}
		return array($max, $maxer, $maxweds);
	}
	
	pre();
		print "<div style=\"width:700px\">";
			print "<h2>Goal statistieken</h2>";
			print "<table>";
				print "<tr>";
					print "<td>";
						print "<b>Topscoorder (seizoen):</b>";
					print "</td>";
					print "<td>";
						$seizoen = new Seizoen();
						$seizoen->setSeason(date("Y-m-d"));
						$goal = new Goal();
						$topsc = $goal->getSeasonTopscoorders($seizoen->getId());
						$top = $topsc[0];
						print $top[1]->toString()." (".$top[0].")";
					print "</td>";
					print "<td>";
						print "<a href=\"\">Lijst</a>";
					print "</td>";
				print "</tr>";
				print "<tr>";
					print "<td>";
						print "<b>Topscoorder (aller tijden):</b>";
					print "</td>";
					print "<td>";
						$goal = new Goal();
						$topsc = $goal->getTopscoorders();
						$top = $topsc[0];
						print $top[1]->toString()." (".$top[0].")";
					print "</td>";
					print "<td>";
						print "<a href=\"\">Lijst</a>";
					print "</td>";
				print "</tr>";
				print "<tr>";
					print "<td>";
						print "<b>Strafcorner topscoorder (seizoen):</b>";
					print "</td>";
					print "<td>";
						$seizoen = new Seizoen();
						$seizoen->setSeason(date("Y-m-d"));
						$goal = new Goal();
						$topsc = $goal->getSeasonTopscoorders($seizoen->getId(), 'Strafcorner');
						$top = $topsc[0];
						print $top[1]->toString()." (".$top[0].")";
					print "</td>";
					print "<td>";
						print "<a href=\"\">Lijst</a>";
					print "</td>";
				print "</tr>";
				print "<tr>";
					print "<td>";
						print "<b>Strafcorner topscoorder (aller tijden):</b>";
					print "</td>";
					print "<td>";
						$goal = new Goal();
						$topsc = $goal->getTopscoorders('Strafcorner');
						$top = $topsc[0];
						print $top[1]->toString()." (".$top[0].")";
					print "</td>";
					print "<td>";
						print "<a href=\"\">Lijst</a>";
					print "</td>";
				print "</tr>";
				print "<tr>";
					print "<td>";
						print "<b>Strafbal topscoorder (seizoen):</b>";
					print "</td>";
					print "<td>";
						$seizoen = new Seizoen();
						$seizoen->setSeason(date("Y-m-d"));
						$goal = new Goal();
						$topsc = $goal->getSeasonTopscoorders($seizoen->getId(), 'Strafbal');
						$top = $topsc[0];
						print $top[1]->toString()." (".$top[0].")";
					print "</td>";
					print "<td>";
						print "<a href=\"\">Lijst</a>";
					print "</td>";
				print "</tr>";
				print "<tr>";
					print "<td>";
						print "<b>Strafbal topscoorder (aller tijden):</b>";
					print "</td>";
					print "<td>";
						$goal = new Goal();
						$topsc = $goal->getTopscoorders('Strafbal');
						$top = $topsc[0];
						print $top[1]->toString()." (".$top[0].")";
					print "</td>";
					print "<td>";
						print "<a href=\"\">Lijst</a>";
					print "</td>";
				print "</tr>";
				print "<tr>";
					print "<td>";
						print "<b>Topscoorder uit rebound (seizoen):</b>";
					print "</td>";
					print "<td>";
						$seizoen = new Seizoen();
						$seizoen->setSeason(date("Y-m-d"));
						$goal = new Goal();
						$topsc = $goal->getSeasonTopscoorders($seizoen->getId(), 'Rebound');
						$top = $topsc[0];
						print $top[1]->toString()." (".$top[0].")";
					print "</td>";
					print "<td>";
						print "<a href=\"\">Lijst</a>";
					print "</td>";
				print "</tr>";
				print "<tr>";
					print "<td>";
						print "<b>Topscoorder uit rebound (aller tijden):</b>";
					print "</td>";
					print "<td>";
						$goal = new Goal();
						$topsc = $goal->getTopscoorders('Rebound');
						$top = $topsc[0];
						print $top[1]->toString()." (".$top[0].")";
					print "</td>";
					print "<td>";
						print "<a href=\"\">Lijst</a>";
					print "</td>";
				print "</tr>";
				print "<tr>";
					print "<td>";
						print "<b>Gescoord in meest aaneengesloten wedstrijden:</b>";
					print "</td>";
					print "<td>";
						$result = consGoals();
						print $result[1]->toString()." (".$result[0].")";
						//print_r($result[2]);
					print "</td>";
					print "<td>";
						print "<a href=\"\">Lijst</a>";
					print "</td>";
				print "</tr>";
			print "</table>";
		print "</div>";
	post();
?>