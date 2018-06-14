<?php
include("../sesija.class.php");
include("../baza.class.php");

Sesija::kreirajSesiju();

if (isset($_SESSION["korisnik"])) {
    if ($_SESSION["uloga"] == 'Registriran korisnik') {
        header("Location: ../usr/index.php");
    }
    if ($_SESSION["uloga"] == 'Administrator') {
        header("Location: ../admin/index.php");
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

$idOglasa = "";
$naziv = "";

if($akcija == '1') {
    $sql_upit_zahtjev = "SELECT * FROM zahtjev_blokiranje WHERE ID_zahtjev LIKE '" . $idZahtjeva . "'";
    $zahtjev = $db->selectDB($sql_upit_zahtjev);
    while($row = $zahtjev->fetch_assoc()) {
        $idOglasa = $row["id_oglasa"];
        $naziv = $row["naziv"];
    }
    $sql_upit_prihvacen = "UPDATE zahtjev_blokiranje SET status_zahtjeva = 'Prihvaćen' WHERE ID_zahtjev LIKE '" . $idZahtjeva . "'";
    $izmijenjenZahtjev = $db->selectDB($sql_upit_prihvacen);
    $sql_blokiran_oglas = "UPDATE Oglas SET status = 'Blokiran' WHERE oglas_ID LIKE '" . $idOglasa . "'";
    $blokiranOglas = $db->selectDB($sql_blokiran_oglas);
    $datum = date("Y-m-d H:i:s");
    $sql_dnevnik = "INSERT INTO `dnevnik`(`akcija`, `datumVrijeme`, `opis`) VALUES('Blokiran oglas', '" . $datum . "', 'Blokiran oglas pod nazivom " . $naziv . "')";
    $noviZapis = $db->selectDB($sql_dnevnik);
    header("Location: index.php");
}
if($akcija == '0') {
    $sql_odbijen_zahtjev = "UPDATE zahtjev_blokiranje SET status_zahtjeva = 'Odbijen' WHERE ID_zahtjev LIKE '" . $idZahtjeva . "'";
    $odbijenZahtjev = $db->selectDB($sql_odbijen_zahtjev);
    $datum = date("Y-m-d H:i:s");
    $sql_dnevnik = "INSERT INTO `dnevnik`(`akcija`, `datumVrijeme`, `opis`) VALUES('Odbijen zahtjev', '" . $datum . "', 'Odbijen zahtjev broj " . $idZahtjeva . "')";
    $noviZapis = $db->selectDB($sql_dnevnik);
    header("Location: index.php");
}
$db->zatvoriDB();