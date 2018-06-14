<?php
include("../../sesija.class.php");
include("../../baza.class.php");

Sesija::kreirajSesiju();

if (isset($_SESSION["korisnik"])) {
    if ($_SESSION["uloga"] == 'Moderator') {
        header("Location: ../../mod/index.php");
    }
    if ($_SESSION["uloga"] == 'Registriran korisnik') {
        header("Location: ../../usr/index.php");
    }
}

$db = new Baza();
$db->spojiDB();

$idZahtjeva = "";
$akcija = "";

if(isset($_GET["id"]) && isset($_GET["akcija"])) {
    $idZahtjeva = $_GET["id"];
    $akcija = $_GET["akcija"];
}

$naziv = "";
$opis = "";
$url = "";
$slika = "";
$idVrste = "";
$idKorisnika = "";
$idPozicije = "";

if($akcija == '1') {
    $sql_upit_zahtjev = "SELECT * FROM zahtjev_novi WHERE id_zahtjeva LIKE '" . $idZahtjeva . "'";
    $zahtjev = $db->selectDB($sql_upit_zahtjev);
    while($row = $zahtjev->fetch_assoc()) {
        $naziv = $row["naziv"];
        $opis = $row["opis"];
        $url = $row["url"];
        $slika = $row["slika"];
        $idVrste = $row["id_vrste"];
        $idKorisnika = $row["id_korisnika"];
    }
    $sql_upit_pozicija = "SELECT * FROM vrsta_oglasa WHERE ID_vrsta_oglasa LIKE '" . $idVrste . "'";
    $pozicija = $db->selectDB($sql_upit_pozicija);
    while($row = $pozicija->fetch_assoc()) {
        $idPozicije = $row["id_pozicije"];
    }
    $sql_upit_prihvacen = "UPDATE zahtjev_novi SET status_zahtjeva = 'Prihvaćen' WHERE id_zahtjeva LIKE '" . $idZahtjeva . "'";
    $izmijenjenZahtjev = $db->selectDB($sql_upit_prihvacen);
    $sql_novi_oglas = "INSERT INTO `Oglas`(`naziv`, `opis`, `url`, `status`, `slika`, `klikovi`, `id_korisnika`, `pozicija_oglasa_ID_pozicija_oglasa`, `vrsta_oglasa_ID_vrsta_oglasa`) VALUES('" . $naziv . "', '" . $opis . "', '" . $url . "', 'Aktivan', '" . $slika . "', '0', '" . $idKorisnika . "', '" . $idPozicije . "', '" . $idVrste . "')";
    $noviOglas = $db->selectDB($sql_novi_oglas);
    $datum = date("Y-m-d H:i:s");
    $sql_dnevnik = "INSERT INTO `dnevnik`(`akcija`, `datumVrijeme`, `opis`) VALUES('Prihvaćen oglas', '" . $datum . "', 'Prihvaćen oglas pod nazivom " . $naziv . "')";
    $noviZapis = $db->selectDB($sql_dnevnik);
    header("Location: index.php");
}
if($akcija == '0') {
    $sql_odbijen_zahtjev = "UPDATE zahtjev_novi SET status_zahtjeva = 'Odbijen' WHERE id_zahtjeva LIKE '" . $idZahtjeva . "'";
    $odbijenZahtjev = $db->selectDB($sql_odbijen_zahtjev);
    $datum = date("Y-m-d H:i:s");
    $sql_dnevnik = "INSERT INTO `dnevnik`(`akcija`, `datumVrijeme`, `opis`) VALUES('Odbijen zahtjev', '" . $datum . "', 'Odbijen zahtjev pod nazivom " . $naziv . "')";
    $noviZapis = $db->selectDB($sql_dnevnik);
    header("Location: index.php");
}
$db->zatvoriDB();