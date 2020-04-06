
<?php
	require_once("functions/functions.php");
	require_once("functions/layout.inc.php");    
    require_once("functions/ofc-library/open-flash-chart-object.php");
	
	$id = $_REQUEST['id'];
	$wedstrijd = new Wedstrijd($id);
	
	print "<div id=\"tabs\">";
    print "<ul>";                       
		//Oirschot speelt en wedstrijd is afgelopen
        if($wedstrijd->OirschotInMatch() and $wedstrijd->played()){
        	print "<li id=\"tabHeader1\" class=\"currenttab\">";
				print "<a href=\"javascript:void(0)\" onClick=\"toggleTab(1,5)\">";
					print "<span>Match Sheet</span>";
				print "</a>";
			print "</li>";
			print "<li id=\"tabHeader2\">";
				print "<a href=\"javascript:void(0)\" onClick=\"toggleTab(2,5)\">";
					print "<span>Score Sheet</span>";
				print "</a>";
			print "</li>";
			print "<li id=\"tabHeader3\">";
				print "<a href=\"javascript:void(0)\" onClick=\"toggleTab(3,5)\">";
					print "<span>Verslag</span>";
				print "</a>";
			print "</li>";
			print "<li id=\"tabHeader4\">";
				print "<a href=\"javascript:void(0)\" onClick=\"ofc('or', '".$id."', 'or_chart');
                    ofc('od', '".$id."', 'od_chart');toggleTab(4,5)\">";
					print "<span>Head 2 Head</span>";
				print "</a>";
			print "</li>";
            print "<li id=\"tabHeader5\">";
                print "<a href=\"javascript:void(0)\" onClick=\"toggleTab(5,5)\">";
                    print "<span>Pre Match</span>";
                print "</a>";
            print "</li>";
		}
        //Wedstijd zonder Oirschot al gespeeld
        elseif($wedstrijd->played() and !$wedstrijd->OirschotInMatch()){
            print "<li id=\"tabHeader1\" class=\"currenttab\">";
                print "<a href=\"javascript:void(0)\" onClick=\"toggleTab(1,3)\">";
                    print "<span>Verslag</span>";
                print "</a>";
            print "</li>";
            print "<li id=\"tabHeader2\">";
                print "<a href=\"javascript:void(0)\" onClick=\"toggleTab(2,3)\">";
                    print "<span>Pre Match</span>";
                print "</a>";
            print "</li>";
            print "<li id=\"tabHeader3\">";
                print "<a href=\"javascript:void(0)\" onClick=\"ofc('or', '".$id."', 'or_chart');
                    ofc('od', '".$id."', 'od_chart');toggleTab(3,3)\">";
                    print "<span>Head 2 Head</span>";
                print "</a>";
            print "</li>";
        }
        
        //overig
        else{               
            print "<li id=\"tabHeader1\" class=\"currenttab\">";
                print "<a href=\"javascript:void(0)\" onClick=\"toggleTab(1,2)\">";
                    print "<span>Pre Match</span>";
                print "</a>";
            print "</li>";
            print "<li id=\"tabHeader2\">";
                print "<a href=\"javascript:void(0)\" onClick=\"ofc('or', '".$id."', 'or_chart');
                    ofc('od', '".$id."', 'od_chart');toggleTab(2,2)\">";
                    print "<span>Head 2 Head</span>";
                print "</a>";
            print "</li>";
        }
        
    print "</ul>";
	print "</div>";
	
    print "<div id=\"tabscontent\">";
        
	//Oirschot speelt en wedstrijd is afgelopen
    if($wedstrijd->OirschotInMatch() and $wedstrijd->played()){
        match_sheet($id, 1, true);
        score_sheet($id, 2, false);
        verslag($id, 3, false);
        head2head($id, 4, false);
        pre_match($id, 5, false);
    }
    
    //Wedstijd zonder Oirschot al gespeeld
    elseif($wedstrijd->played() and !$wedstrijd->OirschotInMatch()){
        verslag($id, 1, true);
        pre_match($id, 2, false);
        head2head($id, 3, false);
    }
    
    else{ 
        pre_match($id, 1, true);
        head2head($id, 2, false);        
    }
 
    print "</div><!--End of tabscontent-->";
