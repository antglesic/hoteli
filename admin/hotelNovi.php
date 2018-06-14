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

$sql_upit_moderatori = "SELECT korisnik.ID_korisnik, korisnik.ime, korisnik.prezime FROM korisnik WHERE tip_korisnika_ID_tip_korisnika LIKE '3'";
$moderatori = $db->selectDB($sql_upit_moderatori);
$selected1 = "<select id='mod' name='moderator'>";
while ($row = mysqli_fetch_array($moderatori)) {
    $prikaz = $row['ime'] . " " . $row["prezime"];
    $selected1 .= "<option value='" . $row['ID_korisnik'] . "'>" . $prikaz . "</option>";
}
$selected1 .= "</select>";

$naziv = "";
$adresa = "";
$broj_zvjezdica = "";
$kapacitet = "";
$godinaIzgradnje = "";
$idModeratora = "";
$idHotela = "";

if(isset($_POST["submit"])) {
    $naziv = $_POST["naziv"];
    $adresa = $_POST["adresa"];
    $broj_zvjezdica = $_POST["zvjezdice"];
    $kapacitet = $_POST["kapacitet"];
    $godinaIzgradnje = $_POST["godinaIzgradnje"];
    $idModeratora = $_POST["moderator"];
    
    $sql_novi_hotel = "INSERT INTO `hotel`(`naziv`, `adresa`, `broj_zvjezdica`, `kapacitet`, `godina_izgradnje`) VALUES('" . $naziv . "', '" . $adresa . "', '" . $broj_zvjezdica . "', '" . $kapacitet . "', '" . $godinaIzgradnje . "')";
    $unosHotela = $db->selectDB($sql_novi_hotel);
    $sql_upit_hotela = "SELECT * FROM hotel WHERE naziv LIKE '" . $naziv . "'";
    $hotel = $db->selectDB($sql_upit_hotela);
    while($row = $hotel->fetch_assoc()) {
        $idHotela = $row["ID_hotel"];
    }
    
    $sql_moderator_hotela = "INSERT INTO `moderator`(`hotel_ID_hotel`, `korisnik_ID_korisnik`) VALUES('" . $idHotela . "', '" . $idModeratora . "')";
    $unosModeratora = $db->selectDB($sql_moderator_hotela);
    
    $datum = date("Y-m-d H:i:s");
    $sql_dnevnik = "INSERT INTO `dnevnik`(`akcija`, `datumVrijeme`, `opis`) VALUES('Novi hotel', '" . $datum . "', 'Dodan novi hotel " . $naziv . "')";
    $noviZapis = $db->selectDB($sql_dnevnik);
    header("Location: indexAdmin.php");
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
        <title>Novi hotel</title>
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
                        <li><a href="moderatorNovi.php">Novi moderator</a></li>
                        <li><a href="statistikaKlikovi.php">Klikovi</a></li>
                        <li><a href="statistikaPlacenih.php">Plaćeni</a></li>
                        <li><a href="statistikaKorisnici.php">Top korisnici</a></li>
                        <li><a href="otkljucavanjeRacuna.php">Računi</a></li>
                        <li><a href="dnevnik.php">Dnevnik</a></li>
                    </ul>
                </nav>
        </header>
        <form class="form-login" id="noviHotel" method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
            <div class="form-log-in-with-email">
                <div class="form-white-background">
                    <div class="form-title-row">
                        <h1>Novi hotel</h1>
                    </div>
                    <div class="form-row">
                        <input id="naziv" type="text" name="naziv" placeholder="Ime hotela"/>
                    </div>
                    <div class="form-row">
                        <input id="adresa" type="text" name="adresa" placeholder="Adresa"/>
                    </div>
                    <div class="form-row">
                        <h4>Broj zvjezdica:</h4>
                        <input id="zvjezdice" type="number" name="zvjezdice"/>
                    </div>
                    <div class="form-row">
                        <h4>Kapacitet:</h4>
                        <input id="kapacitet" type="number" name="kapacitet"/>
                    </div>
                    <div class="form-row">
                        <h4>Godina izgradnje:</h4>
                        <input id="godinaIzgradnje" type="number" name="godinaIzgradnje"/>
                    </div>
                    <div class="form-row">
                        <h4>Moderator:</h4>
                        <?php echo$selected1; ?>
                    </div>
                    <div class="form-row">
                        <input id="submit" class="button" type="submit" name="submit" value="KREIRAJ"/>
                    </div>
                    <a href="dodavanjeModeratora.php" class="form-create-an-account">Već kreiran hotel? Dodaj moderatora &rarr;</a>
                </div>

            </div>
        </form>

    </body>
</html>