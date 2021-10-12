<?php
	require_once("functions.php");
	require_once("classes/db.class.php");
	require_once("classes/nieuws.class.php");
	require_once("classes/stand.class.php");
	require_once("classes/gastenboek.class.php");
	
	function head(){
		echo <<<END
		<html>
		<head>
			<title>Oirschot Heren 1</title>
				<link href="stylesheet/style.css" type="text/css" rel="stylesheet"> 
		<link href="stylesheet/accordion.css" type="text/css" rel="stylesheet">
		<link href="stylesheet/modalbox.css" type="text/css" rel="stylesheet">
		<link href="stylesheet/tabs.css" type="text/css" rel="stylesheet">
		
		<script type="text/javascript" src="javascripts/yahoo_2.0.0-b2.js"></script>
		<script type="text/javascript" src="javascripts/event_2.0.0-b2.js" ></script>
		<script type="text/javascript" src="javascripts/dom_2.0.2-b3.js"></script>
		<script type="text/javascript" src="javascripts/animation_2.0.0-b3.js"></script>
		<script type="text/javascript" src="javascripts/menu.js"></script> 
		<script type="text/javascript" src="javascripts/prototype.js"></script>
		<script type="text/javascript" src="javascripts/scriptaculous.js"></script>
		<script type="text/javascript" src="javascripts/accordion.js"></script>
		<script type="text/javascript" src="javascripts/modalbox.js"></script>
		<script type="text/javascript" src="javascripts/tabs.js"></script>	
        <script type="text/javascript" src="javascripts/swfobject.js"></script> 
        <script type="text/javascript" src="javascripts/myfunctions.js"></script> 
		</head>
		<body>	
END;
	}
	
	
	function close(){
		echo <<<END
			</body>
		</html>
END;
	}
	
	function pre(){
		echo <<<END
	<html>
	<head>
		<title>Oirschot Heren 1</title>
		<link href="stylesheet/style.css" type="text/css" rel="stylesheet"> 
		<link href="stylesheet/accordion.css" type="text/css" rel="stylesheet">
		<link href="stylesheet/modalbox.css" type="text/css" rel="stylesheet">
		<link href="stylesheet/tabs.css" type="text/css" rel="stylesheet">
		<script type="text/javascript" src="javascripts/yahoo_2.0.0-b2.js"></script>
		<script type="text/javascript" src="javascripts/event_2.0.0-b2.js" ></script>
		<script type="text/javascript" src="javascripts/dom_2.0.2-b3.js"></script>
		<script type="text/javascript" src="javascripts/animation_2.0.0-b3.js"></script>
		<script type="text/javascript" src="javascripts/menu.js"></script> 
		<script type="text/javascript" src="javascripts/myfunctions.js"></script>
		<script type="text/javascript" src="javascripts/prototype.js"></script>
		<script type="text/javascript" src="javascripts/scriptaculous.js"></script>
		<script type="text/javascript" src="javascripts/accordion.js"></script>
		<script type="text/javascript" src="javascripts/modalbox.js"></script>
		<script type="text/javascript" src="javascripts/tabs.js"></script>
        <script type="text/javascript" src="javascripts/swfobject.js"></script>
	</head>
	<body>
		<center>
			<div class="totaldiv">
				<div class="left">
					<div>
						<img src="images/header.jpg">
					</div>
					
				    <div class="menu" id="hockeymenu">
				        <a href="#" class="amenu" rel="spelers">Spelers</a>
				        <a href="#" class="amenu" rel="teams">Teams</a>
				        <a href="#" class="amenu" rel="milestones">Milestones</a>
				        <a href="#" class="amenu" rel="seizoen">Dit seizoen</a>
				        <a href="#" class="amenu" rel="gastenboek">&nbsp</a>
				        <a href="#" class="amenu" rel="multimedia">&nbsp</a>
				        <a href="#" class="amenu" rel="home">&nbsp</a>
				    </div>
					
					<div id="spelers" class="dropmenudiv">
					    <a href="spelers_oat.php" class="dropdown">
					        Alle spelers</a>
                        <a href="spelers_table.php" class="dropdown">
					        Spelers tabel</a>
					</div>
					
					<div id="teams" class="dropmenudiv">
					    <a href="teams_oat.php" class="dropdown">
					        Alle teams</a>
					</div>
					
					<div id="milestones" class="dropmenudiv">
					    <a href="debuut.php" class="dropdown">
					        Debuut</a>
					    <a href="debuut_goal.php" class="dropdown">
					        Eerste goal</a>
					     <a href="debuut_assist.php" class="dropdown">
					        Eerste assist</a>  
					</div>
					
					<div id="seizoen" class="dropmenudiv">
					    <a href="svz.php" class="dropdown">
					        Stand van zaken</a>
						<a href="rangen_standen.php" class="dropdown">
					        Topscoorders</a>
					</div>
					
					
					
					<script type="text/javascript">
					    menudropdown.startmenu('hockeymenu');
					</script>
					
					<div class="contentdiv">	
END;
	}
	
	function pre_ff(){
		echo <<<END
	<html>
	<head>
		<title>Oirschot Heren 1 - Nieuwe layout</title>
		<link href="stylesheet/style.css" type="text/css" rel="stylesheet"> 
		<link href="stylesheet/accordion.css" type="text/css" rel="stylesheet">
		<link href="stylesheet/gwidgets.css" type="text/css" rel="stylesheet">
		<!--Yahoo! User Interface Library : http://developer.yahoo.com/yui/index.html-->
		<script type="text/javascript" src="javascripts/yahoo_2.0.0-b2.js"></script>
		<script type="text/javascript" src="javascripts/vent_2.0.0-b2.js" ></script>
		<script type="text/javascript" src="javascripts/dom_2.0.2-b3.js"></script>
		<script type="text/javascript" src="javascripts/animation_2.0.0-b3.js"></script>
		<script language="javascript" src="javascripts/menu.js"></script> 
		<script language="javascript" src="javascripts/myfunctions.js"></script>
		<script language="javascript" src="javascripts/prototype.js"></script>
		<script language="javascript" src="javascripts/scriptaculous.js"></script>
		<script language="javascript" src="javascripts/base.js"></script>
		<script language="javascript" src="javascripts/gwidgets.js"></script>
	</head>
	<body>
		<center>
			<div class="totaldiv">
				<div class="left">
					<div>
						<img src="images/header.jpg">
					</div>
					
				    <div class="menu" id="hockeymenu">
				        <a href="#" class="amenu" rel="home">Home</a>
				        <a href="#" class="amenu" rel="spelers">Spelers</a>
				        <a href="#" class="amenu" rel="teams">Teams</a>
				        <a href="#" class="amenu" rel="competitie">Competitie</a>
				        <a href="#" class="amenu" rel="statistiek">Statistiek</a>
				        <a href="#" class="amenu" rel="gastenboek">Gastenboek</a>
				        <a href="#" class="amenu" rel="multimedia">3e helft</a>
				    </div>
					    
				    <div id="home" class="dropmenudiv">
					    <a href="index.php" class="dropdown">
					        Laatste nieuws</a>
					    <a href="archief.php" class="dropdown">
					        Archief</a>
					</div>
					
					<div id="spelers" class="dropmenudiv">
					    <a href="spelers_seizoen.php" class="dropdown">
					        Dit seizoen</a>
					    <a href="spelers_oat.php" class="dropdown">
					        Alle spelers</a>
					</div>
					
					<div id="teams" class="dropmenudiv">
					    <a href="teams_seizoen.php" class="dropdown">
					        Dit seizoen</a>
					    <a href="teams_oat.php" class="dropdown">
					        Alle teams</a>
					</div>
					
					<div id="competitie" class="dropmenudiv">
					    <a href="svz.php" class="dropdown">
					        Stand van zaken</a>
					    <a href="" class="dropdown">
					        Programma</a>
					    <a href="" class="dropdown">
					        Gespeeld</a>
					     <a href="rangen_standen.php" class="dropdown">
					        Rangen & Standen</a>  
					</div>
					
					<div id="statistiek" class="dropmenudiv">
					    <a href="" class="dropdown">
					        Goals</a>
					    <a href="" class="dropdown">
					        Assists</a>
					</div>
					
					<div id="gastenboek" class="dropmenudiv">
					    <a href="gastenboek_tekenen.php" class="dropdown">
					        Tekenen</a>
					    <a href="gastenboek_bekijken.php" class="dropdown">
					        Bekijken</a>
					</div>
					
					<div id="multimedia" class="dropmenudiv">
					    <a href="http://svgewis.win.tue.nl/~geert/heren1fotos/fotoarchief.php" target="new" 
								title="fotos" class="dropdown">
					        Foto's</a>
					    <a href="" title="videos" class="dropdown">
					        Video's</a>
					    <a href="3ehelft.php" title="videos" class="dropdown">
					        Verslagen</a>
					</div>
					
					<script type="text/javascript">
					    menudropdown.startmenu('hockeymenu');
					</script>
					
					<div class="contentdiv">	
END;
	}
	
	function post(){
		echo <<<END
					</div>
				</div>
				<div class="right">
					<div class="firstbar">
						<img src="images/firstbar.jpg" class="sidebarpic">
					</div>
					<div class="secondbar">
					</div>
				</div>
			</div>
		</center>
	</body>
</html>
END;
	}

?>
