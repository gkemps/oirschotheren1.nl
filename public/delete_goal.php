<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
	require_once("classes/goal.class.php");
	
	if($_GET['id']){
		$goal = new Goal($_GET['id']);
		$goal->delete();
		header("location: dragdrop.php?wedid=".$_GET['wedid']);
	}
?>