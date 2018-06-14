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

$idOglasa = "";
$idKorisnika = "";
$razlog = "";

$db = new Baza();
$db->spojiDB();

$sql_upit_korisnik = "SELECT * FROM korisnik WHERE korisnicko_ime LIKE '" . $_SESSION["korisnik"] . "'";
$korisnik = $db->selectDB($sql_upit_korisnik);
while($row = $korisnik->fetch_assoc()) {
    $idKorisnika = $row["ID_korisnik"];
}

if(isset($_GET["id"]) && isset($_GET["razlog"])) {
    $idOglasa = $_GET["id"];
    $razlog = $_GET["razlog"];
    $datumZahtjeva = date("Y-m-d H:i:s");
    
    $sql_novi_zahtjev = "INSERT INTO `zahtjev_blokiranje`(`razlog`, `id_oglasa`, `id_korisnika`, `status_zahtjeva`, `datum`) VALUES('" . $razlog . "', '" . $idOglasa . "', '" . $idKorisnika . "', 'U čekanju', '" . $datumZahtjeva . "')";
    $noviZahtjev = $db->selectDB($sql_novi_zahtjev);
    $sql_dnevnik = "INSERT INTO `dnevnik`(`akcija`, `datumVrijeme`, `opis`) VALUES('Novi zahtjev', '" . $datumZahtjeva . "', 'Podnesen zahtjev za blokiranje oglasa " . $idOglasa . "')";
    $noviZapis = $db->selectDB($sql_dnevnik);
    header("Location: index.php");
} 

$db->zatvoriDB();