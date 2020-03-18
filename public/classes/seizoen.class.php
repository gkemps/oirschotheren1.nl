<?php
require_once("db.class.php");
require_once("speler.class.php");
require_once("dteams.class.php");
require_once("dspelers.class.php");
require_once("wedstrijd.class.php");

class Seizoen {
	
	private $id;
	private $db;
	private $table;
	
	function __construct($id = null){
		if(!is_null($id))
			$this->id = $id;
		$this->db = new DB();
		$this->table = "seizoenen";
	}
	
	function getId(){
		return $this->id;
	}
	
	function getValues(){
		return $this->db->queryRow("SELECT * FROM ".$this->table." WHERE ".
		"id = '".$this->id."'");
	}
	
	function setSeason($date){
	 	$query = "SELECT id FROM ".$this->table." WHERE start <= '".$date."' ".
						" AND eind >= '".$date."'";
		$id = $this->db->queryOne($query);
		if(is_numeric($id)){
			$this->id = $id;
		}
	}
	
	function yearToSeasons($year){
		$ids = $this->db->queryMany("SELECT id FROM ".$this->table." WHERE start LIKE '%$year%' OR
					eind LIKE '%$year%'");
		$result = array();
		foreach($ids as $id){
			array_push($result, new Seizoen($id));
		}
		return $result;
	}
	
	function toString(){
		$values = $this->getValues();
		return substr($values['start'], 0,4)."/".substr($values['eind'],0,4);
	}
	
	function getTeams(){
		$dteams = new DTeams();
		return $dteams->getTeams($this->id);
	}
	
	function getPlayers(){
		$dspelers = new DSpelers();
		return $dspelers->getPlayers($this->id);
	}
	
	function getRoundMatches($roundnr){
		$values = $this->getValues();
		$wedstrijd = new Wedstrijd();
		return $wedstrijd->getRoundMatches($values['start'], $values['eind'], $roundnr);
	}
	
	function getSeasons(){
		$ids = $this->db->queryMany("SELECT id FROM ".$this->table."");
		$result = array();
		foreach($ids as $id){
			array_push($result, new Seizoen($id));
		}
		return $result;
	}
}


?>