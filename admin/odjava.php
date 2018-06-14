<?php
include("../sesija.class.php");
include("../baza.class.php");

Sesija::kreirajSesiju();

if (isset($_SESSION["korisnik"])) {
    if ($_SESSION["uloga"] == 'Registrirani korisnik') {
        header("Location: ../usr/index.php");
    }
    if ($_SESSION["uloga"] == 'Moderator') {
        header("Location: ../mod/index.php");
    }
}

$db = new Baza();
$db->spojiDB();

$trenutno = date("Y-m-d H:i:s");
$sql_insert_dnevnik = "INSERT INTO `dnevnik`(`akcija`, `datumVrijeme`, `opis`) VALUES('Odjava', '" . $trenutno . "', 'Korisnik " . $_SESSION["korisnik"] . " se odjavio')";
$rez = $db->selectDB($sql_insert_dnevnik);
if(isset($_COOKIE["Prijava"])) {
    unset($_COOKIE["Prijava"]);
}
Sesija::obrisiSesiju();
$db->zatvoriDB();
header("Location: ../index.php");