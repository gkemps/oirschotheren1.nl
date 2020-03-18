<?php
session_start();
//if($_POST['submit']){
//    if($_POST['password']=="fluffers" || $_POST['password'] == "\$helly"){
//        $_SESSION['loggedin'] = true;
//        header("location:invoer.php");
//    }
//}
print "<html>";

    print "<head>";

    print "<title>Oirschot Heren 1 Admin Site</title>";

    print "</head>";

    print "<body bgcolor=\"#DAFFDB\">";

    print "<center>";
    
    ?>
    <form name="login" action="login.php" method="post">
        <input type="password" name="password"></br>
        <input type="submit" value="Log in" name="submit">
    </form>
    <?php
    
    print "</center>";

    print "</body>";

print "</html>";
?>
