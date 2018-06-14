<?php
include("baza.class.php");
include("sesija.class.php");

Sesija::kreirajSesiju();

if (isset($_SESSION["korisnik"])) {
    if ($_SESSION["uloga"] == 'Registriran korisnik') {
        header("Location: usr/index.php");
    }
    if ($_SESSION["uloga"] == 'Moderator') {
        header("Location: mod/index.php");
    }
    if ($_SESSION["uloga"] == 'Administrator') {
        header("Location: admin/index.php");
    }
}

$idSobe = "";
$brojSobe = "";
$opisSobe = "";
$slika = "";
$img = "";
$db = new Baza();
$db->spojiDB();

if(isset($_GET["id"])) {
    $idSobe = $_GET["id"];
    $sql_upit_sobe = "SELECT soba.broj_sobe, soba.slika, tip_sobe.opis_sobe FROM soba, tip_sobe WHERE soba.ID_soba LIKE '" . $idSobe . "' AND soba.tip_sobe_ID_tip_sobe LIKE tip_sobe.ID_tip_sobe";
    $soba = $db->selectDB($sql_upit_sobe);
    while($row = $soba->fetch_assoc()) {
        $brojSobe = $row["broj_sobe"];
        $opisSobe = $row["opis_sobe"];
        $slika = $row["slika"];
    }
    $img = "<img id='slika' src='slike/" . $slika . "' height='251' width='447'>";
}

if(isset($_POST["submit"])) {
    header("Location: rezerviraj.php?idSobe=" . $_POST["sifraSobe"] . "&email=" . $_POST["email"]);
}


$db->zatvoriDB();
?>

<!DOCTYPE html>
<html lang="hr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="naslov" content="Početna stranica" />
        <meta name="kljucne_rijeci" content="projekt, početna" />
        <meta name="datum_izrade" content="29.05.2018." />
        <meta name="autor" content="Matija Jezerinac" />
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <title>Soba</title>
    </head>
    <body>
        <header>
            <section>
                <label id="toggle" for="toggle-1" class="toggle-menu"><ul><li></li> <li></li> <li></li></ul></label>
                <input type="checkbox" id="toggle-1">

                <nav>
                    <ul>
                        <li><a href="index.php">Naslovna</a></li>
                        <li><a href="https://barka.foi.hr/WebDiP/2017_projekti/WebDiP2017x066/prijava.php">Prijava</a></li>
                        <li><a href="registracija.php">Registracija</a></li>
                        <li><a href="hoteli.php">Hoteli</a></li>
                        <li><a href="sobe.php">Sobe</a></li>
                    </ul>
                </nav>
        </header>
        <form class="form-login" id="prijava" method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
            <div class="form-log-in-with-email">
                <div class="form-white-background">
                    <div class="form-row">
                        <input type="hidden" id="sifraSobe" name="sifraSobe" value="<?php echo$idSobe; ?>"
                    </div>
                    <div class="form-title-row">
                        <h1>SOBA <?php echo$brojSobe ?></h1>
                    </div>
                    <div class="form-row">
                        <?php echo$img; ?>
                    </div>
                    <div class="form-row">
                        <p><?php echo$opisSobe ?></p>
                    </div>
                    <div class="form-row">
                        <input id="email" type="text" name="email" placeholder="Email"/>
                    </div>
                    <div class="form-row">
                        <input id="submit" class="button" type="submit" name="submit" value="REZERVIRAJ"/>
                    </div>
                </div>

            </div>
        </form>

    </body>
</html>