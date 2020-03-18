<?php
	require_once("classes/kaart.class.php");
	
	if($_GET['id']){
		$card = new Kaart($_GET['id']);
		$card->delete();
		header("location: dragdrop.php?wedid=".$_GET['wedid']);
	}
?>