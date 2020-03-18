<?php
require_once ("classes/team.class.php");

if($_GET['id']){
	$team = new Team($_GET['id']);
	$team->showPicture();
}

?>