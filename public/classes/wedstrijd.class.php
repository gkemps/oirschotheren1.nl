<?php
require_once("db.class.php");
require_once("team.class.php");
require_once("goal.class.php");
require_once("verslag.class.php");

class Wedstrijd{
	
	private $id;
	private $db;
	private $table;
	
	function __construct($id = null){
		if(!is_null($id))
			$this->id = $id;
		$this->db = new DB();
		$this->table = "programma";
	}
	
	function getValues(){
		$result = $this->db->queryRow("SELECT * FROM ".$this->table." WHERE ".
					"id = '".$this->id."'");

		return $result;
	}
	
	function getId(){
		return $this->id;
	}
	
	function getTable(){
		return $this->table;
	}
	
	function getDate(){
		return $this->db->queryOne("SELECT Datum FROM ".$this->table." WHERE ".
					"id = '".$this->id."'");
	}
	
	function getRound(){
		return $this->db->queryOne("SELECT Speelronde FROM ".$this->table." WHERE ".
					"id = '".$this->id."'");
	}
	
	function homeTeam(){
		$id = $this->db->queryOne("SELECT Thuis FROM ".$this->table." WHERE ".
					"id = '".$this->id."'");
		return new Team($id);
	}
	
	function awayTeam(){
		$id = $this->db->queryOne("SELECT Uit FROM ".$this->table." WHERE ".
				"id = '".$this->id."'");
		return new Team($id);
	}
	
	function toString(){
		return $this->homeTeam()->toString()." - ".$this->awayTeam()->toString();
	}
	
	function OirschotMatch(){
		if($this->homeTeam()->getId()==2)
			return $this->awayTeam()->toString()." (thuis)";
		elseif($this->awayTeam()->getId()==2)
			return $this->homeTeam()->toString()." (uit)";
		else
			return $this->toString();
	}
	
	function getScores(){
		 return $this->db->queryRow("SELECT Scorethuis, Scoreuit FROM ".$this->table." ".
		 			"WHERE id = '".$this->id."'");
	}
	
	function setScores($thuis, $uit){
		$fields['Scorethuis'] = $thuis;
		$fields['Scoreuit'] = $uit;
		$fields['Gespeeld'] = "ja";
		$keys['id'] = $this->id;
		$this->db->update($this->table, $fields, $keys);
	}
	
	function getHomeGoals(){
		$goal = new Goal();
		return $goal->getGoals($this->id, $this->homeTeam()->getId());
	}
	
	function getAwayGoals(){
		$goal = new Goal();
		return $goal->getGoals($this->id, $this->awayTeam()->getId());
	}
	
	function getGoals(){
		$goal = new Goal();
		return $goal->getGoals($this->id);
	}
	
	function getSeason(){
		$id = $this->db->queryOne("SELECT id FROM seizoenen WHERE ".
				"start <= '".$this->getDate()."' AND eind > '".$this->getDate()."'");
		return new Seizoen($id);
	}
	
	function getFreePlayers(){
	 	$seizoen = $this->getSeason();
		$selected = $this->db->queryMany("SELECT DISTINCT(sp.id) FROM spelers as sp,".
					" opstelling_nieuw as o, ".
					" programma as p, deelnemingspelers as d, seizoenen as s ".
					"WHERE p.id = '".$this->id."' ".
					"AND o.wedid = p.id  AND o.spelerid = sp.id AND ".
					" d.spelersid = sp.id AND d.seizoenid = s.id AND ".
					"s.id = '".$seizoen->getId()."'");
		$ids = $this->db->queryMany("SELECT DISTINCT(sp.id) FROM spelers as sp,".
					" programma as p, deelnemingspelers as d, seizoenen as s ".
					"WHERE p.id = '".$this->id."' ".
					"AND d.spelersid = sp.id AND d.seizoenid = s.id AND ".
					"s.id = '".$seizoen->getId());
		$result = array();
		foreach($ids as $id){
		 	if(!in_array($id, $selected)){
				$speler = new Speler($id);
				array_push($result, $speler);
			}
		}
		return $result;
	}
	
	function getMatches($start, $end){
		$ids = $this->db->queryMany("SELECT id FROM ".$this->table." WHERE ".
				"datum >= '".$start."' AND datum <= '".$end."' AND speelronde <> 'Beker ronde 1' ORDER BY datum ASC");
		$result = array();
		foreach($ids as $id){
			array_push($result, new Wedstrijd($id));
		}
		return $result;
	}
	
