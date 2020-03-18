<?php
include("db.class.php");
$db = new DB();
// Leest een bestand in een array. In dit voorbeeld gaan we via HTTP om
// de HTML code van een URL te krijgen.
$lines = file ('ledenbestand.txt');
$velden = explode(";", $lines[0]);
print_r($velden);
unset($lines[0]);
// Loop door onze array, en laat de HTML code zien als HTML code; ook de regel nummers.
foreach ($lines as $line_num => $line) {
  $array = explode(";", $line);
  $i = 0;
  foreach($array as $waarde){
	$rij[$velden[$i]] = $waarde;
	$i++;
    if($i>21)
        break;
  }
  $db->insert("leden", $rij);
}
?> 