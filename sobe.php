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

$db = new Baza();
$db->spojiDB();

$sql_upit_sobe = "SELECT soba.ID_soba, soba.broj_sobe, soba.status_sobe, hotel.naziv as Hotel, tip_sobe.naziv as Velicina FROM soba, tip_sobe, hotel WHERE soba.hotel_ID_hotel LIKE hotel.ID_hotel AND soba.tip_sobe_ID_tip_sobe LIKE tip_sobe.ID_tip_sobe";
$sobe = $db->selectDB($sql_upit_sobe);

$head = "<thead>" . "<tr>" . "<th>Broj sobe</th>" . "<th>Status sobe</th>" . "<th>Veličina sobe</th>" . "<th>Hotel</th>" . "<th>Opširnije</th>" . "</tr>" . "</thead>";
$table = "";

while ($row = $sobe->fetch_assoc()) {
    $table = $table . "<tr>";
    $table = $table . "<td>" . $row["broj_sobe"] . "</td>" . "<td>" . $row["status_sobe"] . "</td>" . "<td>" . $row["Velicina"] . "</td>" . "<td>" . $row["Hotel"] . "</td>" . "<td><a href='soba.php?id=" . $row["ID_soba"] . "'>OPŠIRNIJE</a></td>";
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
        <link rel="stylesheet" type="text/css" href="css/style.css">
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
        <title>Sobe</title>
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
                window.location.href = "klik.php?idOglasa=" + id + "&prethodna=sobe";
            }
        </script>
    </body>
</html>