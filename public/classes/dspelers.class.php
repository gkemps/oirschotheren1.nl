<?php
	require_once("db.class.php");
	require_once("speler.class.php");
	require_once("seizoen.class.php");
	
	class DSpelers{
		
		private $id;
		private $db;
		private $table;
		
		function __construct($id = null){
			if(!is_null($id))
				$this->id = $id;
			$this->db = new DB();
			$this->table = "deelnemingspelers";
		}
		
		function getId(){
			return $this->id;
		}
		
		function getPlayers($seasonid){
			$ids = $this->db->queryMany("SELECT spelersid FROM ".$this->table." WHERE ".
				" seizoenid = '".$seasonid."' AND rugnummer IS NOT NULL ORDER BY rugnummer ASC");
			$result = array();
			foreach($ids as $id){
				array_push($result, new Speler($id));
			}
			return $result;
		}
		
		function getPlayerSeasons($spelerid){
			$ids = $this->db->queryMany("SELECT seizoenid FROM ".$this->table." WHERE ".
				" spelersid = '".$spelerid."'");
			$result = array();
			foreach($ids as $id){
				array_push($result, new Seizoen($id));
			}
			return $result;
		}
		
		function getRugNummer($spelerid, $seizoenid){
			return $this->db->queryOne("SELECT rugnummer FROM ".$this->table." WHERE ".
				" spelersid = '".$spelerid."' AND seizoenid = '".$seizoenid."'");
		}
		
		function isActive($spelerid, $seizoenid){
			$id = $this->db->queryOne("SELECT id FROM ".$this->table." WHERE ".
					" spelersid = '".$spelerid."' AND seizoenid = '".$seizoenid."'");
			if(empty($id)){
				return false;
			}
			else{
				return true;
			}
		}
	}