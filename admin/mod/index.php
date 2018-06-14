<?php
include("../../sesija.class.php");

Sesija::kreirajSesiju();

if (isset($_SESSION["korisnik"])) {
    if ($_SESSION["uloga"] == 'Moderator') {
        header("Location: ../../mod/index.php");
    }
    if ($_SESSION["uloga"] == 'Registriran korisnik') {
        header("Location: ../../usr/index.php");
    }
}

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
        <link rel="stylesheet" type="text/css" href="../../css/style.css">
        <title>Naslovnica</title>
    </head>
    <body>
        <header>
            <a id="odjava" href="odjava.php">Odjava</a>
            <section>
                <label id="toggle" for="toggle-1" class="toggle-menu"><ul><li></li> <li></li> <li></li></ul></label>
                <input type="checkbox" id="toggle-1">

                <nav>
                    <ul>
                        <li><a href="../index.php">Naslovna</a></li>
                        <li><a href="novaSoba.php">Nova soba</a></li>
                        <li><a href="rezervacija.php">Nova rezervacija</a></li>
                        <li><a href="novaVrsta.php">Nova vrsta</a></li>
                        <li><a href="zahtjeviNovi.php">Zahtjevi za oglas</a></li>
                        <li><a href="zahtjeviBlok.php">Zahtjevi za blok</a></li>
                    </ul>
                </nav>
        </header>
    </body>
</html>