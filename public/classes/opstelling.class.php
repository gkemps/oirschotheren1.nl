<?php
require_once("db.class.php");
require_once("seizoen.class.php");
require_once("wedstrijd.class.php");
require_once("positie.class.php");
require_once("systeem.class.php");
require_once("speler.class.php");

class Opstelling{
	
	private $id;
	private $db;
	private $table;
	
	function __construct($id = null){
		if (!is_null($id))
			$this->id = $id;
		$this->db = new DB();
		$this->table = "opstelling_nieuw";
	}
	
	function getValues(){
		return $this->db->queryRow("SELECT * FROM ".$this->table." WHERE ".
			"id = '".$this->id."'");
	}
	
	function getPos($spelerid, $wedid){
		$posid = $this->db->queryOne("SELECT posid FROM ".$this->table." WHERE ".
			" spelerid = '".$spelerid."' AND wedid = '".$wedid."'");
		return new Positie($posid);
	}
	
	function setId($wedid, $sysid, $posid, $spelerid){
		$this->id = $this->db->queryOne("SELECT id FROM ".$this->table." WHERE ".
				"wedid = '".$wedid."' AND sysid = '".$sysid."' AND ".
				" posid = '".$posid."' AND spelerid = '".$spelerid."'");
	}
	
	function delete(){
		$keys['id'] = $this->id;
		$this->db->delete($this->table, $keys);
	}
	
	function getPlayer($wedid, $posid){
		$id = $this->db->queryOne("SELECT spelerid FROM ".$this->table." WHERE ".
				"wedid = '".$wedid."' AND posid = '".$posid."'");
		if(!empty($id)){
			return new Speler($id);
		}
		else
			return null;
	}
	
	function insert($wedid, $sysid, $posid, $spelerid){
		$fields['wedid'] = $wedid;
		$fields['sysid'] = $sysid;
		$fields['posid'] = $posid;
		$fields['spelerid'] = $spelerid;
		$this->db->insert($this->table, $fields);
	}
	
	function getPlayers($wedid){
		$ids = $this->db->queryMany("SELECT spelerid FROM ".$this->table." WHERE ".
					" wedid = '".$wedid."'");
		$result = array();
		foreach($ids as $id){
			array_push($result, new Speler($id));
		}
		return $result;
	}
	
	function getSeasonMatches($spelerid, $seizoenid = null){
		$seizoen = new Seizoen($seizoenid);
		$bounds = $seizoen->getValues();
		$wedstrijd = new Wedstrijd();
		$wtable = $wedstrijd->getTable();
		if(is_null($seizoenid))
			$query = "SELECT w.id FROM ".$this->table." AS o, ".$wtable." AS w WHERE ".
			" o.wedid = w.id AND o.spelerid = '".$spelerid."' ".
			" AND (o.posid < 25 OR o.posid > 30) ORDER BY w.Datum ASC";
		else
			$query = "SELECT w.id FROM ".$this->table." AS o, ".$wtable." AS w WHERE ".
			" o.wedid = w.id AND w.Datum > '".$bounds['start']."' AND ".
			" w.Datum < '".$bounds['eind']."' AND o.spelerid = '".$spelerid."' ".
			" AND (o.posid < 25 OR o.posid > 30) ORDER BY w.Datum ASC";
		$ids = $this->db->queryMany($query);
		$result = array();
		foreach($ids as $id){
			array_push($result, new Wedstrijd($id));
		}
		return $result;
	}
	
	function getSeasonBenchMatches($spelerid, $seizoenid = null){
		$seizoen = new Seizoen($seizoenid);
		$bounds = $seizoen->getValues();
		$wedstrijd = new Wedstrijd();
		$wtable = $wedstrijd->getTable();
		if(is_null($seizoenid))
			$query = "SELECT w.id FROM ".$this->table." AS o, ".$wtable." AS w WHERE ".
			" o.wedid = w.id AND o.spelerid = '".$spelerid."' ".
			" AND o.posid < 25 AND o.posid > 19";
		else
			$query = "SELECT w.id FROM ".$this->table." AS o, ".$wtable." AS w WHERE ".
			" o.wedid = w.id AND w.Datum > '".$bounds['start']."' AND ".
			" w.Datum < '".$bounds['eind']."' AND o.spelerid = '".$spelerid."' ".
			" AND o.posid < 25 AND o.posid > 19";
		$ids = $this->db->queryMany($query);
		$result = array();
		foreach($ids as $id){
			array_push($result, new Wedstrijd($id));
		}
		return $result;
	}
	
	function getSeasonTopApps($seizoenid){
		$seizoen = new Seizoen($seizoenid);
		$bounds = $seizoen->getValues();
		$wedstrijd = new Wedstrijd();
		$wtable = $wedstrijd->getTable();
		$query = "SELECT COUNT(o.id) as sum, o.spelerid AS Speler FROM ".$this->table." AS o, ".
			$wtable." AS w WHERE ".
			" o.wedid = w.id AND w.Datum > '".$bounds['start']."' AND ".
			" w.Datum < '".$bounds['eind']."' AND (o.posid < 25 OR o.posid > 30) GROUP BY o.spelerid";
		$results = $this->db->query($query);
		$return = array();
		foreach($results as $result){
			array_push($return, array($result['sum'], new Speler($result['Speler'])));	
		}
		return $return;
	}
	
	function getSeasonTopBenchApps($seizoenid){
		$seizoen = new Seizoen($seizoenid);
		$bounds = $seizoen->getValues();
		$wedstrijd = new Wedstrijd();
		$wtable = $wedstrijd->getTable();
		$query = "SELECT COUNT(o.id) as sum, o.spelerid AS Speler FROM ".$this->table." AS o, ".
			$wtable." AS w WHERE ".
			" o.wedid = w.id AND w.Datum > '".$bounds['start']."' AND ".
			" w.Datum < '".$bounds['eind']."' AND o.posid < 25 AND o.posid > 19 GROUP BY o.spelerid";
		$results = $this->db->query($query);
		$return = array();
		foreach($results as $result){
			array_push($return, array($result['sum'], new Speler($result['Speler'])));	
		}
		return $return;
	}
	
	function getLastPos($spelerid){
		$id = $this->db->queryOne("SELECT id FROM ".$this->table." WHERE ".
			" spelerid = '".$spelerid."' AND posid < 20 ORDER BY id DESC LIMIT 1");
		return new Opstelling($id);
	}
	
	function getSystem($wedid){
		$id = $this->db->queryOne("SELECT sysid FROM ".$this->table." WHERE ".
			" wedid = '".$wedid."'");
		if(empty($id))
			return null;
		else
			return new Systeem($id);
	}
	
	function getBenchPlayers($wedid){
		$ids = $this->db->queryMany("SELECT spelerid FROM ".$this->table." WHERE ".
			" wedid = '".$wedid."' AND posid = 20 ");
		$result = array();
		foreach($ids as $id){
			array_push($result, new Speler($id));
		}
		return $result;
	}
}


?>