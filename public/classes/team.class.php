<?php
require_once("db.class.php");

class Team {
	
	private $id;
	private $db;
	private $table;
	
	function __construct($id = null){
		if(!is_null($id))
			$this->id = $id;
		$this->db = new DB();
		$this->table = "teams";
	}
	
	function getValues(){
		return $this->db->queryRow("SELECT * FROM ".$this->table." WHERE ".
			" id = '".$this->id."'");
	}
	
	function toString(){
                return $this->db->queryOne("SELECT teamnaam FROM ".$this->table." WHERE ".
				"id = '".$this->id."'");
	}
	
	function getId(){
		return $this->id;
	}
    
    function getColor(){
        return "#".$this->db->queryOne("SELECT kleur FROM ".$this->table." WHERE ".
                "id = '".$this->id."'");
    }
	
	function getTable(){
		return $this->table;
	}
	
	/**
		First Letter Teams
	**/
	function getFLTeams($l){
		$ids = $this->db->queryMany("SELECT id FROM ".$this->table." WHERE ".
				" SUBSTRING(teamnaam, 1,1)='".$l."' ORDER BY teamnaam ASC");
		$result = array();
		foreach($ids as $id){
			array_push($result, new Team($id));
		}
		return $result;
	}
	
	function showPicture(){
	 	$photo = getcwd()."/images/teamlogos/".$this->id.".jpg";
		if(file_exists($photo)){
		 	header("Content-Type: image/jpeg");
			$im = imagecreatefromjpeg($photo);
			imagejpeg($im);
		}
		else{
			header("Content-Type: image/jpeg");
			$im = imagecreatefromjpeg(getcwd()."/images/pasfotos/nophoto.jpg");
			imagejpeg($im);
		}
	}
	
	function getMatches($seizoenid = null){
		$wedstrijd = new Wedstrijd();
		return $wedstrijd->getTeamMatches($this->id, $seizoenid);
	}
	
	function getSeasons(){
		$dteams = new DTeams();
		return $dteams->getSeasons($this->id);
	}
	
	/**
	*	1 = win, 2=verlies, 3=gelijk
	*/
	function nrOfResults($result){
		$wedstrijd = new Wedstrijd();
		return $wedstrijd->nrOfResults($this->id, $result);
	}
	
	function findTeam($input){
		$teams = $this->db->query("SELECT id, teamnaam FROM ".$this->table."");
		foreach($teams as $team){
			$score = levenshtein($input, $team['teamnaam']) / strlen($team['teamnaam']);
			if($score <= 0.4){
				return new Team($team['id']);
				break;
			}
		}
	}
}

?>
