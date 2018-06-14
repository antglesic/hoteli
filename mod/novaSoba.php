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
$brojSobe = "";
$idTipa = "";
$idHotela = "";
$name = "";


$sql_upit_korisnik = "SELECT * FROM korisnik WHERE korisnicko_ime LIKE '" . $_SESSION["korisnik"] . "'";
$korisnik = $db->selectDB($sql_upit_korisnik);
while($row = $korisnik->fetch_assoc()) {
    $idKorisnika = $row["ID_korisnik"];
}

$sql_upit_hoteli = "SELECT hotel.naziv, hotel.ID_hotel FROM hotel, moderator, korisnik WHERE hotel.ID_hotel LIKE moderator.hotel_ID_hotel AND korisnik.ID_korisnik LIKE moderator.korisnik_ID_korisnik AND korisnik.ID_korisnik LIKE '" . $idKorisnika . "'";
$hoteli = $db->selectDB($sql_upit_hoteli);
$selected1 = "<select id='hotel' name='hotel'>";
while ($row = mysqli_fetch_array($hoteli)) {
    $prikaz = $row['naziv'];
    $selected1 .= "<option value='" . $row['ID_hotel'] . "'>" . $prikaz . "</option>";
}
$selected1 .= "</select>";

$sql_upit_tipovi = "SELECT * FROM tip_sobe";
$tipovi = $db->selectDB($sql_upit_tipovi);
$selected2 = "<select id='tip_sobe' name='tip_sobe'>";
while ($row = mysqli_fetch_array($tipovi)) {
    $prikaz = $row['naziv'];
    $selected2 .= "<option value='" . $row['ID_tip_sobe'] . "'>" . $prikaz . "</option>";
}
$selected2 .= "</select>";

if(isset($_POST["submit"])) {
    $brojSobe = $_POST["broj"];
    $idTipa = $_POST["tip_sobe"];
    $idHotela = $_POST["hotel"];
    $name = $_FILES['file']['name'];
    $datum = date("Y-m-d H:i:s");
    
    $tmp_name = $_FILES['file']['tmp_name'];
    $position = strpos($name, ".");
    $fileextension = substr($name, $position + 1);
    $fileextension = strtolower($fileextension);
    if (isset($name)) {
        $path = "../slike/";
        if (!empty($name)) {
            if (move_uploaded_file($tmp_name, $path . $name)) {
                //echo "Uploadano!";
            }
        }
    }
    
    $sql_nova_soba = "INSERT INTO `soba`(`broj_sobe`, `status_sobe`, `slika`, `tip_sobe_ID_tip_sobe`, `hotel_ID_hotel`) VALUES('" . $brojSobe . "', 'Slobodna', '" . $name . "', '" . $idTipa . "', '" . $idHotela . "')";
    $zapisanaSoba = $db->selectDB($sql_nova_soba);
    $sql_dnevnik = "INSERT INTO `dnevnik`(`akcija`, `datumVrijeme`, `opis`) VALUES('Nova soba', '" . $datum . "', 'Definirana nova soba broj " . $brojSobe . "')";
    $noviZapis = $db->selectDB($sql_dnevnik);
    header("Location: index.php");
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
        <title>Nova soba</title>
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
        <form class="form-login" id="novaSoba" method="POST" action="#file" enctype="multipart/form-data">
            <div class="form-log-in-with-email">
                <div class="form-white-background">
                    <div class="form-title-row">
                        <h1>Nova soba</h1>
                    </div>
                    <div class="form-row">
                        <h4>Broj sobe:</h4>
                        <input id="broj" type="number" name="broj" placeholder="Broj sobe"/>
                    </div>
                    <div class="form-row">
                        <h4>Tip sobe: </h4>
                        <?php echo($selected2);?>
                    </div>
                    <div class="form-row">
                        <h4>Hotel: </h4>
                        <?php echo($selected1);?>
                    </div>
                    <div class="form-row">
                        <h4>Slika:</h4>
                        <input id="file" name="file" type="file"/>
                    </div>
                    <div class="form-row">
                        <input id="submit" class="button" type="submit" name="submit" value="KREIRAJ"/>
                    </div>
                </div>

            </div>
        </form>

    </body>
</html>