<?php
	$wedstrijd = new Wedstrijd($_GET['id']);
	print_r($wedstrijd);
	if(is_null($wedstrijd->getVerslag()))
		print "Geen verslag beschikbaar ...";
	else
		print $wedstrijd->getVerslag()->getVerslag();

?>