<?php
include("../baza.class.php");
include("../sesija.class.php");

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

$sql_upit_oglasi = "SELECT * FROM Oglas WHERE status LIKE 'Aktivan'";
$aktivniOglasi = $db->selectDB($sql_upit_oglasi);

$oglasi = array();

while($redak = $aktivniOglasi->fetch_assoc()) {
    array_push($oglasi, $redak);
}

header('Content-Type: application:/json');
echo json_encode($oglasi);


$db->zatvoriDB();