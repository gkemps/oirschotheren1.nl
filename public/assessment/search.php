<?php
require_once("lib/class.XAggregator.php");

$xagg = new XAggregator();
if(!empty($_POST['adres'])){
    $xml = $xagg->search($_POST['query'], $_POST['all'], $_POST['sort'], $_POST['dir'], $_POST['adres']);
}
else{
    $xml = $xagg->search($_POST['query'], $_POST['all'], $_POST['sort'], $_POST['dir']);
}
print $xml;
?>
