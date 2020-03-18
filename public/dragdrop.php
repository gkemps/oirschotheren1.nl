<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
if(!$_SESSION['loggedin'])
    header("location: login.php");
else{

	require_once("classes/seizoen.class.php");

	require_once("classes/speler.class.php");

	require_once("classes/systeem.class.php");

	require_once("classes/wedstrijd.class.php");

	require_once("classes/positie.class.php");

	require_once("functions/functions.php");

?>



<html>

	<head>

		<title>Invoer wedstrijd statistieken</title>

		<script src="javascripts/prototype.js" type="text/javascript"></script>

		<script src="javascripts/scriptaculous.js" type="text/javascript"></script>

		<script src="javascripts/myfunctions.js" type="text/javascript"></script>

		<link href="stylesheet/style.css" type="text/css" rel="stylesheet">

	</head>

	<body>

		<?php

		



		$wedstrijd = new Wedstrijd($_REQUEST['wedid']);

		$systeem = $wedstrijd->getSystem();

		if(is_null($systeem))

			$systeem = new Systeem(6);

		$lines = array_reverse($systeem->getLines());

		$positions = $systeem->getPositions();

		if($wedstrijd->homeTeam()->getId()==2){

			$tegenstander = $wedstrijd->awayTeam()->getId();

		}

		else

			$tegenstander = $wedstrijd->homeTeam()->getId();

		print "<input type=\"hidden\" id=\"wedid\" value=\"".$wedstrijd->getId()."\">";

		print "<input type=\"hidden\" id=\"sysid\" value=\"".$systeem->getId()."\">";

		print "<input type=\"hidden\" id=\"helft\" value=\"1\">";

		print "<input type=\"hidden\" id=\"tegenstander\" value=\"$tegenstander\">";

		print "<div class=\"veld\">\r\n";

		foreach($lines as $line){

		 	print "<table width=\"100%\" border=\"0\">\r\n";

				print "<tr>";

					for($i=0;$i<$line;$i++){

					 	$width = 100 / count($line);

					 	$pos = array_pop($positions);

					 	$id = $pos->getId();

						print "<td align=\"center\" id=\"box_$id\" width=\"$width\">\r\n";

							positie($pos, $wedstrijd);

						print "</td>\r\n";

					}

				print "</tr>\r\n";

			print "</table>\r\n";

		}

		print "<script type=\"text/javascript\" language=\"javascript\">\r\n";

			print "// <![CDATA[\r\n";

			$positions = $systeem->getPositions();

			foreach($positions as $pos){

			 	$id = $pos->getId();

				print "Droppables.add('pic_$id',".

						" {onDrop: function (drag, drop){player_dropped(drag, drop)},".

						" accept: ['player_picbox', 'player_picture'], ".

						"hoverclass: 'player_picbox_hover'})\r\n";

			}

			print "// ]]>\r\n";

		print "</script>\r\n";

		print "</div>\r\n";

		

		

		?>

		

		<?php

			print "<div class=\"opstelling_right\">\r\n";

			print "<div class=\"opstelling_scores\" id=\"scores\">\r\n";

					scores($wedstrijd);

			print "</div>\r\n";

			

			print "<div class=\"events\">";

				print "<table width=\"100%\"><tr>";

				 print "<td><img src=\"images/goal.gif\" id=\"goal\" class=\"goal\"></td>";

				 print "<td><img src=\"images/groen.JPG\" id=\"groen\" class=\"kaart\"></td>";

				 print "<td><img src=\"images/geel.jpg\" id=\"geel\" class=\"kaart\"></td>";

				 print "<td><img src=\"images/rood.JPG\" id=\"rood\" class=\"kaart\"></td>";

					print "<td>";

						print "1e helft ";

						print "<input type=\"radio\" name=\"helft\" value=\"1\" ".

								" onclick=\"helft_change(this)\" Checked>";

						print "2e helft ";

						print "<input type=\"radio\" name=\"helft\" value=\"2\" ".

								" onclick=\"helft_change(this)\">";

					print "</td>";

//					$db = new DB();
//
//					$ids = $db->queryMany("SELECT id FROM speelsystemen");
//
//					print "<td>";
//
//						print "<select name=\"systeem\">";
//
//							foreach($ids as $id){
//
//								$systeem = new Systeem($id);
//
//								print "<option value=\"".$systeem->getId()."\">";
//
//								print $systeem->toString()."</option>";
//
//							}
//
//						print "</select>";
//
//					print "</td>";

				print "</tr></table>";

				print "<script type=\"text/javascript\" language=\"javascript\">\r\n";

					print "new Draggable('goal', {revert: true});";

					print "new Draggable('groen', {revert: true});";

					print "new Draggable('geel', {revert: true});";

					print "new Draggable('rood', {revert: true});";

				print "</script>\r\n";

			print "</div>";

			

			print "<div class=\"bank_players\">";

				$positie = new Positie();

				$bankposs = $positie->getBenchPositions();

				print "<table border=\"0\">\r\n";

					print "<tr>";

						$i=0;

						foreach($bankposs as $bank){

						 	$id = $bank->getId();

							print "<td align=\"center\" id=\"box_$id\">\r\n";

								positie($bank, $wedstrijd);

							print "</td>\r\n";

							if($i % 3 == 2)

						 		print "</tr><tr>";

							$i++;

						}

					print "</tr>\r\n";

				print "</table>\r\n";

				print "<script type=\"text/javascript\" language=\"javascript\">\r\n";

					print "// <![CDATA[\r\n";

					foreach($bankposs as $pos){

					 	$id = $pos->getId();

						print "Droppables.add('pic_$id',".

								" {onDrop: function (drag, drop){player_dropped(drag, drop)},".

								" accept: ['player_picbox', 'player_picture'], ".

								"hoverclass: 'player_picbox_hover'})\r\n";

					}

					print "// ]]>\r\n";

				print "</script>\r\n";

			print "</div>";

			

//			print "<div class=\"absent_players\">";
//
//				$positie = new Positie();
//
//				$absentposs = $positie->getAbsentPositions();
//
//				print "<table border=\"0\">\r\n";
//
//					print "<tr>";
//
//						$i=0;
//
//						foreach($absentposs as $absent){
//
//						 	$id = $absent->getId();
//
//							print "<td align=\"center\" id=\"box_$id\">\r\n";
//
//								positie($absent, $wedstrijd);
//
//							print "</td>\r\n";
//
//							if($i % 3 == 2)
//
//						 		print "</tr><tr>";
//
//							$i++;
//
//						}
//
//					print "</tr>\r\n";
//
//				print "</table>\r\n";
//
//				print "<script type=\"text/javascript\" language=\"javascript\">\r\n";
//
//					print "// <![CDATA[\r\n";
//
//					foreach($absentposs as $pos){
//
//					 	$id = $pos->getId();
//
//						print "Droppables.add('pic_$id',".
//
//								" {onDrop: function (drag, drop){player_dropped(drag, drop)},".
//
//								" accept: ['player_picbox', 'player_picture'], ".
//
//								"hoverclass: 'player_picbox_hover'})\r\n";
//
//					}
//
//					print "// ]]>\r\n";
//
//				print "</script>\r\n";
//
//			print "</div>";

			

		print "</div>\r\n";

			

			print "<div class=\"players\">\r\n";

			$seizoen = new Seizoen();

			$seizoen->setSeason($wedstrijd->getDate());

			$spelers = $wedstrijd->getFreePlayers();

			print "<table><tr>\r\n";

			foreach($spelers as $speler){

			 	print "<td class=\"opst_player\" id=\"nonposplayer_".$speler->getId()."\">\r\n";

			 	print "<img src=\"spelerfoto.php?id=".$speler->getId()."\" ".

				 		"class=\"player_picbox\" id=\"player_".$speler->getId()."\"><br>";

				print $speler->shortName();

				print "</td>\r\n";

			}

			print "</tr></table>\r\n";

			print "<script type=\"text/javascript\" language=\"javascript\">\r\n";

			foreach($spelers as $speler){

			 	print "new Draggable('player_".$speler->getId()."', {revert: true});";

			}

			print "</script>\r\n";

			print "</div>\r\n";

		?>

		

	</body>

</html>
<?php } ?>