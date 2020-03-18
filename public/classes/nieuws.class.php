<?php
	require_once("db.class.php");

class Nieuws{
	
	private $id;
	private $db;
	private $table;
	
	function __construct($id = null){
		if(!is_null($id))
			$this->id = $id;
		$this->db = new DB();
		$this->table = "nieuws";
	}
	
	function getId(){
		return $this->id;
	}
	
	function getValues(){
		return $this->db->queryRow("SELECT * FROM ".$this->table." WEHRE ".
				"id = '".$this->id."'");
	}
	
	function getLatestNews($nr){
		return $this->db->query("SELECT * FROM ".$this->table." ORDER BY datum ".
				"DESC LIMIT $nr");
	}
    
    function insert($datum, $titel, $inhoud){
        $fields['datum'] = $datum;
        $fields['titel'] = $titel;
        $fields['inhoud'] = $inhoud;
        $this->db->insert($this->table, $fields);
    }
}


?>