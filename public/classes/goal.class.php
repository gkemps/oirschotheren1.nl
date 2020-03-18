<?php
require_once("db.class.php");
require_once("speler.class.php");
require_once("team.class.php");
require_once("wedstrijd.class.php");
require_once("seizoen.class.php");

class Goal{
 
	private $id;
	private $db;
	private $table;	
	
	function __construct($id = null){
		if(!is_null($id))
			$this->id = $id;
		$this->db = new DB();
		$this->table = "goals";
	}
	
	function getValues(){
		$result = $this->db->queryRow("SELECT * FROM ".$this->table." WHERE ".
				"id = '".$this->id."'");
		$result['Team'] = new Team($result['Team']);
		if(is_numeric($result['Maker']))
			$result['Maker'] = new Speler($result['Maker']);
		if (is_numeric($result['Aangever']))
			$result['Aangever'] = new Speler($result['Aangever']);
		return $result;
	}
	
	function getId(){
		return $this->id;
	}
	
	function nextGoal($teamid, $wedid){
		$volgorde = $this->db->queryOne("SELECT Volgorde FROM ".$this->table." WHERE ".
				"wedid = '".$wedid."' ".
				"ORDER BY LENGTH(Volgorde) DESC, Volgorde DESC LIMIT 1");
		$wedstrijd = new Wedstrijd($wedid);
		if($wedstrijd->homeTeam()->getId()==$teamid){
			if(empty($volgorde)){
				return "1-0";
			}
			else{
				$stripe = strpos($volgorde, "-");
				$result = strval(intval(substr($volgorde, 0, $stripe))+1);
				$result .= substr($volgorde, $stripe, strlen($volgorde));
				return $result;
			}
		}
		else{
			if(empty($volgorde)){
				return "0-1";
			}
			else{
				$stripe = strpos($volgorde, "-");
				$result = substr($volgorde, 0, $stripe+1);
				$result .= strval(intval(substr($volgorde, $stripe+1, strlen($volgorde)))+1);
				return $result;
			}
		}
	}

	function insert($team, $maker, $aangever, $helft, $wedid){
		$fields['Team'] = $team;
		$fields['Maker'] = $maker;
		$fields['Aangever'] = $aangever;
		$fields['Volgorde'] = $this->nextGoal($team, $wedid, $helft);
		$fields['Helft'] = $helft;
		$fields['wedid'] = $wedid;
		$this->db->insert($this->table, $fields);
	}
	
	function getNonPlayerAssists(){
		return $this->db->queryMany("SELECT Distinct(Aangever) FROM ".$this->table." ".
			" WHERE Aangever <> concat( '', 0 + Aangever ) AND Aangever <> '' ");
	}
	
	function getGoals($wedid, $teamid = null){
		if(is_null($teamid))
			$ids = $this->db->queryMany("SELECT id FROM ".$this->table." WHERE ".
				"wedid = '".$wedid."' ORDER BY volgorde");
		else
			$ids = $this->db->queryMany("SELECT id FROM ".$this->table." WHERE ".
				"wedid = '".$wedid."' AND Team = '".$teamid."'");
		$result = array();
		foreach($ids as $id){
			array_push($result, new Goal($id));
		}
		return $result;
	}
	
	function getPlayerGoals($spelerid, $wedid = null){
		if(is_null($wedid))
			$ids = $this->db->queryMany("SELECT id FROM ".$this->table." WHERE ".
				"Maker = '".$spelerid."' ORDER BY id ASC");
		else
			$ids = $this->db->queryMany("SELECT id FROM ".$this->table." WHERE ".
				"wedid = '".$wedid."' AND Maker = '".$spelerid."' ORDER BY id ASC");
		$result = array();
		foreach($ids as $id){
			array_push($result, new Goal($id));
		}
		return $result;
	}
	
	function getPlayerAssists($spelerid, $wedid = null){
		if(is_null($wedid))
			$ids = $this->db->queryMany("SELECT id FROM ".$this->table." WHERE ".
				"Aangever = '".$spelerid."' ORDER BY id ASC");
		else
			$ids = $this->db->queryMany("SELECT id FROM ".$this->table." WHERE ".
				"wedid = '".$wedid."' AND Aangever = '".$spelerid."' ORDER BY id ASC");
		$result = array();
		foreach($ids as $id){
			array_push($result, new Goal($id));
		}
		return $result;
	}
	
	function getSeasonGoals($spelerid, $seizoenid){
		$wedstrijd = new Wedstrijd();
		$wtable = $wedstrijd->getTable();
		$seizoen = new Seizoen($seizoenid);
		$bounds = $seizoen->getValues();
		$query = "SELECT g.id FROM ".$this->table." AS g, ".$wtable." as w WHERE ".
				" g.wedid = w.id AND w.datum > '".$bounds['start']."' AND ".
				" w.datum < '".$bounds['eind']."' AND g.Maker = '".$spelerid."'";
		$ids = $this->db->queryMany($query);
		$result = array();
		foreach($ids as $id){
			array_push($result, new Goal($id));
		}
		return $result;
	}
	
