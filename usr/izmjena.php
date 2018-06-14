<?php
include("../baza.class.php");
include("../sesija.class.php");

Sesija::kreirajSesiju();

if (isset($_SESSION["korisnik"])) {
    if ($_SESSION["uloga"] == 'Moderator') {
        header("Location: ../mod/index.php");
    }
    if ($_SESSION["uloga"] == 'Administrator') {
        header("Location: ../admin/index.php");
    }
}

$idZahtjeva = "";

$db = new Baza();
$db->spojiDB();
$kontrola = 0;

if (isset($_GET["id"])) {
    $idZahtjeva = $_GET["id"];
    $kontrola = 1;
}

$naziv = "";
$opis = "";
$url = "";
$datum = "";
$name = "";

$sql_upit_zahtjev = "SELECT * FROM zahtjev_novi WHERE id_zahtjeva LIKE '" . $idZahtjeva . "'";
$zahtjev = $db->selectDB($sql_upit_zahtjev);
while ($row = $zahtjev->fetch_assoc()) {
    $naziv = $row["naziv"];
    $opis = $row["opis"];
    $url = $row["url"];
    $datum = $row["datum"];
}

if (isset($_POST["submit"])) {
    $idZahtjeva = $_POST["sifraZahtjeva"];
    $naziv = $_POST["naziv"];
    $opis = $_POST["opis"];
    $url = $_POST["url"];
    $datum = $_POST["datum"];
    $datumIzmjene = date("Y-m-d H:i:s");


    $sql_izmjena_zahtjeva = "UPDATE zahtjev_novi SET naziv='" . $naziv . "', opis='" . $opis . "', url='" . $url . "', datum='" . $datum . "' WHERE id_zahtjeva LIKE '" . $idZahtjeva . "'";
    $noviZahtjev = $db->selectDB($sql_izmjena_zahtjeva);
    $sql_dnevnik = "INSERT INTO `dnevnik`(`akcija`, `datumVrijeme`, `opis`) VALUES('Izmjena zahtjeva', '" . $datumIzmjene . "', 'Izmjena podataka zahtjeva " . $idZahtjeva . "')";
    $noviZapis = $db->selectDB($sql_dnevnik);
    header("Location: vasiZahtjevi.php");
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
        <link rel="stylesheet" type="text/css" href="../css/style.css">
        <title>Izmjena</title>
    </head>
    <body>
        <header>
            <a id="odjava" href="odjava.php">Odjava</a>
            <section>
                <label id="toggle" for="toggle-1" class="toggle-menu"><ul><li></li> <li></li> <li></li></ul></label>
                <input type="checkbox" id="toggle-1">

                <nav>
                    <ul>
                        <li><a href="index.php">Naslovna</a></li>
                        <li><a href="oglasi.php">Oglasi</a></li>
                        <li><a href="vrste.php">Novi oglas</a></li>
                        <li><a href="vasiZahtjevi.php">Vaši zahtjevi</a></li>
                        <li><a href="vasiOglasi.php">Vaši oglasi</a></li>
                    </ul>
                </nav>
        </header>
        <form class="form-login" id="noviOglas" method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>" enctype="multipart/form-data">
            <div class="form-log-in-with-email">
                <div class="form-white-background">
                    <div class="form-row">
                        <input type="hidden" id="sifraZahtjeva" name="sifraZahtjeva" value="<?php echo$idZahtjeva; ?>"
                    </div>
                    <div class="form-row">
                        <input id="naziv" type="text" name="naziv" placeholder="Naziv oglasa" value="<?php echo($naziv); ?>"/>
                    </div>
                    <div class="form-row">
                        <input id="opis" type="text" name="opis" placeholder="Opis oglasa" value="<?php echo($opis); ?>"/>
                    </div>
                    <div class="form-row">
                        <input id="url" type="text" name="url" placeholder="URL" value="<?php echo($url); ?>"/>
                    </div>
                    <div class="form-row">
                        <input id="datum" type="date" name="datum" value="<?php echo($datum); ?>"/>
                    </div>
                    <div class="form-row">
                        <input id="submit" class="button" type="submit" name="submit" value="POŠALJI ZAHTJEV"/>
                    </div>
                </div>

            </div>
        </form>

    </body>
</html>