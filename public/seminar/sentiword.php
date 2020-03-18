<?php
ini_set("memory_limit","64M");
define("DBHOST", "localhost");
define("DBUSER", "oirschot_Kemzy");
define("DBPASS", "17kuuS");
define("DATABASE", "oirschot_seminar");

require_once("classes/class.db.php");

$db = new Db();

$lines = file("sentiword.txt");
print "number of lines:".count($lines);
$i = 0;
$db->query("TRUNCATE TABLE  `sentiword`");
foreach($lines as $line){
    if(!(substr($line, 0, 1)=="#")){
        $columns = split("\t", $line);
        $terms = split(" ", $columns[4]);
        foreach($terms as $term){
            $term = substr($term, 0, strpos($term, "#"));
            $fields['pos'] = $columns[0];
            $fields['id'] = $columns[1];
            $fields['posscore'] = $columns[2];
            $fields['negscore'] = $columns[3];
            $fields['term'] = $term;
            $fields['gloss'] = $columns[5];
            if(!strpos($term, "_"))
                $db->insert("sentiword", $fields);
        }
    }
    $i++;
    if(false)
        die('terminated');
}

/*
$query = "SELECT * FROM sentiword";
$db->getArray($result, $query);

print_r($result);
 *
 * 
 */

?>
