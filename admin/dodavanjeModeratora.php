<?php
include("../sesija.class.php");
include("../baza.class.php");

Sesija::kreirajSesiju();

if (isset($_SESSION["korisnik"])) {
    if ($_SESSION["uloga"] == 'Registriran korisnik') {
        header("Location: ../usr/index.php");
    }
    if ($_SESSION["uloga"] == 'Moderator') {
        header("Location: ../mod/index.php");
    }
}

$db = new Baza();
$db->spojiDB();

$sql_upit_hoteli = "SELECT * FROM hotel";
$hoteli = $db->selectDB($sql_upit_hoteli);
$selected1 = "<select id='hotel' name='hotel'>";
while ($row = mysqli_fetch_array($hoteli)) {
    $prikaz = $row['naziv'];
    $selected1 .= "<option value='" . $row['ID_hotel'] . "'>" . $prikaz . "</option>";
}
$selected1 .= "</select>";

$sql_upit_moderatori = "SELECT korisnik.ID_korisnik, korisnik.ime, korisnik.prezime FROM korisnik WHERE tip_korisnika_ID_tip_korisnika LIKE '3'";
$moderatori = $db->selectDB($sql_upit_moderatori);
$selected2 = "<select id='mod' name='moderator'>";
while ($row = mysqli_fetch_array($moderatori)) {
    $prikaz = $row['ime'] . " " . $row["prezime"];
    $selected2 .= "<option value='" . $row['ID_korisnik'] . "'>" . $prikaz . "</option>";
}
$selected2 .= "</select>";

$idHotela = "";
$idModeratora = "";
$kontrola = 0;

if (isset($_POST["submit"])) {
    $idHotela = $_POST["hotel"];
    $idModeratora = $_POST["moderator"];

    $sql_upit_moderacija = "SELECT * FROM moderator WHERE hotel_ID_hotel LIKE '" . $idHotela . "' AND korisnik_ID_korisnik LIKE '" . $idModeratora . "'";
    $moderacija = $db->selectDB($sql_upit_moderacija);
    if (mysqli_num_rows($moderacija) > 0) {
        $kontrola = 1;
    } else {
        $sql_moderator_hotela = "INSERT INTO `moderator`(`hotel_ID_hotel`, `korisnik_ID_korisnik`) VALUES('" . $idHotela . "', '" . $idModeratora . "')";
        $unosModeratora = $db->selectDB($sql_moderator_hotela);
        $datum = date("Y-m-d H:i:s");
        $sql_dnevnik = "INSERT INTO `dnevnik`(`akcija`, `datumVrijeme`, `opis`) VALUES('Dodan moderator', '" . $datum . "', 'Dodan novi moderator hotelu " . $idHotela . "')";
        $noviZapis = $db->selectDB($sql_dnevnik);
        header("Location: indexAdmin.php");
    }
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
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/s/dt/jq-2.1.4,dt-1.10.10/datatables.min.css"/>
        <script type="text/javascript" src="https://cdn.datatables.net/s/dt/jq-2.1.4,dt-1.10.10/datatables.min.js"></script>
        <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
        <title>Dodavanje moderatora</title>
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
                        <li><a href="hotelNovi.php">Novi hotel</a></li>
                        <li><a href="pozicijaNova.php">Nova pozicija</a></li>
                        <li><a href="statistikaKlikovi.php">Klikovi</a></li>
                        <li><a href="statistikaPlacenih.php">Plaćeni</a></li>
                        <li><a href="statistikaKorisnici.php">Top korisnici</a></li>
                    </ul>
                </nav>
        </header>
        <div id="greske">
            <?php if($kontrola === 1) echo("<h4>Moderator već dodijeljen tom hotelu</h4><br>"); ?>
        </div>
        <form class="form-login" id="noviHotel" method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
            <div class="form-log-in-with-email">
                <div class="form-white-background">
                    <div class="form-title-row">
                        <h1>Dodjela moderatora</h1>
                    </div>
                    <div class="form-row">
                        <h4>Hotel:</h4>
                        <?php echo$selected1; ?>
                    </div>
                    <div class="form-row">
                        <h4>Moderator:</h4>
                        <?php echo$selected2; ?>
                    </div>
                    <div class="form-row">
                        <input id="submit" class="button" type="submit" name="submit" value="Dodaj"/>
                    </div>
                </div>
            </div>
        </form>
    </body>
</html>