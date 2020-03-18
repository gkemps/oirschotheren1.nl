<?php
require_once("db.class.php");
require_once("positie.class.php");

class Systeem {
	
	private $id;
	private $db;
	
	function __construct($id){
		$this->id = $id;
		$this->db = new DB();
	}
	
	function getId(){
		return $this->id;
	}
	
	function toString(){
		print $this->db->queryOne("SELECT systeem FROM speelsystemen WHERE ".
					"id = '".$this->id."'");
	}
	
	function getSystem(){
		return $this->db->queryOne("SELECT systeem FROM speelsystemen WHERE ".
						"id = '".$this->id."'");
	}
	
	function getLines(){
		$lines = $this->db->queryOne("SELECT systeem FROM speelsystemen WHERE ".
					"id = '".$this->id."'");
		return explode("-", $lines);
	}
	
	function getPositions(){
		$positions = $this->db->queryMany("SELECT p.id FROM systeemposities AS s, posities as p ".
					"WHERE systeemid = '".$this->id."' AND s.posid = p.id  AND p.lijn < 7 ".
					"ORDER BY p.lijn ASC, p.id DESC");
		$result = array();
		foreach($positions as $pos){
			array_push($result, new Positie($pos));
		}
		return $result;
	}
}

?>