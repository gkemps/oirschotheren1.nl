<?php
	require_once("db.class.php");
	require_once("team.class.php");
	require_once("seizoen.class.php");
	
	class DTeams{
		
		private $id;
		private $db;
		private $table;
		
		function __construct($id = null){
			if(!is_null($id))
				$this->id = $id;
			$this->db = new DB();
			$this->table = "deelnemingteams";
		}
		
		function getId(){
			return $this->id;
		}
		
		function getTeams($seasonid){
		 	$team = new Team();
		 	$ttable = $team->getTable();
			$ids = $this->db->queryMany("SELECT teamid FROM ".$this->table." AS dt, ".
					" ".$ttable." AS t WHERE seizoenid = '".$seasonid."' AND ".
						"dt.teamid = t.id ORDER BY t.teamnaam ASC");
			$result = array();
			foreach($ids as $id){
				array_push($result, new Team($id));
			}
			return $result;
		}
		
		function getSeasons($teamid){
			$ids = $this->db->queryMany("SELECT seizoenid FROM ".$this->table." WHERE ".
				"teamid = '".$teamid."'");
			$result = array();
			foreach($ids as $id){
				array_push($result, new Seizoen($id));
			}
			return $result;
		}
	}
