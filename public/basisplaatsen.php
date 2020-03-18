<?php
require_once("classes/db.class.php");

$query = "SELECT * FROM programma WHERE gespeeld = 'ja' ORDER BY datum ASC";
$result = mysql_query($query);

$spelers = array();
$basis_plaatsen = array();
$max_plaatsen = array();
$start_datum = array();
$eind_datum = array();
$laatste_eind_datum = array();
while($row = mysql_fetch_array($result)){
    $query = "SELECT * FROM opstelling_nieuw WHERE wedid = '".$row['id']."'";
    $sub_result = mysql_query($query);
    while($opt = mysql_fetch_array($sub_result)){
        //in de basis
        if($opt['posid']<20){
            $basis_plaatsen[$opt['spelerid']]++;
            if($basis_plaatsen[$opt['spelerid']] > $max_plaatsen[$opt['spelerid']]){
                $max_plaatsen[$opt['spelerid']] = $basis_plaatsen[$opt['spelerid']];
                $eind_datum[$opt['spelerid']] = $row['Datum'];
            }
        }
        //niet in de basis
        else{
            //nieuw maximum?
            if($basis_plaatsen[$opt['spelerid']] > $max_plaatsen[$opt['spelerid']]){
                $max_plaatsen[$opt['spelerid']] = $basis_plaatsen[$opt['spelerid']];
                $eind_datum[$opt['spelerid']] = $row['Datum'];
            }
            $laatste_eind_datum[$opt['spelerid']] = $row['Datum'];
            $basis_plaatsen[$opt['spelerid']] = 0;
        }
    }
}

print "<table>";
for($i=0;$i<100;$i++){
    $query = "SELECT * FROM spelers WHERE id = $i";
    $result = mysql_fetch_array(mysql_query($query));
    print "<tr>";
        print "<td>".$result['Voornaam']." ".$result['Achternaam']."</td>";
        print "<td>".$max_plaatsen[$i]."</td>";
        print "<td>".$eind_datum[$i]."</td>";
        print "<td>".$laatste_eind_datum[$i]."</td>";
    print "</tr>";
}
print "</table>";

?>
