<?php
include("../sesija.class.php");
include("../baza.class.php");

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

$sql_upit_korisnika = "SELECT vrsta_oglasa.cijena_oglasa*COUNT(Oglas.id_korisnika) AS placeno, korisnik.ime, korisnik.prezime FROM vrsta_oglasa, Oglas, korisnik WHERE Oglas.id_korisnika LIKE korisnik.ID_korisnik AND Oglas.vrsta_oglasa_ID_vrsta_oglasa LIKE vrsta_oglasa.ID_vrsta_oglasa GROUP BY Oglas.id_korisnika ORDER BY 1 DESC";
$korisnici = $db->selectDB($sql_upit_korisnika);

$head = "<thead>" . "<tr>" . "<th>Korisnik</th>" . "<th>Plaćen iznos</th>" . "</tr>" . "</thead>";
$table = "";

while ($row = $korisnici->fetch_assoc()) {
    $table = $table . "<tr>";
    $table = $table . "<td>" . $row["ime"] . " " . $row["prezime"] . "</td>" . "<td>" . $row["placeno"] ."</td>";
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
        <script type="text/javascript">
            $(document).ready(function () {
                $('#tablica').DataTable();
            });
        </script>
        <title>Top korisnici</title>
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
                        <li><a href="hotelNovi.php">Novi hotel</a></li>
                        <li><a href="pozicijaNova.php">Nova pozicija</a></li>
                        <li><a href="moderatorNovi.php">Novi moderator</a></li>
                        <li><a href="statistikaKlikovi.php">Klikovi</a></li>
                        <li><a href="statistikaPlacenih.php">Plaćeni</a></li>
                        <li><a href="statistikaKorisnici.php">Top korisnici</a></li>
                        <li><a href="otkljucavanjeRacuna.php">Računi</a></li>
                        <li><a href="dnevnik.php">Dnevnik</a></li>
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
    </body>
</html>