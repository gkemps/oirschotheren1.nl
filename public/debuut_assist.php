<?php
	require_once("functions/layout.inc.php");
	pre();
		$db = new DB();
		$query = $db->query("SELECT * FROM spelers");
		foreach($query as $result){
			$gbd = $result['Geboortedatum'];
			$id = $result['id'];
			$subq = $db->queryRow("SELECT w.datum FROM programma AS w, goals AS g, spelers AS s WHERE g.wedid = w.id AND g.aangever = s.id AND g.aangever = '$id' ORDER BY w.datum ASC LIMIT 1");
			
			$dpd = $subq['datum'];
			//print "(gbd: $gbd)(doelpunt: $dpd)";
			$gbd = strtotime($gbd);
			$dpd = strtotime($dpd);
			$d = ($dpd - $gbd) / (24 * 60 * 60);
			
      if(isset($subq['datum']) && $d > 0)
      {
        $aDays[$id] = $d;
        $aDiff[$id] = age($result['Geboortedatum'], $subq['datum']);
        $aPlayers[$id] = array('naam' => $result['Voornaam']." ".$result['Achternaam']);
      }
		}
    
    asort($aDays);
    
    $iCount = 1;
    foreach($aDays as $id => $days)
    {
      print $iCount++.". ";
      print $aPlayers[$id]['naam'];
      //print ": ".$days." dagen";
      print " (".$aDiff[$id].")";
      print "<br/>";
    }
		
		function age($sGBDatum, $sDatum)
    {
      $bday = new DateTime($sGBDatum);
      // $today = new DateTime('00:00:00'); - use this for the current date
      $today = new DateTime($sDatum); // for testing purposes

      $diff = $today->diff($bday);

      return sprintf('%d jaar, %d maanden, %d dagen', $diff->y, $diff->m, $diff->d);
    }
		
		
		function GetDateDifference($StartDateString=NULL, $EndDateString=NULL) {
        $ReturnArray = array();
       
        $SDSplit = explode('/',$StartDateString);
        $StartDate = mktime(0,0,0,$SDSplit[0],$SDSplit[1],$SDSplit[2]);
       
        $EDSplit = explode('/',$EndDateString);
        $EndDate = mktime(0,0,0,$EDSplit[0],$EDSplit[1],$EDSplit[2]);
       
        $DateDifference = $EndDate-$StartDate;
       
        $ReturnArray['YearsSince'] = $DateDifference/60/60/24/365;
        $ReturnArray['MonthsSince'] = $DateDifference/60/60/24/365*12;
        $ReturnArray['DaysSince'] = $DateDifference/60/60/24;
        $ReturnArray['HoursSince'] = $DateDifference/60/60;
        $ReturnArray['MinutesSince'] = $DateDifference/60;
        $ReturnArray['SecondsSince'] = $DateDifference;

        $y1 = date("Y", $StartDate);
        $m1 = date("m", $StartDate);
        $d1 = date("d", $StartDate);
        $y2 = date("Y", $EndDate);
        $m2 = date("m", $EndDate);
        $d2 = date("d", $EndDate);
       
        $diff = '';
        $diff2 = '';
        if (($EndDate - $StartDate)<=0) {
            // Start date is before or equal to end date!
            $diff = "0 days";
            $diff2 = "Days: 0";
        } else {

            $y = $y2 - $y1;
            $m = $m2 - $m1;
            $d = $d2 - $d1;
            $daysInMonth = date("t",$StartDate);
            if ($d<0) {$m--;$d=$daysInMonth+$d;}
            if ($m<0) {$y--;$m=12+$m;}
            $daysInMonth = date("t",$m2);
           
            // Nicestring ("1 year, 1 month, and 5 days")
            if ($y>0) $diff .= $y==1 ? "1 year" : "$y years";
            if ($y>0 && $m>0) $diff .= ", ";
            if ($m>0) $diff .= $m==1? "1 month" : "$m months";
            if (($m>0||$y>0) && $d>0) $diff .= ", and ";
            if ($d>0) $diff .= $d==1 ? "1 day" : "$d days";
           
            // Nicestring 2 ("Years: 1, Months: 1, Days: 1")
            if ($y>0) $diff2 .= $y==1 ? "Years: 1" : "Years: $y";
            if ($y>0 && $m>0) $diff2 .= ", ";
            if ($m>0) $diff2 .= $m==1? "Months: 1" : "Months: $m";
            if (($m>0||$y>0) && $d>0) $diff2 .= ", ";
            if ($d>0) $diff2 .= $d==1 ? "Days: 1" : "Days: $d";
           
        }
        $ReturnArray['NiceString'] = $diff;
        $ReturnArray['NiceString2'] = $diff2;
        return $ReturnArray;
    }
   
	post();
?>