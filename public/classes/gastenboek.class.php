<?php
	require_once("db.class.php");

class Gastenboek{
	
	private $db;
	private $id;
	private $table;
	
	function __construct($id = null){
		if(!is_null($id))
			$this->id = $id;
		$this->db = new DB();
		$this->table = "gastenboek";
	}
	
	function getId(){
		return $this->id;
	}
	
	function getValues(){
		return $this->db->queryRow("SELECT * FROM ".$this->table." WHERE ".
		"id = '".$this->id."'");
	}
	
	function getEntries($limit, $start){
		$ids = $this->db->queryMany("SELECT id FROM ".$this->table." ORDER BY id DESC ".
			"LIMIT $start, $limit");
		$result = array();
		foreach($ids as $id){
			array_push($result, new Gastenboek($id));
		}
		return $result;
	}
	
	function getNrOfPosts(){
		$ids = $this->db->queryMany("SELECT id FROM ".$this->table."");
		return count($ids);
	}
	
	function getNrOfUserPosts($naam){
		$ids = $this->db->queryMany("SELECT id FROM ".$this->table." WHERE ".
				"naam LIKE '%".$naam."' AND id <= '".$this->id."'");
		return count($ids);
	}
	
	function getPostNr(){
		$ids = $this->db->queryMany("SELECT id FROM ".$this->table." WHERE ".
				"id <= '".$this->id."'");
		return count($ids);
	}
	
	function insert($naam, $bericht){
		$fields['naam'] = $naam;
		$fields['bericht'] = $bericht;
		$fields['datum'] = 'NOW()';
		$this->db->insert($this->table, $fields);
	}
	
	function deleteLast(){
      $this->db->query("DELETE FROM ".$this->table." ORDER BY id DESC LIMIT 1");
   }
}
	