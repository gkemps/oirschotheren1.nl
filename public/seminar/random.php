<?php
define("DBHOST", "localhost");
define("DBUSER", "oirschot_Kemzy");
define("DBPASS", "17kuuS");
define("DATABASE", "oirschot_seminar");
require_once("classes/class.db.php");
$db = new Db();

$query = "SELECT text FROM documents ORDER BY RAND() LIMIT 1";
print $db->getSingle($query);

$rand = md5(rand(0, 999999999999999));
print "<a href='random.php?$rand'>refresh</a>";

?>
