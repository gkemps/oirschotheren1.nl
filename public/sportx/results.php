<?php
  require_once("functies.php");
    print last_vote(); 
  $data = nominaties();
  print "<table>";
  foreach($data as $entry){
     if($entry['aantal']>1) {
          print "<tr>";
            print "<td>";
                print $entry['voornaam']." ".$entry['achternaam'];
            print "</td>";
            print "<td>";
                print $entry['aantal'];
            print "</td>";
          print "</tr>";
      }
  }
  print "</table>";
  
  $data = nominaties_ploegen();
  print "<table>";
  foreach($data as $entry){
     if($entry['aantal']>1) {
          print "<tr>";
            print "<td>";
                print $entry['ploeg'];
            print "</td>";
            print "<td>";
                print $entry['aantal'];
            print "</td>";
          print "</tr>";
      }
  }
  print "</table>";
?>
