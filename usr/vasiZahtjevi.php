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

$idKorisnika = "";
$sql_upit_korisnik = "SELECT * FROM korisnik WHERE korisnicko_ime LIKE '" . $_SESSION["korisnik"] . "'";
$korisnik = $db->selectDB($sql_upit_korisnik);
while ($row = $korisnik->fetch_assoc()) {
    $idKorisnika = $row["ID_korisnik"];
}

$sql_upit_zahtjevi = "SELECT * FROM zahtjev_novi WHERE id_korisnika LIKE '" . $idKorisnika . "'";
$zahtjevi = $db->selectDB($sql_upit_zahtjevi);

$head = "<thead>" . "<tr>" . "<th>Slika</th>" . "<th>Naziv</th>" . "<th>Opis</th>" . "<th>Status</th>" . "<th>Izmjena</th>" . "</tr>" . "</thead>";
$table = "";

while ($row = $zahtjevi->fetch_assoc()) {
    $table = $table . "<tr>";
    if ($row["status_zahtjeva"] == 'U čekanju') {
        $table = $table . "<td><img src='../slike/" . $row["slika"] . "' width='50' height='50'></td>" . "<td>" . $row["naziv"] . "</td>" . "<td>" . $row["opis"] . "</td>" . "<td>" . $row["status_zahtjeva"] . "</td>" . "<td><a href='izmjena.php?id=" . $row["id_zahtjeva"] . "'>IZMJENA</a></td>";
    }
    else {
        $table = $table . "<td><img src='../slike/" . $row["slika"] . "' width='50' height='50'></td>" . "<td>" . $row["naziv"] . "</td>" . "<td>" . $row["opis"] . "/<td>" . "<td>" . $row["status_zahtjeva"] . "</td>" . "<td>NIJE MOGUĆE</td>";
    }
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
        <title>Vaši zahtjevi</title>
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
    </body>
</html>