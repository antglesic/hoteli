<?php
include("../baza.class.php");
include("../sesija.class.php");

Sesija::kreirajSesiju();

if (isset($_SESSION["korisnik"])) {
    if ($_SESSION["uloga"] == 'Moderator') {
        header("Location: ../mod/index.php");
    }
    if ($_SESSION["uloga"] == 'Administrator') {
        header("Location: ../admin/index.php");
    }
}

$idVrste = "";
$idKorisnika = "";

$db = new Baza();
$db->spojiDB();

$sql_upit_korisnik = "SELECT * FROM korisnik WHERE korisnicko_ime LIKE '" . $_SESSION["korisnik"] . "'";
$korisnik = $db->selectDB($sql_upit_korisnik);
while ($row = $korisnik->fetch_assoc()) {
    $idKorisnika = $row["ID_korisnik"];
}

if (isset($_GET["id"])) {
    $idVrste = $_GET["id"];
}

$naziv = "";
$opis = "";
$url = "";
$datum = "";
$name = "";

if(isset($_POST["submit"])) {
    $idVrste = $_POST["sifraVrste"];
    $naziv = $_POST["naziv"];
    $opis = $_POST["opis"];
    $url = $_POST["url"];
    $datum = $_POST["datum"];
    $name = $_FILES['file']['name'];
    $datumZahtjeva = date("Y-m-d H:i:s");
    
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
    
    
    $sql_novi_zahtjev = "INSERT INTO `zahtjev_novi`(`naziv`, `opis`, `url`, `id_vrste`, `id_korisnika`, `status_zahtjeva`, `slika`, `datum`) VALUES('" . $naziv . "', '" . $opis . "', '" . $url . "', '" . $idVrste . "', '" . $idKorisnika . "', 'U čekanju', '" . $name . "', '" . $datum . "')";
    $noviZahtjev = $db->selectDB($sql_novi_zahtjev);
    $sql_dnevnik = "INSERT INTO `dnevnik`(`akcija`, `datumVrijeme`, `opis`) VALUES('Novi zahtjev', '" . $datumZahtjeva . "', 'Podnesen zahtjev za novim oglasom')";
    $noviZapis = $db->selectDB($sql_dnevnik);
    header("Location: index.php");
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
        <link rel="stylesheet" type="text/css" href="../css/style.css">
        <title>Novi oglas</title>
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
        <form class="form-login" id="noviOglas" method="POST" action="#file" enctype="multipart/form-data">
            <div class="form-log-in-with-email">
                <div class="form-white-background">
                    <div class="form-row">
                        <input type="hidden" id="sifraVrste" name="sifraVrste" value="<?php echo$idVrste; ?>"
                    </div>
                    <div class="form-row">
                        <input id="naziv" type="text" name="naziv" placeholder="Naziv oglasa"/>
                    </div>
                    <div class="form-row">
                        <input id="opis" type="text" name="opis" placeholder="Opis oglasa"/>
                    </div>
                    <div class="form-row">
                        <input id="url" type="text" name="url" placeholder="URL"/>
                    </div>
                    <div class="form-row">
                        <input id="datum" type="date" name="datum"/>
                    </div>
                    <div class="form-row">
                        <label for="file">Slika oglasa: </label>
                        <input id="file" name="file" type="file"/>
                    </div>
                    <div class="form-row">
                        <input id="submit" class="button" type="submit" name="submit" value="POŠALJI ZAHTJEV"/>
                    </div>
                </div>

            </div>
        </form>

    </body>
</html>