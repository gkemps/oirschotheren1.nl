<?php
require_once("db.class.php");
require_once("speler.class.php");   

class Vroegah{
 
	private $id;
	private $db;
	private $table;	
	
	function __construct($id = null){
		if(!is_null($id))
			$this->id = $id;
		$this->db = new DB();
		$this->table = "vroegah";
	}
	
	function getValues(){
		$result = $this->db->queryRow("SELECT * FROM ".$this->table." WHERE ".
				"id = '".$this->id."'");
		$result['spelerid'] = new Speler($result['spelerid']);     
        $result['seizoenid'] = new Seizoen($result['seizoenid']);  
		return $result;
	}
	
	function getId(){
		return $this->id;
	}
    
    function getSeason(){
        $id =  $this->db->queryOne("SELECT seizoenid FROM ".$this->table." WHERE id = '".$this->id."'");
        return new Seizoen($id);
    }
    
    function getGoals(){
        return $this->db->queryOne("SELECT goals FROM ".$this->table." WHERE id = '".$this->id."'");
    }
    
    function getAssists(){
        return $this->db->queryOne("SELECT assists FROM ".$this->table." WHERE id = '".$this->id."'");
    }
    
    function getCards(){
        return $this->db->queryOne("SELECT groen + geel + rood FROM ".$this->table." WHERE id = '".$this->id."'");
    }
    
    function getTotalGoals($spelerid){
        return $this->db->queryOne("SELECT SUM(goals) FROM ".$this->table." WHERE 
                    spelerid = '".$spelerid."' GROUP BY spelerid ");
    }
    
    function getTotalAssists($spelerid){
        return $this->db->queryOne("SELECT SUM(assists) FROM ".$this->table." WHERE 
                    spelerid = '".$spelerid."' GROUP BY spelerid ");
    }
    
    function getTotalCards($spelerid){
        return $this->db->queryOne("SELECT (SUM(groen) + SUM(geel) + SUM(rood)) FROM ".$this->table." WHERE 
                    spelerid = '".$spelerid."' GROUP BY spelerid ");
    }
    
    function getVroegah($spelerid){
        $results = $this->db->queryMany("SELECT v.id FROM ".$this->table." as v, seizoenen as s WHERE 
          v.seizoenid = s.id AND v.spelerid = '".$spelerid."' ORDER BY s.start DESC");
        $return = array();
        foreach($results as $id){
            $return[] = new Vroegah($id);
        }
        return $return;
    }
	
}


?>