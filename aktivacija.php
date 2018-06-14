<?php
include("baza.class.php");
include("sesija.class.php");

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

$korisnickoIme = "";
$aktivacijskiKod = "";
$db = new Baza();
$db->spojiDB();

if(isset($_GET["korIme"]) && isset($_GET["kod"])) {
    $korisnickoIme = $_GET["korIme"];
    $aktivacijskiKod = $_GET["kod"];
    
    $sql_upit_korisnika = "SELECT * FROM korisnik WHERE korisnicko_ime LIKE '" . $korisnickoIme . "' AND kriptiranaLozinka LIKE '" . $aktivacijskiKod . "'";
    $rezultatProvjere = $db->selectDB($sql_upit_korisnika);
    if(mysqli_num_rows($rezultatProvjere) > 0) { //Uspjesna aktivacija
        $sql_aktivacija_racuna = "UPDATE korisnik SET status = '1' WHERE korisnicko_ime LIKE '" . $korisnickoIme . "'";
        $aktivacija = $db->selectDB($sql_aktivacija_racuna);
        header("Location: prijava.php");
    }
    else {//Neuspjesno
        header("Location: index.php");
    }
}


$db->zatvoriDB();