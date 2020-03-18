<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
	require_once("functions/functions.php");
	require_once("classes/db.class.php");
	require_once("classes/positie.class.php");
	require_once("classes/speler.class.php");
	require_once("classes/wedstrijd.class.php");
	require_once("classes/systeem.class.php");
	require_once("classes/goal.class.php");
	require_once("classes/kaart.class.php");
	
	if($_GET['c']==0){
		if(isset($_POST['posid'])){
			$pos = new Positie($_POST['posid']);
		}
		if(isset($_POST['playerid'])){
			$speler = new Speler($_POST['playerid']);
		}
		if(isset($_POST['wedid'])){
			$wedstrijd = new Wedstrijd($_POST['wedid']);
		}
		if(isset($_POST['sysid'])){
			$systeem = new Systeem($_POST['sysid']);
		}
		//speler aan opstelling toevoegen
		$opst = new Opstelling();
		$opst->insert($wedstrijd->getId(), $systeem->getId(), 
									$pos->getId(), $speler->getId());
		positie($pos, $wedstrijd);
	}
	elseif($_GET['c']==1){
		if(isset($_POST['posid'])){
			$pos = new Positie($_POST['posid']);
		}
		if(isset($_POST['playerid'])){
			$speler = new Speler($_POST['playerid']);
		}
		if(isset($_POST['wedid'])){
			$wedstrijd = new Wedstrijd($_POST['wedid']);
		}
		if(isset($_POST['sysid'])){
			$systeem = new Systeem($_POST['sysid']);
		}
		//speler aan opstelling toevoegen
		$opst = new Opstelling();
		$opst->setId($wedstrijd->getId(), $systeem->getId(), 
									$pos->getId(), $speler->getId());
		$opst->delete();
		positie($pos, $wedstrijd);	
	}
	elseif($_GET['c']==2){
		if(isset($_POST['posid'])){
			$pos = new Positie($_POST['posid']);
		}
		if(isset($_POST['wedid'])){
			$wedstrijd = new Wedstrijd($_POST['wedid']);
		}
		if(isset($_POST['sysid'])){
			$systeem = new Systeem($_POST['sysid']);
		}
		if(isset($_POST['helft'])){
			$helft = $_POST['helft'];
		}
		if(isset($_POST['aangever'])){
			if(is_numeric($_POST['aangever'])){
				$aangever = new Speler($_POST['aangever']);
				$aangever = $aangever->getId();
			}
			else
				$aangever = $_POST['aangever'];
		}
		
		$db = new DB();
		$playerid = $db->queryOne("SELECT spelerid FROM opstelling_nieuw WHERE ".
					" wedid = '".$wedstrijd->getId()."' AND ".
					"  sysid = '".$systeem->getId()."' AND ".
					" posid = '".$pos->getId()."'");
		$goal = new Goal();
		$goal->insert(2, $playerid, $aangever, $helft, $wedstrijd->getId());
		positie($pos, $wedstrijd);
	}
	elseif($_GET['c']==3){
		if(isset($_POST['wedid'])){
			$wedstrijd = new Wedstrijd($_POST['wedid']);
		}
		scores($wedstrijd);
	}
	elseif($_GET['c']==4){
		if(isset($_POST['posid'])){
			$pos = new Positie($_POST['posid']);
		}
		if(isset($_POST['wedid'])){
			$wedstrijd = new Wedstrijd($_POST['wedid']);
		}
		if(isset($_POST['kleur'])){
			$kleur = $_POST['kleur'];
		}
		if(isset($_POST['reden'])){
			$reden = $_POST['reden'];
		}
		$db = new DB();
		$playerid = $db->queryOne("SELECT spelerid FROM opstelling_nieuw WHERE ".
					" wedid = '".$wedstrijd->getId()."' AND ".
					" posid = '".$pos->getId()."'");
		$kaart = new Kaart();
		$kaart->insert($playerid, $kleur, $reden, $wedstrijd->getId());
		positie($pos, $wedstrijd);
	}
	elseif($_GET['c']==5){
	 	if(isset($_POST['aangever'])){
			$aangever = $_POST['aangever'];
		}
		if(isset($_POST['wedid'])){
			$wedstrijd = new Wedstrijd($_POST['wedid']);
		}
		$opstelling = new Opstelling();
		$players = $opstelling->getPlayers($wedstrijd->getId());
		$min = 100;
		foreach($players as $player){
			$diff = $player->isPlayer($aangever);
			if($diff < $min){
				$min = $diff;
				$result = $player;
			}
		}
		$goal = new Goal();
		$assists = $goal->getNonPlayerAssists();
		foreach($assists as $assist){
			$diff = diff($assist, $aangever);
			if($diff < $min){
				$min = $diff;
				$result = $assist;
			}
		}
		if(is_string($result)){
			print $result;
		}
		else{
			print $result->getId();
		}
	}
	elseif($_GET['c']==6){
	 	if(isset($_POST['reden'])){
			$reden = $_POST['reden'];
		}
		if(isset($_POST['wedid'])){
			$wedstrijd = new Wedstrijd($_POST['wedid']);
		}
		$kaart = new Kaart();
		$reden = $kaart->guessReason($reden);
		print $reden;
	}
	elseif($_GET['c']==7){
		if(isset($_POST['wedid'])){
			$wedstrijd = new Wedstrijd($_POST['wedid']);
		}
		if(isset($_POST['helft'])){
			$helft = $_POST['helft'];
		}
		if(isset($_POST['teamid'])){
			$team = new Team($_POST['teamid']);
		}
		if(isset($_POST['aangever'])){
			$aangever = strtolower(trim($_POST['aangever']));
			if(!empty($aangever)){
				if(levenshtein($aangever, "strafcorer")<levenshtein($aangever, "strafbal"))
					$aangever = "strafcorner";
				else
					$aangever = "strafbal";
			}
		}
		$goal = new Goal();
		$goal->insert($team->getId(), "", $aangever, $helft, $wedstrijd->getId());
		scores($wedstrijd);
	}
	
?>