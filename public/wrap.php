<?php
include ("classes/db.class.php");

$db = new DB();
$results = $db->query("SELECT * FROM deelnemingteams");

foreach($results as $result){
	$seizoen = $result['seizoen'];
	$start = substr($seizoen, 0, 4)."-08-01";
	$end = substr($seizoen, 5, 4)."-07-31";
	$seizoenid = $db->queryOne("SELECT id FROM seizoenen WHERE start = '".$start."' AND ".
					" eind = '".$end."'");
	$fields['seizoen'] = $seizoenid;
	$keys['id'] = $result['id'];
	$db->update("deelnemingteams", $fields, $keys);
	print $seizoenid."<br>";
}

?>