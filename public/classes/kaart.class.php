<?php
require_once("db.class.php");

class Kaart{
	
	private $id;
	private $db;
	private $table;
	
	function __construct($id = null){
		if(!is_null($id))
			$this->id = $id;
		$this->db = new DB();
		$this->table = "kaarten";
	}
	
	function getId(){
		return $this->id;
	}
	
	function getValues(){
		return $this->db->queryRow("SELECT * FROM ".$this->table." WHERE ".
			"id = '".$this->id."'");
	}
	
	function getColor(){
		return $this->db->queryOne("SELECT kleur FROM ".$this->table." WHERE ".
			"id = '".$this->id."'");
	}
	
	function insert($spelerid, $kleur, $reden, $wedid){
		$fields['ontvanger'] = $spelerid;
		$fields['kleur'] = $kleur;
		$fields['reden'] = $reden;
		$fields['wedid'] = $wedid;
		$this->db->insert($this->table, $fields);
	}
	
	function getPlayerCards($spelerid, $wedid = null){
		if(is_null($wedid))
			$query = "SELECT id FROM ".$this->table." WHERE ontvanger = '".$spelerid."' ".
			" ORDER BY id ASC";
		else
			$query = "SELECT id FROM ".$this->table." WHERE ontvanger = '".$spelerid."' ".
			" AND wedid = '".$wedid."'";
		$ids = $this->db->queryMany($query);
		$result = array();
		foreach($ids as $id){
			array_push($result, new Kaart($id));
		}
		return $result;
	}
	
	function getMatchCards($wedid){
		$query = "SELECT id FROM ".$this->table." WHERE wedid = '".$wedid."' ".
			" ORDER BY id ASC";
		$ids = $this->db->queryMany($query);
		$result = array();
		foreach($ids as $id){
			array_push($result, new Kaart($id));
		}
		return $result;
	}
	
	function getSeasonCards($spelerid, $seizoenid, $kleur = null){
		$wedstrijd = new Wedstrijd();
		$wtable = $wedstrijd->getTable();
		$seizoen = new Seizoen($seizoenid);
		$bounds = $seizoen->getValues();
		if(is_null($kleur))
			$query = "SELECT k.id FROM ".$this->table." AS k, ".$wtable." as w WHERE ".
				" k.wedid = w.id AND w.datum > '".$bounds['start']."' AND ".
				" w.datum < '".$bounds['eind']."' AND k.ontvanger = '".$spelerid."'";
		else{
			$query = "SELECT k.id FROM ".$this->table." AS k, ".$wtable." as w WHERE ".
				" k.wedid = w.id AND w.datum > '".$bounds['start']."' AND ".
				" w.datum < '".$bounds['eind']."' AND k.ontvanger = '".$spelerid."' ".
				" AND k.kleur = '".$kleur."'";
		}
		$ids = $this->db->queryMany($query);
		$result = array();
		foreach($ids as $id){
			array_push($result, new Kaart($id));
		}
		return $result;
	}
	
	function guessReason($guess){
		$kandis = $this->db->queryMany("SELECT DISTINCT(reden) as reden FROM ".$this->table."");
		$min = 100;
		$guess = strtolower($guess);
		foreach($kandis as $kandi){
		 	$kandi = strtolower($kandi);
			$lsh1 = levenshtein($kandi, $guess);
			$lsh2 = levenshtein($guess, $kandi);
			$len = strlen($kandi) + strlen($guess);
			$diff = ($lsh1 + $lsh2 / $len);
			if($diff < $min){
				$min = $diff;
				$reden = $kandi;
			}
		} 	
		return $reden;
	}
	
	function delete(){
		$keys['id'] = $this->id;
		$this->db->delete($this->table, $keys);
	}
}