print "</div><!--End of tabs-->";
print "</body>";
print "</html>";

function match_sheet($wedid, $nr, $visible){
    $vis = "";
    if(!$visible)
        $vis = " style=\"display:none\"";
    $wedstrijd = new Wedstrijd($wedid);
    print "<div id=\"tabContent$nr\" class=\"tabContent\" $vis>"; 
        $systeem = $wedstrijd->getSystem();
        if(is_null($systeem))
            $systeem = new Systeem(1);
        $lines = array_reverse($systeem->getLines());
        $positions = $systeem->getPositions();
        print "<div class=\"veld_\">";
        foreach($lines as $line){
            print "<div class=\"line\">";
                for($i=0;$i<$line;$i++){
                    $class = "pos".$line;
                    $pos = array_pop($positions);    
                    print "<div class=\"$class\">";          
                        positie_($pos, $wedstrijd);
                        //print "speler";
                    print "</div>";
                }
            print "</div>";
        }
        print "</div>";
    print "</div>";
}

function score_sheet($wedid, $nr, $visible){
    if(!$visible)
        $vis = " style=\"display:none\"";
    $wedstrijd = new Wedstrijd($wedid);
    print "<div id=\"tabContent$nr\" class=\"tabContent\" $vis>";
        $goals = $wedstrijd->getGoals();
        $helft = 1;
        print "<table width=\"100%\">";
        $i = 0;     
        foreach($goals as $goal){
            $bg = "#DAFFDB";
            if($i % 2 ==0)
                $bg = "#91BD93";
            $goal = $goal->getValues();
            if($goal['Helft']>$helft){
                print "<tr><td colspan=\"3\" style=\"text-align:center\">~</td></tr>";
                $helft = $goal['Helft'];
            }
             print "<tr>";      
            print "<td width=\"45%\" style=\"padding-left:50px;background:$bg\">";
            if($goal['Team']->getId()==$wedstrijd->homeTeam()->getId()){
                if(empty($goal['Maker'])){
                    print $goal['Team']->toString();
                }
                else{
                    if(is_string($goal['Maker'])){
                        print $goal['Maker'];
                    }
                    else{
                        print $goal['Maker']->shortName();
                    }
                }
                if(!empty($goal['Aangever'])){
                    if(is_string($goal['Aangever'])){
                     print "<i> (".$goal['Aangever'].")</i>";
                    }
                    else{
                     print "<i> (".$goal['Aangever']->shortName().")</i>";
                    }  
                }
            }
            print "</td>";
            print "<td width=\"10%\" style=\"text-align:center\"><b>";
                print $goal['Volgorde'];
            print "</b></td>";
            print "<td width=\"45%\" style=\"padding-right:50px;background:$bg\">";
                if($goal['Team']->getId()==$wedstrijd->awayTeam()->getId()){
                    if(empty($goal['Maker'])){
                        print $goal['Team']->toString();
                    }
                    else{
                        if(is_string($goal['Maker'])){
                            print $goal['Maker'];
                        }
                        else{
                            print $goal['Maker']->shortName();
                        }
                        if(is_string($goal['Aangever'])){
                         print "<i> (".$goal['Aangever'].")</i>";
                        }
                        else{
                         print "<i> (".$goal['Aangever']->shortName().")</i>";
                        }
                    }
                }
            print "</td>";
            print "</tr>";
            $i++;
        }
        print "</table>";
    print "</div>"; 
}

