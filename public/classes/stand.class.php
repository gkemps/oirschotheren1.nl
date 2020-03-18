<?php
	require_once("db.class.php");
	require_once("seizoen.class.php");
	require_once("wedstrijd.class.php");
	
	class Stand{
		
		private $datum;
		private $seizoen;
		private $db;
		private $table;
		
		function __construct($datum){
			$this->datum = $datum;
			$this->db = new DB();
			$this->table = "stand";
			$seizoen = new Seizoen();
			$seizoen->setSeason($datum);
			$this->seizoen = $seizoen;
            $this->stand = array();
		}
		
		function getSeason(){
			return $this->seizoen;
		}
		
		function initTable(){         
            $this->stand = array();                                
			$teams = $this->seizoen->getTeams();
			foreach ($teams as $team){
                $this->insert($team);    
			}
		}
        
        function insert($team){
			  $punten = 0;
			  if ($team->getId() == 31) {
				  $punten = -3;
			  }
              $this->stand[$team->getId()] = array("teamnaam" => $team->toString(),
                                "teamid" => $team->getId(),
                                "punten" => $punten,
                                "gespeeld" => 0,
                                "gewonnen" => 0,
                                "gelijk" => 0,
                                "verloren" => 0,
                                "voor" => 0,
                                "tegen" => 0,
                                "saldo" => 0
                                );
        }
		
		function initYearTable($year){              
            $this->stand = array();                 
		 	$seizoen = new Seizoen();
		 	$seizoenen = $seizoen->yearToSeasons($year);
		 	$teams1 = $seizoenen[0]->getTeams();
		 	if(count($seizoenen)>1)
		 		$teams2 = $seizoenen[1]->getTeams();
		 	else
		 		$teams2 = array();
			foreach ($teams1 as $team){
				$this->insert($team);
			}
			foreach($teams2 as $team){
				if(!in_array($team, $teams1))
					$this->insert($team);
			}
		}
		
		function initGraphData(){
			$data = array();
			$teams = $this->seizoen->getTeams();
			foreach ($teams as $team){
				$data[$team->getId()] = array(0);
			}
			return $data;
		} 
        
        function sortTable(){
            $rows = $this->stand;
            $this->stand = array();
            foreach($rows as $teamid => $row){
                $naam[$teamid] = $row['teamnaam'];
                $punten[$teamid] = $row['punten'];
                $gespeeld[$teamid] = $row['gespeeld'];
                $gewonnen[$teamid] = $row['gewonnen'];
                $gelijk[$teamid] = $row['gelijk'];
                $verloren[$teamid] = $row['verloren'];
                $voor[$teamid] = $row['voor'];
                $tegen[$teamid] = $row['tegen'];
                $saldo[$teamid] =  $row['saldo'];
            }
            array_multisort($punten, SORT_DESC, $gewonnen, SORT_DESC, $saldo, SORT_DESC, $naam, SORT_ASC, $rows);
            foreach($rows as $row){
                $this->stand[$row['teamid']] = $row;
            }
        }
		
		function calcTable($thuis = true, $uit = true){
		 	$this->initTable();
		 	$bounds = $this->seizoen->getValues();
		 	$wedstrijd = new Wedstrijd();
		 	$weds = $wedstrijd->getMatches($bounds['start'], $this->datum);
		 	foreach($weds as $wed){
				$this->processMatch($wed, $thuis, $uit);
			}
            $this->sortTable();
		}
		
		function calcYearTable($year){
			$this->initYearTable($year);
			$start = "$year-01-01";
			$eind = "$year-12-31";
			$wedstrijd = new Wedstrijd();
			$weds = $wedstrijd->getMatches($start, $eind);
			foreach($weds as $wed){
				$this->processMatch($wed, true, true);
			}
			$this->sortTable();
		}
		
		function calcGraphData(){
			$data = $this->initGraphData();
		 	$bounds = $this->seizoen->getValues();
		 	$wedstrijd = new Wedstrijd();
		 	$nu = date("Y-m-d");
		 	$weds = $wedstrijd->getMatches($bounds['start'], $nu);
		 	foreach($weds as $wed){
				$data = $this->matchData($wed, $data);
			}
			return $data;
		}
		
		function calcOirschotGraph(){
			$seizoen = new Seizoen();
			$wedstrijd = new Wedstrijd();
			$seizoenen = $seizoen->getSeasons();
			$data = array();
			foreach($seizoenen as $seizoen){
				$data[$seizoen->getId()] = array(0);
				$wedstrijden = $wedstrijd->getTeamMatches(2, $seizoen->getId());
				foreach($wedstrijden as $wed){
					if($wed->played()){
						$values = $wed->getValues();
						if($values['Scorethuis']>$values['Scoreuit'] and ($values['Thuis'] == 2)){
							array_push($data[$seizoen->getId()], 3);
						}
						elseif($values['Scorethuis']>$values['Scoreuit'] and ($values['Uit'] == 2)){
							array_push($data[$seizoen->getId()], 0);
						}
						elseif($values['Scoreuit']>$values['Scorethuis'] and ($values['Uit'] == 2)){
							array_push($data[$seizoen->getId()], 3);
						}
						elseif($values['Scoreuit']>$values['Scorethuis'] and ($values['Thuis'] == 2)){
							array_push($data[$seizoen->getId()], 0);
						}
						else{
						 	array_push($data[$seizoen->getId()], 1);
						}
					}
				}
			}
			return $data;
		}
		
		function matchData($wed, $data){
			if($wed->played()){
				$values = $wed->getValues();
				if($values['Scorethuis']>$values['Scoreuit']){
					array_push($data[$values['Thuis']], 3);
					array_push($data[$values['Uit']], 0);
				}
				elseif($values['Scoreuit']>$values['Scorethuis']){
					array_push($data[$values['Thuis']], 0);
					array_push($data[$values['Uit']], 3);
				}
				else{
				 	array_push($data[$values['Thuis']], 1);
					array_push($data[$values['Uit']], 1);
				}
			}
			return $data;
		}
		
		function processMatch($wed, $thuis, $uit){
                         if($wed->played()){
				$values = $wed->getValues();
				if($values['Scorethuis']>$values['Scoreuit']){
					if($thuis)
						$this->update($values['Thuis'], 3, $values['Scorethuis'], 
								$values['Scoreuit']);
					if($uit)
						$this->update($values['Uit'], 0, $values['Scoreuit'], 
								$values['Scorethuis']);
				}
				elseif($values['Scoreuit']>$values['Scorethuis']){
					if($uit)
						$this->update($values['Uit'], 3, $values['Scoreuit'], 
								$values['Scorethuis']);
					if($thuis)
						$this->update($values['Thuis'], 0, $values['Scorethuis'], 
								$values['Scoreuit']);
				}
				else{
				 	if($thuis)
						$this->update($values['Thuis'], 1, $values['Scorethuis'], 
								$values['Scoreuit']);
					if($uit)
						$this->update($values['Uit'], 1, $values['Scoreuit'], 
								$values['Scorethuis']);
				}
			}
		}
		
		function update($teamid, $points, $for, $against){   
            $this->stand[$teamid]['gespeeld']++;
            $this->stand[$teamid]['punten'] = $this->stand[$teamid]['punten'] + $points; 
            $this->stand[$teamid]['voor'] = $this->stand[$teamid]['voor'] + $for;
            $this->stand[$teamid]['tegen'] = $this->stand[$teamid]['tegen'] + $against;
            $this->stand[$teamid]['saldo'] = $this->stand[$teamid]['saldo'] + ($for-$against);  
            if($points == 3)
               $this->stand[$teamid]['gewonnen']++;   
            if($points == 1)
               $this->stand[$teamid]['gelijk']++;
            if($points == 0)
               $this->stand[$teamid]['verloren']++;    
		}
		
		function getTable(){ 
		 	$this->calcTable();                                                   
			return $this->stand;
		}
		
		function getYearTable($year){
		    $this->calcYearTable($year);   
            return $this->stand;
		}
		
		function getHomeTable(){
			$this->calcTable(true, false); 
            return $this->stand;
		}
        
        function getHomeResult($teamid){
            $this->calcTable(true, false);
            $row = $this->stand[$teamid];
            $punten = $row['punten'];
            $max = $row['gespeeld'] * 3;
            return array($punten, $max);
        }
        
        function getAwayResult($teamid){
            $this->calcTable(false, true);
            $row = $this->stand[$teamid];
            $punten = $row['punten'];
            $max = $row['gespeeld'] * 3;   
            return array($punten, $max);
        }
		
		function getAwayTable(){
			$this->calcTable(false, true); 
            return $this->stand;
		}
		
		function getTeamRow($teamid){    
			$this->calcTable();
			return $this->stand[$teamid];
		}
		
		function getTeamPositions(){
			$this->calcTable();                                  
			$i = 1;
            $result = array();
			foreach($this->stand as $teamid => $row){
			 	$result[$teamid] = $i++;
			}
			return $result;  
		}
		
		function tablePosition($teamid){
			$this->calcTable();
            $i=1;
		 	foreach($this->stand as $key => $row){
                 if($key==$teamid)
                    return $i;
                 $i++;
            }
		}
	}

?>
