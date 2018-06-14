<?php
include("../sesija.class.php");
include("../baza.class.php");

Sesija::kreirajSesiju();

if (isset($_SESSION["korisnik"])) {
    if ($_SESSION["uloga"] == 'Moderator') {
        header("Location: ../mod/index.php");
    }
    if ($_SESSION["uloga"] == 'Administrator') {
        header("Location: ../admin/index.php");
    }
}

$db = new Baza();
$db->spojiDB();

$sql_upit_oglasi = "SELECT * FROM Oglas WHERE status LIKE 'Aktivan'";
$oglasi = $db->selectDB($sql_upit_oglasi);

$head = "<thead>" . "<tr>" . "<th>Naziv oglasa</th>" . "<th>Poveznica</th>" . "<th>Broj klikova</th>" . "<th>Blokiraj</th>" . "</tr>" . "</thead>";
$table = "";

while ($row = $oglasi->fetch_assoc()) {
    $table = $table . "<tr>";
    $table = $table . "<td>" . $row["naziv"] . "</td>" . "<td><a href='" . $row["url"] . "'>" . $row["url"] ."</a></td>" . "<td>" . $row["klikovi"] . "<td><a href='blokiranjeOglasa.php?id=" . $row["oglas_ID"] . "'>BLOKIRAJ</a></td>";
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
            $(document).ready(function() {
                $('#tablica').DataTable();
            }); 
        </script>
        <title>Oglasi</title>
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
                        <li><a href="oglasi.php">Oglasi</a></li>
                        <li><a href="vrste.php">Novi oglas</a></li>
                        <li><a href="vasiZahtjevi.php">Vaši zahtjevi</a></li>
                        <li><a href="vasiOglasi.php">Vaši oglasi</a></li>
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
                window.location.href = "klik.php?idOglasa=" + id + "&prethodna=oglasi";
            }
        </script>
    </body>
</html>