function head2head($wedid, $nr, $visible){
    if(!$visible)
        $vis = " style=\"display:none\"";
    $wedstrijd = new Wedstrijd($wedid);
    print "<div id=\"tabContent$nr\" class=\"tabContent\" $vis>";
    print "<div id=\"or_chart\"></div><br />";
    if($wedstrijd->OirschotInMatch())
        print "<div id=\"od_chart\"></div>";
    print "</div>";
}

function verslag($wedid, $nr, $visible){
    $vis = "";
    if(!$visible)
        $vis = " style=\"display:none\"";
    $wedstrijd = new Wedstrijd($wedid);
    print "<div id=\"tabContent$nr\" class=\"tabContent\" $vis>";
        $verslag = new Verslag(); 
        $text = $verslag->getVerslag($wedstrijd->getId());
        if(is_null($text)){
            print "<b>Geen verslag beschikbaar</b>";
        }
        else{
            $text = str_replace("</TITLE>", "</b>", $text);
            $text = str_replace("<TITLE>", "<b style=\"font-size:16px\">", $text);
            $text = str_replace("�", "'", $text);
            $text = str_replace("�", "\"", $text);
            $text = str_replace("�", "\"", $text); 
            $text = strip_tags($text, "<i><u><b><hr>");
            $text = htmlentities($text);
            $text = str_replace("&lt;", "<", $text); 
            $text = str_replace("&gt;", ">", $text); 
            //$text = utf8_encode($text);
            $text = nl2br($text);
            print $text;
        }
    print "</div>";       
}

