<?php
require_once("db.class.php");

class Positie {
	
	private $id;
	private $db;
	private $table;
	
	function __construct($id = null){
		if(!is_null($id))
			$this->id = $id;
		$this->db = new DB();
		$this->table = "posities";
	}
	
	function toString(){
		return $this->db->queryOne("SELECT beschrijving FROM ".$this->table." WHERE ".
				"id = '".$this->id."'");
	}
	
	function getValues(){
		return $this->db->queryRow("SELECT * FROM ".$this->table." WHERE ".
				"id = '".$this->id."'");
	}
	
	function getId(){
		return $this->id;
	}
	
	function isBasis(){
		if($this->id<20){
			return true;
		}
		else
			return false;
	}
	
	function getBenchPositions(){
		$ids = $this->db->queryMany("SELECT id FROM ".$this->table." WHERE lijn = 7");
		$result = array();
		foreach($ids as $id){
			array_push($result, new Positie($id));
		}
		return $result;
	}
	
	function getAbsentPositions(){
		$ids = $this->db->queryMany("SELECT id FROM ".$this->table." WHERE lijn = 8");
		$result = array();
		foreach($ids as $id){
			array_push($result, new Positie($id));
		}
		return $result;
	}
	
	function isBench(){
	 	$values = $this->getValues();
		if($values['lijn']==7)
			return true;
		else
			return false;
	}
}

?>