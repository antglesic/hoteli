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

$db = new Baza();
$db->spojiDB();

$sql_upit = "SELECT * FROM korisnik";
$rez = $db->selectDB($sql_upit);

$korisnici = array();
while($row = $rez->fetch_assoc()) {
    array_push($korisnici, $row["korisnicko_ime"]);
}
header('Content-Type: application:/json');
echo json_encode($korisnici);

$db->zatvoriDB();