	function getSeasonAssists($spelerid, $seizoenid){
		$wedstrijd = new Wedstrijd();
		$wtable = $wedstrijd->getTable();
		$seizoen = new Seizoen($seizoenid);
		$bounds = $seizoen->getValues();
		$query = "SELECT g.id FROM ".$this->table." AS g, ".$wtable." as w WHERE ".
				" g.wedid = w.id AND w.datum > '".$bounds['start']."' AND ".
				" w.datum < '".$bounds['eind']."' AND g.Aangever = '".$spelerid."'";
		$ids = $this->db->queryMany($query);
		$result = array();
		foreach($ids as $id){
			array_push($result, new Goal($id));
		}
		return $result;
	}
	
	function getSeasonTopscoorders($seizoenid, $aangever = null){
		$wedstrijd = new Wedstrijd();
		$wtable = $wedstrijd->getTable();
		$speler = new Speler();
		$stable = $speler->getTable();
		$seizoen = new Seizoen($seizoenid);
		$bounds = $seizoen->getValues();
		if(is_null($aangever))
			$query = "SELECT COUNT(g.id) AS sum, g.Maker as Maker FROM ".$this->table." AS g, ".$wtable." as w, ".
				" ".$stable." as s WHERE g.wedid = w.id AND s.id = g.Maker AND w.datum > '".$bounds['start']."' AND ".
				" w.datum < '".$bounds['eind']."' AND g.Team = 2 GROUP BY g.Maker ORDER BY sum DESC";
		else
			$query = "SELECT COUNT(g.id) AS sum, g.Maker as Maker FROM ".$this->table." AS g, ".$wtable." as w, ".
				" ".$stable." as s WHERE g.wedid = w.id AND s.id = g.Maker AND w.datum > '".$bounds['start']."' AND ".
				" w.datum < '".$bounds['eind']."' AND g.Team = 2 AND g.Aangever = '".$aangever."' GROUP BY g.Maker ".
				"ORDER BY sum DESC";
		$results = $this->db->query($query);
		$return = array();
		foreach($results as $result){
			array_push($return, array($result['sum'], new Speler($result['Maker'])));
		}
		return $return;
	}
	
	function getTopScoorders($aangever = null){
		if(is_null($aangever))
			$results = $this->db->query("SELECT COUNT(id) AS sum, Maker FROM ".$this->table." GROUP BY Maker ".
					"ORDER BY sum DESC");
		else
			$results = $this->db->query("SELECT COUNT(id) AS sum, Maker FROM ".$this->table." WHERE ".
				"Aangever = '".$aangever."'GROUP BY Maker ORDER BY sum DESC");
		$return = array();
		foreach($results as $result){
		 	if(is_numeric($result['Maker']))
				array_push($return, array($result['sum'], new Speler($result['Maker'])));
		}
		return $return;
	}

	
	function getSeasonTopassists($seizoenid){
		$wedstrijd = new Wedstrijd();
		$wtable = $wedstrijd->getTable();
		$speler = new Speler();
		$stable = $speler->getTable();
		$seizoen = new Seizoen($seizoenid);
		$bounds = $seizoen->getValues();
		$query = "SELECT COUNT(g.id) AS sum, g.Aangever as Aangever FROM ".$this->table." AS g, ".
				$wtable." as w, ".$stable." AS s WHERE  g.wedid = w.id AND s.id = g.Aangever AND ".
				"w.datum > '".$bounds['start']."' AND w.datum < '".$bounds['eind']."' AND g.Team = 2 ".
				"GROUP BY g.Aangever ORDER BY sum DESC";
		$results = $this->db->query($query);
		$return = array();
		foreach($results as $result){
			array_push($return, array($result['sum'], new Speler($result['Aangever'])));
		}
		return $return;
	}
	
	function delete(){
		$keys['id'] = $this->id;
		$this->db->delete($this->table, $keys);
	}
	
	function playerScored($spelerid, $wedid){
		$id = $this->db->queryOne("SELECT id FROM ".$this->table." WHERE Maker = '".$spelerid."' AND wedid = '".$wedid."'");
		if(empty($id))
			return false;
		else
			return true;
	}
	
	function getAllGoals($seizoenid = null){
		if(is_null($seizoenid)){
			$query = "SELECT g.id FROM ".$this->table." AS g";
		}
		else{
			$wedstrijd = new Wedstrijd();
			$wtable = $wedstrijd->getTable();
			$seizoen = new Seizoen($seizoenid);
			$bounds = $seizoen->getValues();
			$query = "SELECT g.id FROM ".$this->table." AS g, ".$wtable." as w WHERE ".
					" g.wedid = w.id AND w.datum > '".$bounds['start']."' AND ".
					" w.datum < '".$bounds['eind']."'";
		}
		$ids = $this->db->queryMany($query);
		$result = array();
		foreach($ids as $id){
			array_push($result, new Goal($id));
		}
		return $result;
	}
}


?>