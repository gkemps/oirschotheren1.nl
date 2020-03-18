<?php
require_once("db.class.php");
require_once("goal.class.php");
require_once("kaart.class.php");
require_once("opstelling.class.php");
require_once("positie.class.php");
require_once("dspelers.class.php");

class Speler {
 
 	private $id;
 	private $db;
 	private $table;
	
	function __construct ($id = null){
		if(!is_null($id))
			$this->id = $id;
		$this->db = new DB();
		$this->table = "spelers";
	}
	
	function getValues(){
		return $this->db->queryRow("SELECT * FROM ".$this->table." WHERE ".
				"id = '".$this->id."'");
	}
	
	function getId(){
		return $this->id;
	}
	
	function getTable(){
		return $this->table;
	}
	
	function toString(){
		$values = $this->getValues();
		return $values['Voornaam']." ".$values['Tussennaam']." ".$values['Achternaam'];
	}
	
	function shortName(){
		$values = $this->getValues();
		if(empty($values['Achternaam']))
		   return $values['Voornaam'];
                else
		   return substr($values['Voornaam'], 0, 1).". ".$values['Achternaam'];
	}
	
	function fullName(){
	 	$values = $this->getValues();
		return $values['Achternaam'].", ".$values['Voornaam']." ".$values['Tussennaam'];
	}
	
	function getList(){
		$ids = $this->db->queryMany("SELECT id FROM ".$this->table."");
		$result = array();
		foreach($ids as $id){
			array_push($result, new Speler($id));
		}
		return $result;
	}
	
	/**
		First Letter Players
	**/
	function getFLPlayers($l){
		$ids = $this->db->queryMany("SELECT id FROM ".$this->table." WHERE ".
				" SUBSTRING(Achternaam, 1,1)='".$l."' ORDER BY Achternaam ASC".
				", Voornaam ASC");
		$result = array();
		foreach($ids as $id){
			array_push($result, new Speler($id));
		}
		return $result;
	}
	
	function getPlayers(){
        $ids = $this->db->queryMany("SELECT id FROM ".$this->table." WHERE Tonen = '1' ORDER BY Achternaam ASC, Voornaam ASC");
        $result = array();
		foreach($ids as $id){
			array_push($result, new Speler($id));
		}
		return $result;
    }
	
	function showPicture(){
	 	$photo = getcwd()."/images/pasfotos/".$this->id.".jpg";
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
		$opst = new Opstelling();
		return $opst->getSeasonMatches($this->id, $seizoenid);
	}
	
	function getBenchMatches($seizoenid = null){
		$opst = new Opstelling();
		return $opst->getSeasonBenchMatches($this->id, $seizoenid);
	}
	
	function getGoals($wedid = null){
		$goals = new Goal();
		return $goals->getPlayerGoals($this->id, $wedid);
	}
	
	function getAssists($wedid = null){
		$goals = new Goal();
		return $goals->getPlayerAssists($this->id, $wedid);
	}
	
	function getCards($wedid = null){
		$kaarten = new Kaart();
		return $kaarten->getPlayerCards($this->id, $wedid);
	}
	
	function getSeasonGoals($seizoenid){
		$goal = new Goal();
		return $goal->getSeasonGoals($this->id, $seizoenid);
	}
	
	function getSeasonAssists($seizoenid){
		$goal = new Goal();
		return $goal->getSeasonAssists($this->id, $seizoenid);
	}
	
	function getSeasonCards($seizoenid, $kleur = null){
		$card = new Kaart();
		return $card->getSeasonCards($this->id, $seizoenid, $kleur);
	}
	
	function getSeasons(){
		$dspelers = new DSpelers();
		return $dspelers->getPlayerSeasons($this->id);
	}
	
	function isPlayer($name){
	 	$name = strtolower($name);
	 	$lsh1 = levenshtein(strtolower($this->toString()), $name);
		$lsh2 = levenshtein($name, strtolower($this->toString()));
		$len = strlen($this->fullName()) + strlen($name);
		return ($lsh1 + $lsh2 / $len);
	}
	
	function getRugNummer($seizoenid){
		$dspelers = new DSpelers();
		return $dspelers->getRugNummer($this->id, $seizoenid);
	}
	
	function getType(){
		$opstelling = new Opstelling();
		$lastpos = $opstelling->getLastPos($this->id);
		$values = $lastpos->getValues();
		$positie = new Positie($values['posid']);
		$values = $positie->getValues();
		if($values['lijn']==="0"){
			return "Keeper";
		}
		elseif($values['lijn']>0 and $values['lijn']<3){
			return "Verdediger";
		}
		elseif($values['lijn']>2 and $values['lijn']<6){
			return "Middenvelder";
		}
		elseif($values['lijn']>5 and $values['lijn']<7){
			return "Aanvaller";
		}
		else{
			return "Onbekend";
		}
	}
	
	function isActive($seizoenid){
		$dspelers = new DSpelers();
		return $dspelers->isActive($this->id, $seizoenid);
	}
	
	function getAge(){
		$gdatum = $this->db->queryOne("SELECT Geboortedatum FROM ".$this->table." WHERE ".
				" id = '".$this->id."'");
		$year = substr($gdatum, 0, 4);
		$month = substr($gdatum, 5, 2);
		$day = substr($gdatum, 8, 2);
		$thisyear = date("Y");
		$thismonth = date("m");
		$thisday = date("d");
		if($thismonth <= $month and $thisday <= $day)
			return $thisyear - $year - 1;
		else
			return $thisyear - $year;
	}
}

?>
