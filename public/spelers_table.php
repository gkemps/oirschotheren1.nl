<?php
 require_once("functions/layout.inc.php");
 require_once("classes/vroegah.class.php");
 pre();
 
 function display($arg, $actief){
    $result = $arg;
    if($arg % 25 == 0 and $arg !== 0)
        $result = "<b>$result</b>";
    if($arg % 50 == 0 and $arg !== 0)
        $result = "<u>$result</u>";
    if($arg == 0 or is_null($arg))
        $result = "-";
    if(($arg % 50 == 49) and $actief)
        $result = "<font style=\"color:#ffcc33\"><b>$result</b></font>";
    return $result;
 }
 print "<div style=\"width:700px\">";
	print "<h2>Spelers: feiten en cijfers</h2>";
 $speler = new Speler();
 $vroegah = new Vroegah(); 
 $vroegah->getTotalCards(4);
 $spelers = $speler->getPlayers();

 print "<table cellspacing=\"0\" style=\"border: 2px solid #00937A;\">";
 print "<tr>";
            print "<td class=\"stand_header\"><b>Naam</b></td>";
            print "<td class=\"stand_header\" style=\"border-left: 2px solid #00937A\">
                        <img src=\"images/wedstrijd.gif\" class=\"icon\">+
                        <img src=\"images/bank.gif\" class=\"icon\"></td>";
            print "<td class=\"stand_header\"><img src=\"images/wedstrijd.gif\" class=\"icon\"></td>";
            print "<td class=\"stand_header\"><img src=\"images/bank.gif\" class=\"icon\"></td>";
            print "<td class=\"stand_header\"><img src=\"images/goal.gif\" class=\"icon\"></td>";
            print "<td class=\"stand_header\"><img src=\"images/assist.gif\" class=\"icon\"></td>";
            print "<td class=\"stand_header\"><img src=\"images/kaarten.jpg\" class=\"icon\"></td>";
            
            print "<td class=\"stand_header\" style=\"border-left: 2px solid #00937A\"><img src=\"images/wedstrijd.gif\" class=\"icon\"></td>";
            print "<td class=\"stand_header\"><img src=\"images/bank.gif\" class=\"icon\"></td>";
            print "<td class=\"stand_header\"><img src=\"images/goal.gif\" class=\"icon\"></td>";
            print "<td class=\"stand_header\"><img src=\"images/assist.gif\" class=\"icon\"></td>";
            print "<td class=\"stand_header\"><img src=\"images/kaarten.jpg\" class=\"icon\"></td>";
        print "</tr>";
/** @var Speler $speler */
foreach($spelers as $speler){
        $spid = $speler->getId();
        $nrmatches =   count($speler->getMatches());
        $nrbenchmatches = count($speler->getBenchMatches());
        $nrbasismatches = $nrmatches - $nrbenchmatches;
        $nrgoals = count($speler->getGoals()) + $vroegah->getTotalGoals($spid);
        $nrassists = count($speler->getAssists()) + $vroegah->getTotalAssists($spid); 
        $nrcards = count($speler->getCards())+ $vroegah->getTotalCards($spid);
        
        $seizoen = new Seizoen();
        $seizoen->setSeason("2017-09-01");
        $sid = $seizoen->getId();
        $nrsmatches =   count($speler->getMatches($sid));
        $nrsbenchmatches = count($speler->getBenchMatches($sid));
        $nrsbasismatches = $nrsmatches - $nrsbenchmatches;
        $nrsgoals = count($speler->getSeasonGoals($sid));
        $nrsassists = count($speler->getSeasonAssists($sid));
        $nrscards = count($speler->getSeasonCards($sid));
        
        $css = "";
        $actief = $speler->isActive($sid);
        if($actief)
            $css = "background:#92bc94";
        print "<tr>";
            print "<td style=\"$css\"><a href=\"speler.php?id=".$speler->getId()."\">".$speler->fullName()."</a></td>";
            print "<td style=\"$css ;border-left: 2px solid #00937A\" >".display($nrmatches, $actief)."</td>";
            print "<td style=\"$css\">".display($nrbasismatches, $actief)."</td>";
            print "<td style=\"$css\">".display($nrbenchmatches, false)."</td>";
            print "<td style=\"$css\">".display($nrgoals, $actief)."</td>";
            print "<td style=\"$css\">".display($nrassists, $actief)."</td>";
            print "<td style=\"$css\">".display($nrcards, $actief)."</td>";
            
            if($actief){
                print "<td style=\"$css ;border-left: 2px solid #00937A\">".display($nrsbasismatches, $actief)."</td>";
                print "<td style=\"$css\">".display($nrsbenchmatches, $actief)."</td>";
                print "<td style=\"$css\">".display($nrsgoals, $actief)."</td>";
                print "<td style=\"$css\">".display($nrsassists, $actief)."</td>";
                print "<td style=\"$css\">".display($nrscards, $actief)."</td>";
            }
            else{
                print "<td style=\"$css ;border-left: 2px solid #00937A\">&nbsp;</td>"; 
            }
        print "</tr>";
 }
 print "</table>";
 print "</div>";
 
 post();
?>
