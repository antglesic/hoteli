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

$sql_upit_zahtjevi = "SELECT * FROM zahtjev_blokiranje";
$zahtjevi = $db->selectDB($sql_upit_zahtjevi);

$vremenskaCrta = "";
while($row = $zahtjevi->fetch_assoc()) {
    $vremenskaCrta .= "<li><div><time>" . $row["datum"] . "</time>" . $row["razlog"] . "<br><h4>Oglas broj: " . $row["id_oglasa"] . "</h4><br><a class='button1' href='blokiranje.php?id=" . $row["ID_zahtjev"] . "&akcija=1'>PRIHVATI</a>" . "<br><a class='button2' href='blokiranje.php?id=" . $row["ID_zahtjev"] . "&akcija=0'>ODBIJ</a>" . "</div></li>";
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
        <link rel="stylesheet" type="text/css" href="../css/vremenskaCrta.css"/>
        <script type="text/javascript">
            $(document).ready(function () {
                $('#tablica').DataTable();
            });
        </script>
        <title>Zahtjevi za blokiranje</title>
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
                        <li><a href="novaSoba.php">Nova soba</a></li>
                        <li><a href="rezervacija.php">Nova rezervacija</a></li>
                        <li><a href="novaVrsta.php">Nova vrsta</a></li>
                        <li><a href="zahtjeviNovi.php">Zahtjevi za oglas</a></li>
                        <li><a href="zahtjeviBlok.php">Zahtjevi za blok</a></li>
                    </ul>
                </nav>
        </header>
        <section class="timeline">
            <ul>
                <?php echo($vremenskaCrta); ?>
            </ul>
        </section>
        <script type="text/javascript">
            (function () {
                'use strict';
                var items = document.querySelectorAll(".timeline li");
                function isElementInViewport(el) {
                    var rect = el.getBoundingClientRect();
                    return (
                            rect.top >= 0 &&
                            rect.left >= 0 &&
                            rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
                            rect.right <= (window.innerWidth || document.documentElement.clientWidth)
                            );
                }
                function callbackFunc() {
                    for (var i = 0; i < items.length; i++) {
                        if (isElementInViewport(items[i])) {
                            items[i].classList.add("in-view");
                        }
                    }
                }
                window.addEventListener("load", callbackFunc);
                window.addEventListener("resize", callbackFunc);
                window.addEventListener("scroll", callbackFunc);
            })();
        </script>
    </body>
</html>