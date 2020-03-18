<?php
	require_once("classes/db.class.php");
	require_once("classes/gastenboek.class.php");
	print "<?xml version=\"1.0\" ?>\r\n";
	print "<rss version=\"2.0\">\r\n";
	
	print "<channel>\r\n";
	print "<title>Heren 1 Gastenboek RSS feed</title>\r\n";
	print "<description>Heren 1 Gastenbeok RSS feed</description>\r\n";
	print "<link>";
			print "http://www.oirschotheren1.nl/gastenboek_bekijken.php";
	print "</link>\r\n";
	$gastenboek = new Gastenboek();
	$entries = $gastenboek->getEntries(10, 0);
	foreach($entries as $entry){
	 	$values = $entry->getValues();
	 	print "<item>\r\n";
		print "<title>";
			print strtoupper($values['naam']);
			print " - ".substr(strip_tags($values['bericht']), 0, 20)."...";
		print "</title>\r\n";
		print "<description>";
			print strtoupper($values['naam']);
			print " - ".strip_tags($values['bericht']);
		print "</description>\r\n";
		print "<link>";
			print "http://www.oirschotheren1.nl/gastenboek_bekijken.php";
		print "</link>\r\n";
		print "</item>\r\n";
	}
	print "</channel>\r\n";
	print "</rss>\r\n";
?>