	function getLastPlayed($teamid, $nr, $peildatum = null){
        if(is_null($peildatum))
		    $ids = $this->db->queryMany("SELECT id FROM ".$this->table." WHERE ".
				" (Thuis = '".$teamid."' OR Uit = '".$teamid."') AND Gespeeld = \"ja\" AND Scorethuis IS NOT NULL ".
				" ORDER BY Datum DESC LIMIT $nr");
        else
            $ids = $this->db->queryMany("SELECT id FROM ".$this->table." WHERE ".
                " (Thuis = '".$teamid."' OR Uit = '".$teamid."') AND Gespeeld = \"ja\" AND Scorethuis IS NOT NULL ".
                " AND Datum < '".$peildatum."' ORDER BY Datum DESC LIMIT $nr");   
		$result = array();
		foreach($ids as $id){
			array_push($result, new Wedstrijd($id));
		}
		return $result;
	}
	
	function getNextPlayed($teamid, $nr){
		$ids = $this->db->queryMany("SELECT id FROM ".$this->table." WHERE ".
				" (Thuis = '".$teamid."' OR Uit = '".$teamid."') AND Gespeeld = \"nee\" AND Scorethuis IS NULL ".
				" ORDER BY Datum ASC LIMIT $nr");
		$result = array();
		foreach($ids as $id){
			array_push($result, new Wedstrijd($id));
		}
		return $result;
	}
	
	function getTeamMatches($teamid, $seizoenid){
		if(is_null($seizoenid))
			$query = "SELECT id FROM ".$this->table." WHERE (Thuis = '".$teamid."' ".
				" OR Uit = '".$teamid."' ORDER BY datum ASC)";
		else{
			$seizoen = new Seizoen($seizoenid);
			$bounds = $seizoen->getValues();
			$query = "SELECT id FROM ".$this->table." WHERE (Thuis = '".$teamid."' ".
				" OR Uit = '".$teamid."') AND Datum > '".$bounds['start']."' AND ".
				" Datum < '".$bounds['eind']."' ORDER BY datum ASC";
		}
		$ids = $this->db->queryMany($query);
		$result = array();
		foreach($ids as $id){
			array_push($result, new Wedstrijd($id));
		}
		return $result;
	}
	
	function getResult($teamid){
		$values = $this->getValues();
		if($values['Thuis']==$teamid){
			if($values['Scorethuis']>$values['Scoreuit'])
				return "W";
			elseif($values['Scorethuis']<$values['Scoreuit'])
				return "V";
			else
				return "G";
		}
		if($values['Uit']==$teamid){
			if($values['Scorethuis']>$values['Scoreuit'])
				return "V";
			elseif($values['Scorethuis']<$values['Scoreuit'])
				return "W";
			else
				return "G";
		}
	}
	
	function getTeamForm($teamid, $peildatum = null){
        if(is_null($peildatum))
            $peildatum =  date("Y-m-d"); 
	 	$seizoen = new Seizoen();
	 	$seizoen->setSeason($peildatum);
	 	$bounds = $seizoen->getValues();
		$matches = $this->getLastPlayed($teamid, 5, $peildatum);
		$result = "";       
		foreach($matches as $match){                       
		 	if($match->getDate()>$bounds['start'])       
                $result = $match->getResult($teamid).$result; 
		}
		return $result;
	}
	
	function played(){
		$values = $this->getValues();
                if(strlen($values['Scorethuis'])==0)
			return false;
		else
			return true;
	}
    