function pre_match($wedid, $nr, $visible){
    if(!$visible)
        $vis = " style=\"display:none\"";
    $wedstrijd = new Wedstrijd($wedid); 
    print "<div id=\"tabContent$nr\" class=\"tabContent\" $vis>";
        $stand = new Stand($wedstrijd->getDate());
        $thuis = $wedstrijd->homeTeam()->toString();
        $uit = $wedstrijd->awayTeam()->toString();
        $thuis_id = $wedstrijd->homeTeam()->getId();
        $uit_id = $wedstrijd->awayTeam()->getId();
        $thuis_rang = $stand->getTeamRow($wedstrijd->homeTeam()->getId());
        $uit_rang =  $stand->getTeamRow($wedstrijd->awayTeam()->getId());       
        print "<table>";
            print "<tr>";
                print "<td></td>";
                print "<td><u><b>".$wedstrijd->homeTeam()->toString()."</u></b></td>";
                print "<td><u><b>".$wedstrijd->awayTeam()->toString()."</u></b></td>";
            print "</tr>";
            print "<tr>";
                print "<td><b>Positie ranglijst:</b></td>";
                $pos =   $stand->getTeamPositions();
                print "<td>".$pos[$thuis_id]."e</td>";
                print "<td>".$pos[$uit_id]."e</td>";
            print "</tr>";
            print "<tr>";
                print "<td><b>Punten:</b></td>";
                print "<td>".$thuis_rang['punten']."</td>";
                print "<td>".$uit_rang['punten']."</td>";
            print "</tr>";
            print "<tr>";
                print "<td><b>Voor/Tegen:</b></td>";
                print "<td>".$thuis_rang['voor']."/".$thuis_rang['tegen']."</td>";
                print "<td>".$uit_rang['voor']."/".$uit_rang['tegen']."</td>";
            print "</tr>";
            print "<tr>";
                print "<td><b>Vorm:</b></td>";
                print "<td>".$wedstrijd->getTeamForm($thuis_id, $wedstrijd->getDate())."</td>";
                print "<td>".$wedstrijd->getTeamForm($uit_id, $wedstrijd->getDate())."</td>";
            print "</tr>";
            print "<tr>";
                print "<td><b>Thuis & Uit resultaat:</b></td>";
                $thuis_result = $stand->getHomeResult($thuis_id);
                $uit_result = $stand->getAwayResult($uit_id); 
                if($thuis_result[1] > 0 and $uit_result[1] > 0){
                    print "<td>".round(($thuis_result[0]/$thuis_result[1])*100)."%</td>";
                    print "<td>".round(($uit_result[0]/$uit_result[1])*100)."%</td>";
                }
                else{
                    print "<td><i>nvt</i></td>";
                    print "<td><i>nvt</i></td>";
                }
            print "</tr>";
            print "<tr>";
                print "<td><b>Return:</b></td>";
                $return = $wedstrijd->returnMatch(); 
                print "<td colspan=\"2\">";
                    print $return->toString();
                    print " ";
                    if($return->played()){
                        print $return->printScore();
                        print " (".$return->getDate().")";
                    }
                    else
                        print $return->getDate();
                print "</td>";  
            print "</tr>";
            print "<tr>";
                print "<td valign=\"top\"><b>Laatste 3:</b></td>";
                $weds_thuis = $wedstrijd->getLastPlayed($thuis_id, 3, $wedstrijd->getDate());
                $weds_uit =   $wedstrijd->getLastPlayed($uit_id, 3, $wedstrijd->getDate());
                print "<td>";
                foreach($weds_thuis as $wed){
                    print $wed->toString()." ";
                    print $wed->printScore()."<br />";
                }
                print "</td>";
                print "<td>";
                foreach($weds_uit as $wed){
                    print $wed->toString()." ";
                    print $wed->printScore()."<br />";
                }
                print "</td>";                                                 
            print "</tr>";
            print "<tr>";
                print "<td><b>Laatste thuis nederlaag ".$thuis.":</b></td>";
                print "<td colspan=\"2\">";
                    $wed = $wedstrijd->getLastHomeLoss($thuis_id, $wedstrijd->getDate());
                    if(is_null($wed))
                        print "<i>onbekend</i>";
                    else{
                        print $wed->toString()." ";
                        print $wed->printScore()." ";
                        print "<i>(".daysAgo($wed->getDate(), $wedstrijd->getDate())." dagen geleden)</i>"."<br />"; 
                    }     
                print "</td>";  
            print "</tr>";
            print "<tr>";
                print "<td><b>Laatste thuis overwinning ".$thuis.":</b></td>";
                print "<td colspan=\"2\">";
                    $wed = $wedstrijd->getLastHomeWin($thuis_id, $wedstrijd->getDate());
                    if(is_null($wed))
                        print "<i>onbekend</i>";
                    else{
                        print $wed->toString()." ";
                        print $wed->printScore()." ";
                        print "<i>(".daysAgo($wed->getDate(), $wedstrijd->getDate())." dagen geleden)</i>"."<br />";      
                    }
                print "</td>";  
            print "</tr>";
            print "<tr>";
                print "<td><b>Laatste uit nederlaag ".$uit.":</b></td>";
                print "<td colspan=\"2\">";
                    $wed = $wedstrijd->getLastAwayLoss($uit_id, $wedstrijd->getDate());
                    if(is_null($wed))
                        print "<i>onbekend</i>";
                    else{
                        print $wed->toString()." ";
                        print $wed->printScore()." ";
                        print "<i>(".daysAgo($wed->getDate(), $wedstrijd->getDate())." dagen geleden)</i>"."<br />"; 
                    }     
                print "</td>";  
            print "</tr>";
            print "<tr>";
                print "<td><b>Laatste uit overwinning ".$uit.":</b></td>";
                print "<td colspan=\"2\">";
                    $wed = $wedstrijd->getLastAwayWin($uit_id, $wedstrijd->getDate());
                    if(is_null($wed))
                        print "<i>onbekend</i>";
                    else{
                        print $wed->toString()." ";
                        print $wed->printScore()." ";
                        print "<i>(".daysAgo($wed->getDate(), $wedstrijd->getDate())." dagen geleden)</i>"."<br />";  
                    }    
                print "</td>";  
            print "</tr>";
        print "</table>";
    print "</div>";  
}
