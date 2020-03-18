<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once ("classes/speler.class.php");

if($_GET['id']){
	$speler = new Speler($_GET['id']);
	$speler->showPicture();
}

?>