    function returnMatch(){
        $thuis = $this->homeTeam()->getId();
        $uit = $this->awayTeam()->getId();
        $seizoen = $this->getSeason();
        $bounds = $seizoen->getValues();
        $start = $bounds['start'];
        $eind = $bounds['eind'];
        $id = $this->db->queryOne("SELECT id FROM ".$this->table." WHERE thuis = '".$uit."' AND 
                uit = '".$thuis."' AND datum < '".$eind."' AND datum > '".$start."' ORDER BY datum DESC LIMIT 1");
        return new Wedstrijd($id); 
    }
	
	function lastResult($teamid, $toto, $peildatum = null){
        if(is_null($peildatum))
            $peildatum = date("Y-m-d");
		if($toto==1){
			$homewin = $this->db->queryRow("SELECT id, Datum FROM ".$this->table." WHERE ".
				"Thuis = '".$teamid."' AND Gespeeld = 'ja' AND Scorethuis > Scoreuit ".
				" AND Datum < '".$peildatum."' ORDER BY Datum DESC LIMIT 1");
			$awaywin = $this->db->queryRow("SELECT id, Datum FROM ".$this->table." WHERE ".
				"Uit = '".$teamid."' AND Gespeeld = 'ja' AND Scorethuis < Scoreuit ".
				"  AND Datum < '".$peildatum."' ORDER BY Datum DESC LIMIT 1");
			if($homewin['Datum']>$awaywin['Datum'])
				return new Wedstrijd($homewin['id']);
			else
				return new Wedstrijd($awaywin['id']);
		}
		elseif($toto==2){
			$homeloss = $this->db->queryRow("SELECT id, Datum FROM ".$this->table." WHERE ".
				"Thuis = '".$teamid."' AND Gespeeld = 'ja' AND Scorethuis < Scoreuit ".
				"  AND Datum < '".$peildatum."' ORDER BY Datum DESC LIMIT 1");
			$awayloss = $this->db->queryRow("SELECT id, Datum FROM ".$this->table." WHERE ".
				"Uit = '".$teamid."' AND Gespeeld = 'ja' AND Scorethuis > Scoreuit ".
				"  AND Datum < '".$peildatum."' ORDER BY Datum DESC LIMIT 1");
			if($homeloss['Datum']>$awayloss['Datum'])
				return new Wedstrijd($homeloss['id']);
			else
				return new Wedstrijd($awayloss['id']);
		}
		elseif($toto==3){
			$homedraw = $this->db->queryRow("SELECT id, Datum FROM ".$this->table." WHERE ".
				"Thuis = '".$teamid."' AND Gespeeld = 'ja' AND Scorethuis = Scoreuit ".
				"  AND Datum < '".$peildatum."' ORDER BY Datum DESC LIMIT 1");
			$awaydraw = $this->db->queryRow("SELECT id, Datum FROM ".$this->table." WHERE ".
				"Uit = '".$teamid."' AND Gespeeld = 'ja' AND Scorethuis = Scoreuit ".
				"  AND Datum < '".$peildatum."' ORDER BY Datum DESC LIMIT 1");
			if($homedraw['Datum']>$awaydraw['Datum'])
				return new Wedstrijd($homedraw['id']);
			else
				return new Wedstrijd($awaydraw['id']);
		}
	}
    
    function getLastHomeWin($teamid, $peildatum = null){
          if(is_null($peildatum))
            $peildatum = date("Y-m-d");
          $homewin = $this->db->queryOne("SELECT id FROM ".$this->table." WHERE ".
                "Thuis = '".$teamid."' AND Gespeeld = 'ja' AND Scorethuis > Scoreuit ".
                " AND Datum < '".$peildatum."' ORDER BY Datum DESC LIMIT 1");
          if(is_null($homewin))
            return null;
          else
            return new Wedstrijd($homewin);
    }
    
    function getLastHomeLoss($teamid, $peildatum = null){
          if(is_null($peildatum))
            $peildatum = date("Y-m-d");
          $homeloss = $this->db->queryOne("SELECT id FROM ".$this->table." WHERE ".
                "Thuis = '".$teamid."' AND Gespeeld = 'ja' AND Scorethuis < Scoreuit ".
                " AND Datum < '".$peildatum."' ORDER BY Datum DESC LIMIT 1");
          if(is_null($homeloss))
            return null;
          else
            return new Wedstrijd($homeloss);
    }
    
    function getLastAwayWin($teamid, $peildatum = null){
          if(is_null($peildatum))
            $peildatum = date("Y-m-d");
          $awaywin = $this->db->queryOne("SELECT id FROM ".$this->table." WHERE ".
                "Uit = '".$teamid."' AND Gespeeld = 'ja' AND Scorethuis < Scoreuit ".
                " AND Datum < '".$peildatum."' ORDER BY Datum DESC LIMIT 1");
          if(is_null($awaywin))
            return null;
          else
            return new Wedstrijd($awaywin);
    }
    
    function getLastAwayLoss($teamid, $peildatum = null){
          if(is_null($peildatum))
            $peildatum = date("Y-m-d");
          $awayloss = $this->db->queryOne("SELECT id FROM ".$this->table." WHERE ".
                "Uit = '".$teamid."' AND Gespeeld = 'ja' AND Scorethuis > Scoreuit ".
                " AND Datum < '".$peildatum."' ORDER BY Datum DESC LIMIT 1");
          if(is_null($awayloss))
            return null;
          else
            return new Wedstrijd($awayloss);
    }
	
	function nrOfResults($teamid, $result){
		if($result==1){
			$home = $this->db->queryOne("SELECT COUNT(id) FROM ".$this->table." ".
				" WHERE Thuis = '".$teamid."' AND Scorethuis > Scoreuit AND gespeeld = 'ja'");
			$away = $this->db->queryOne("SELECT COUNT(id) FROM ".$this->table." ".
				" WHERE Uit = '".$teamid."' AND Scorethuis < Scoreuit AND gespeeld = 'ja'");	
		}
		elseif($result==2){
			$home = $this->db->queryOne("SELECT COUNT(id) FROM ".$this->table." ".
				" WHERE Thuis = '".$teamid."' AND Scorethuis < Scoreuit AND gespeeld = 'ja'");
			$away = $this->db->queryOne("SELECT COUNT(id) FROM ".$this->table." ".
				" WHERE Uit = '".$teamid."' AND Scorethuis > Scoreuit AND gespeeld = 'ja'");	
		}
		elseif($result==3){
			$home = $this->db->queryOne("SELECT COUNT(id) FROM ".$this->table." ".
				" WHERE Thuis = '".$teamid."' AND Scorethuis = Scoreuit AND gespeeld = 'ja'");
			$away = $this->db->queryOne("SELECT COUNT(id) FROM ".$this->table." ".
				" WHERE Uit = '".$teamid."' AND Scorethuis = Scoreuit AND gespeeld = 'ja'");	
		}
		return $home + $away;
	}
	
	function OirschotInMatch(){
		if($this->homeTeam()->getId()==2 or $this->awayTeam()->getId()==2)
			return true;
		else
			return false;
	}
	
	function getSystem(){
		if($this->OirschotInMatch()){
			$opstelling = new Opstelling();
			return $opstelling->getSystem($this->id);
		}
	}
	
	function getVerslag(){
		$verslag = new Verslag();
		return $verslag->getVerslag($this->id);
	}
	
	function insert($datum, $thuis, $uit, $speelronde){
		$fields['Datum'] = $datum;
		$fields['Thuis'] = $thuis;
		$fields['Uit'] = $uit;
		$fields['Speelronde'] = $speelronde;
		$this->db->insert($this->table, $fields);
	}

	function getRoundMatches($start, $eind, $ronde){
		$ids = $this->db->queryMany("SELECT id FROM ".$this->table." WHERE Datum >= '".$start."' AND ".
			" Datum <= '".$eind."' AND Speelronde = '".$ronde."'");
		$result = array();
		foreach($ids as $id){
			array_push($result, new Wedstrijd($id));
		}
		return $result;
	}
	
	function head2head(){
        $values = $this->getValues();  
        $oldid = $this->id;
		$ids = $this->db->queryMany("SELECT id FROM ".$this->table." WHERE Thuis = '".$values['Thuis']."' AND 
						Uit = '".$values['Uit']."' AND gespeeld = 'ja'");
		$thuis = array("gw" => 0, "gl" => 0, "vl" => 0, "voor" => 0, "tegen" => 0, 
                        "sc" => 0, "sb"=>0, "1e" => 0, "2e" => 0);
		$uit = array("gw" => 0, "gl" => 0, "vl" => 0,  "voor" => 0, "tegen" => 0, 
                        "sc" => 0, "sb"=>0, "1e" => 0, "2e" => 0);
		foreach($ids as $id){
            $this->id = $id;
            $goals = $this->getGoals();
            foreach($goals as $goal){
                $gvalues = $goal->getValues();    
                if($values['Thuis']==$gvalues['Team']->getId()){ 
                   $thuis['voor']++;
                   $uit['tegen']++;
                   if(strtolower($gvalues['Aangever'])=="strafcorner")
                       $thuis['sc']++;
                   if(strtolower($gvalues['Aangever'])=="strafbal")
                       $thuis['sb']++;
                   if($gvalues['Helft']==1)
                        $thuis['1e']++;
                   else
                        $thuis['2e']++;
                }
                elseif($values['Uit']==$gvalues['Team']->getId()){          
                    $uit['voor']++;
                    $thuis['tegen']++;
                    if(strtolower($gvalues['Aangever'])=="strafcorner")
                       $uit['sc']++;
                    if(strtolower($gvalues['Aangever'])=="strafbal")
                       $uit['sb']++;
                    if($gvalues['Helft']==1)
                        $uit['1e']++;
                    else
                        $uit['2e']++;
                }         
            }
            
            
			$svalues = $this->getScores();
			if($svalues['Scorethuis'] > $svalues['Scoreuit']){
				$thuis['gw']++;
				$uit['vl']++;
			}
			elseif($svalues['Scorethuis'] < $svalues['Scoreuit']){
				$uit['gw']++;
				$thuis['vl']++;
			}
			else{
				$thuis['gl']++;
				$uit['gl']++;
			}
		}
		$result = array($thuis, $uit);
        $this->id = $oldid;
		return $result;
	}
    
    function printScore(){
        $scores = $this->getScores();
        return $scores['Scorethuis']." - ".$scores['Scoreuit'];
    }
}

?>
