<?php
include("sesija.class.php");
include("baza.class.php");


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
$email = "";
$idHotela = "";
$idSezone = "";
$db = new Baza();
$db->spojiDB();

if(isset($_GET["idSobe"]) && isset($_GET["email"])) {
    $idSobe = $_GET["idSobe"];
    $email = $_GET["email"];
    $datumRezervacije = date("Y-m-d H:i:s");
    $sql_upit_hotel = "SELECT * FROM soba WHERE ID_soba LIKE '" . $idSobe . "'";
    $hotel = $db->selectDB($sql_upit_hotel);
    while($row = $hotel->fetch_assoc()) {
        $idHotela = $row["hotel_ID_hotel"];
    }
    if($datumRezervacije > '2018-06-21 00:00:00' && $datumRezervacije < '2018-12-21 00:00:00') {
        $idSezone = 1;
    }
    else {
        $idSezone = 2;
    }
    $sql_nova_rezervacija = "INSERT INTO `rezervacijaGost`(`datum_registracije`, `sezona_ID_sezona`,`soba_ID_soba`, `hotel_ID_hotel`, `email`) VALUES('" . $datumRezervacije . "', '" . $idSezone . "', '" . $idSobe . "', '" . $idHotela . "', '" . $email . "')";
    $zapisanaRezervacija = $db->selectDB($sql_nova_rezervacija);
    $akcija = "Rezervacija";
    $opis = "Rezervirana soba " . $idSobe;
    $sql_dnevnik = "INSERT INTO `dnevnik`(`akcija`, `datumVrijeme`, `opis`) VALUES('" . $akcija . "', '" . $datumRezervacije . "', '" . $opis . "')";
    $noviZapis = $db->selectDB($sql_dnevnik);
    header("Location: index.php");
}

$db->zatvoriDB();