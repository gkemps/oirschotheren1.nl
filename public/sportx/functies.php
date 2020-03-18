<?php
	require_once("db.class.php");
	$db = new DB();
	
	function gestemd(){
		global $db;
		$result = $db->query("SELECT * FROM stemmen WHERE ip = '".
				$_SERVER['REMOTE_ADDR']."'");         
		if (count($result)>0)
			return true;
		else
			return false;
	}
    
    function genomineerd(){
        global $db;
        $result = $db->query("SELECT * FROM nomineren WHERE ADDTIME(datum, '0 12:0:00') > NOW() AND ip = '".
                $_SERVER['REMOTE_ADDR']."'");
        if (count($result)>0)
            return true;
        else
            return false;
    }
	
	function aantalstemmen(){
	 	global $db;
		$result = $db->query("SELECT * FROM ploegen");
		return count($result);
	}
	
	function nomineren($lidnr){
		global $db;
		$fields["lidnr"] = $lidnr;
		$fields["datum"] = "NOW()";
		$fields["ip"] = $_SERVER['REMOTE_ADDR'];
		$db->insert("nomineren", $fields);
	}
	
	function stemmen($man, $vrouw, $ploeg, $mot){
	    global $db;
       $fields['man'] = $man;
       $fields['vrouw'] = $vrouw;
       $fields['ploeg'] = $ploeg;
       $fields['datum'] = "NOW()";
       $fields['ip'] = $_SERVER['REMOTE_ADDR'];
       $fields['motivatie'] = $mot;
       $db->insert("stemmen", $fields);
   }
	
	function ploegen($ploeg){
		global $db;
		$fields["ploeg"] = $ploeg;
		$fields["datum"] = "NOW()";
		$fields["ip"] = $_SERVER['REMOTE_ADDR'];
		$db->insert("ploegen", $fields);
	}
	
	function resultaten($geslacht){
		global $db;
		if($geslacht == "M" or $geslacht == "V"){
			$results = $db->query("SELECT *, COUNT(s.id) AS aantal FROM stemmen AS s, leden AS l WHERE ".
						"s.lidnr = l.lidnummer AND geslacht = '$geslacht' GROUP BY s.lidnr ORDER BY aantal DESC LIMIT 5");
			foreach($results as $result){
				$datax[] = $result['achternaam'].", ".$result['voornaam']." ".$result['tussenvoegsel'];
				$datay[] = $result['aantal'];
			}
		}
		elseif($geslacht == "P"){
		 	$results = $db->query("SELECT *, COUNT(p.id) AS aantal FROM ploegen AS p GROUP BY p.ploeg ".
			 		"ORDER BY aantal DESC LIMIT 5");
			foreach($results as $result){
				$datax[] = $result['ploeg'];
				$datay[] = $result['aantal'];
			}
		}
		return array($datax, $datay);
	}
    
    function last_vote(){
        global $db;
        return $db->queryOne("SELECT datum FROM nomineren ORDER BY id DESC") ;
    }
    
    function nominaties(){
        global $db;
        return $db->query("SELECT count(id) as aantal, n.lidnr, voornaam, achternaam FROM nomineren as n, 
        leden as l WHERE n.lidnr = l.lidnummer GROUP BY n.lidnr ORDER BY aantal DESC");
    }
    
    function nominaties_ploegen(){
        global $db;
        return $db->query("SELECT count(id) as aantal, ploeg FROM ploegen as p GROUP BY ploeg ORDER BY aantal DESC");
    }
    
	
	function stemlijst($type, $nominees){
	    shuffle($nominees);
        $i=0;
        foreach($nominees as $nominee){
            if($i==0)
                print "<input type=\"radio\" name=\"$type\" value=\"$nominee\" checked>";
            else
                 print "<input type=\"radio\" name=\"$type\" value=\"$nominee\">";            
          print "<b>".$nominee."</b><br>";
          $i++;
      }
   }
	
	function nomineerlijst($geslacht){
		global $db;
		if($geslacht == "M" or $geslacht == "V"){
			$results = $db->query("SELECT * FROM leden WHERE geslacht = '$geslacht' ORDER BY achternaam, voornaam");
			print "<select name=\"$geslacht\">";
			$i=0;
			$select = rand(0, count($results)-1);
			foreach($results as $rij){
				$sel = ($select==$i) ? "selected" : "";
				print "<OPTION VALUE=\"".$rij['lidnummer']."\" $sel>"
					.utf8_encode($rij['achternaam'].", ".$rij['voornaam']." ".$rij['tussenvoegsel']."(".$rij['team'].")").
					"</OPTION>";
				$i++;
			}
			print "</select>";
		}
		elseif($geslacht == "P"){
			$results = $db->query("SELECT DISTINCT(team) FROM leden WHERE team IS NOT NULL AND team NOT LIKE '%keepers%' ORDER BY team");
			print "<select name=\"$geslacht\">";
			$i=0;
			$select = rand(0, count($results));
			foreach($results as $rij){
			 	$sel = ($select==$i) ? "selected" : "";
			 	if(trim($rij['team'])!=="")
					print "<OPTION VALUE=\"".$rij['team']."\" $sel>"
						.$rij['team']."</OPTION>";
				$i++;
			}
			print "</select>";
		}
	}
	
	
?>