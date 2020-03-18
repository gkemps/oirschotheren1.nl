<?php
session_start();
require_once("classes/wedstrijd.class.php");
require_once("classes/verslag.class.php");
require_once("classes/nieuws.class.php");

$wedid = $_REQUEST['wedid'];
$wed = new Wedstrijd($wedid);

$verslag = new Verslag();
$text = $verslag->getVerslag($wedid);
if($_POST['submit']){ 
    $new_text = $_POST['verslag'];
    if(is_null($text))
        $verslag->setVerslag($new_text, $wedid);
    else
        $verslag->updateVerslag($new_text, $wedid);
    print "<b>Verslag opgeslagen</b>";
    $text = $verslag->getVerslag($wedid);
}
elseif($_POST['nieuws']){
     $titel = $_POST['titel'] ;
     $inhoud =  $_POST['verslag'];
     $nieuws = new Nieuws();
     $nieuws->insert($wed->getDate(), $titel, $inhoud);
     print "<b>succesvol ge-exporteerd naar nieuws!</b>";       
}
print "<html>";

    print "<head>";

    print "<title>Oirschot Heren 1 Admin Site</title>";

    print "</head>";

    print "<body bgcolor=\"#DAFFDB\">";

    print "<center>";
    print "<h3>Verslag Invoer</h3>";
    print $wed->toString();
    
    ?>
    <form name="login" action="verslag.php" method="post">
        <textarea name="verslag" rows="25" cols="60"><?php print $text; ?></textarea><br />
        <input type="hidden" value="<?php print $wedid;?>" name="wedid">
        <input type="submit" value="Opslaan" name="submit">
        <br />
        <input type="textbox" name="titel" size="50">
        <input type="submit" name="nieuws" value="Exporteer naar nieuws"> 
    </form>
    <?php
    
    print "<a href=\"invoer.php\">Invoer</a>";
    print "</center>";

    print "</body>";

print "</html>";
?>
