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

$idHotela = "";
$db = new Baza();
$db->spojiDB();

if (isset($_GET["id"])) {
    $idHotela = $_GET["id"];

    $sql_upit_sobe = "SELECT soba.broj_sobe, soba.status_sobe, soba.tip_sobe_ID_tip_sobe, soba.hotel_ID_hotel, tip_sobe.naziv, COUNT(rezervacija.soba_ID_soba) as brojRez FROM soba, tip_sobe, rezervacija WHERE soba.hotel_ID_hotel LIKE '" . $idHotela . "' AND soba.tip_sobe_ID_tip_sobe LIKE tip_sobe.ID_tip_sobe AND rezervacija.soba_ID_soba LIKE soba.ID_soba GROUP BY soba.ID_soba";
    $sobe = $db->selectDB($sql_upit_sobe);

    $head = "<thead>" . "<tr>" . "<th>Broj sobe</th>" . "<th>Status sobe</th>" . "<th>Tip sobe</th>" . "<th>Broj rezervacija</th>" . "</tr>" . "</thead>";
    $table = "";

    while ($row = $sobe->fetch_assoc()) {
        $table = $table . "<tr>";
        $table = $table . "<td>" . $row["broj_sobe"] . "</td>" . "<td>" . $row["status_sobe"] . "</td>" . "<td>" . $row["naziv"] . "</td>" . "<td>" . $row["brojRez"] . "</td>";
        $table = $table . "</tr>";
    }
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
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/s/dt/jq-2.1.4,dt-1.10.10/datatables.min.css"/>
        <script type="text/javascript" src="https://cdn.datatables.net/s/dt/jq-2.1.4,dt-1.10.10/datatables.min.js"></script>
        <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                $('#tablica').DataTable();
            }); 
        </script>
        <title>Sobe Hotela</title>
    </head>
    <body>
        <header>
            <section>
                <label id="toggle" for="toggle-1" class="toggle-menu"><ul><li></li> <li></li> <li></li></ul></label>
                <input type="checkbox" id="toggle-1">

                <nav>
                    <ul>
                        <li><a href="index.php">Naslovna</a></li>
                        <li><a href="https://barka.foi.hr/WebDiP/2017_projekti/WebDiP2017x066/prijava.php">Prijava</a></li>
                        <li><a href="registracija.php">Registracija</a></li>
                        <li><a href="hoteli.php">Hoteli</a></li>
                        <li><a href="sobe.php">Sobe</a></li>
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