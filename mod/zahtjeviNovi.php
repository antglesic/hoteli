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

$idKorisnika = "";
$sql_upit_korisnik = "SELECT * FROM korisnik WHERE korisnicko_ime LIKE '" . $_SESSION["korisnik"] . "'";
$korisnik = $db->selectDB($sql_upit_korisnik);
while ($row = $korisnik->fetch_assoc()) {
    $idKorisnika = $row["ID_korisnik"];
}

$sql_upit_zahtjevi = "SELECT zahtjev_novi.id_zahtjeva, zahtjev_novi.naziv, zahtjev_novi.opis, zahtjev_novi.status_zahtjeva, zahtjev_novi.slika FROM zahtjev_novi, vrsta_oglasa, moderira_poziciju, korisnik WHERE zahtjev_novi.status_zahtjeva LIKE 'U čekanju' AND zahtjev_novi.id_vrste LIKE vrsta_oglasa.ID_vrsta_oglasa AND vrsta_oglasa.id_pozicije LIKE moderira_poziciju.pozicija_oglasa_ID_pozicija_oglasa AND moderira_poziciju.korisnik_ID_korisnik LIKE '" . $idKorisnika . "' GROUP BY 1";
$zahtjevi = $db->selectDB($sql_upit_zahtjevi);

$head = "<thead>" . "<tr>" . "<th>Slika</th>" . "<th>Naziv</th>" . "<th>Opis</th>" . "<th>Status</th>" . "<th>Prihvati</th>" . "<th>Odbij</th>" . "</tr>" . "</thead>";
$table = "";

while ($row = $zahtjevi->fetch_assoc()) {
    $table = $table . "<tr>";
    $table = $table . "<td><img src='../slike/" . $row["slika"] . "' width='50' height='50'></td>" . "<td>" . $row["naziv"] . "</td>" . "<td>" . $row["opis"] . "</td>" . "<td>" . $row["status_zahtjeva"] . "</td>" . "<td><a href='zahtjevObrada.php?id=" . $row["id_zahtjeva"] . "'>PRIHVATI</a></td>" . "<td><a href='zahtjevObrada.php?id=" . $row["id_zahtjeva"] . "'>ODBIJ</a></td>";
    $table = $table . "</tr>";
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
        <script src="js/oglasi.js"></script>
        <script type="text/javascript">
            $(document).ready(function () {
                $('#tablica').DataTable();
            });
        </script>
        <title>Zahtjevi za novi oglas</title>
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
        <table id="tablica" class="display" style="width:90%">
            <?php
            echo $head;
            ?>
            <tbody style="text-align: center">
                <?php
                echo $table;
                ?>
            </tbody>
        </table>
        <div id="slideshow" style="max-width:500px; max-height: 300px;margin: 0 auto 100px;padding: 45px;"></div>
        <script type="text/javascript">
            $(document).ready(function () {
                $("#slideshow > div:gt(0)").hide();

                setInterval(function () {
                    $('#slideshow > div:first')
                            .fadeOut(10)
                            .next()
                            .fadeIn(10)
                            .end()
                            .appendTo('#slideshow');
                }, 3000);
            });
        </script>
        <script type="text/javascript">
            function klik(id) {
                window.location.href = "klik.php?idOglasa=" + id + "&prethodna=zahtjevNovi";
            }
        </script>
    </body>
</html>