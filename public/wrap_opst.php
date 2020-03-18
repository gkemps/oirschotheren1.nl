<?php
	require_once("classes/db.class.php");
	
	$items = $db->query("SELECT * FROM opstelling_nieuw ORDER BY wedid ASC");
	$wed = array();
	foreach($items as $item){
		if($item['posid']==20){
			if(isset($wed[$item['wedid']])){
				$keys['id'] = $item['id'];
				$fields['posid'] = ++$wed[$item['wedid']];
				$db->update("opstelling_nieuw", $fields, $keys);
			}
			else{
				$keys['id'] = $item['id'];
				$fields['posid'] = 20;
				$wed[$item['wedid']] = 20;
				$db->update("opstelling_nieuw", $fields, $keys);
			}
			print_r($item);
			print "=> ".$wed[$item['wedid']];
			print "<br>";
		}
	}
?>