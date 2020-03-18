<?php
	require_once("db.class.php");
	
	class Verslag{
		
		private $id;
		private $db;
		private $table;
		
		function __construct($id = null){
			if(!is_null($id))
				$this->id = $id;
			$this->table = "verslagen";
			$this->db = new DB();
		}
		
		function getId(){
			return $this->id;
		}
		
		function getValues(){
			return $this->db->queryRow("SELECT * FROM ".$this->table." WHERE ".
					" id = '".$this->id."'");
		}
		
		function getVerslag($wedid){
			$verslag = $this->db->queryOne("SELECT verslag FROM ".$this->table." WHERE ".
				" wedid = '".$wedid."'");
			if(empty($verslag))
				return null;
			else
				return $verslag;
		}
        
        function setVerslag($verslag, $wedid){
             $fields['verslag'] = $verslag;
             $fields['wedid'] = $wedid;
             $this->db->insert($this->table, $fields);
        }
        
        function updateVerslag($verslag, $wedid){
            $fields['verslag'] = $verslag;
            $keys['wedid'] = $wedid;
            $this->db->update($this->table, $fields, $keys);
        }  
	}
?>