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

$sql_upit_sobe = "SELECT * FROM soba WHERE status_sobe LIKE 'Slobodna'";
$sobe = $db->selectDB($sql_upit_sobe);
$selected1 = "<select id='soba' name='idsoba'>";
while ($row = mysqli_fetch_array($sobe)) {
    $prikaz = $row['broj_sobe'];
    $selected1 .= "<option value='" . $row['broj_sobe'] . "'>" . $prikaz . "</option>";
}
$selected1 .= "</select>";

$sql_upit_korisnici = "SELECT * FROM korisnik WHERE tip_korisnika_ID_tip_korisnika LIKE '2'";
$korisnici = $db->selectDB($sql_upit_korisnici);
$selected2 = "<select id='korisnik' name='idkorisnika'>";
while ($row = mysqli_fetch_array($korisnici)) {
    $prikaz = $row['ime'] . " " . $row["prezime"];
    $selected2 .= "<option value='" . $row['ID_korisnik'] . "'>" . $prikaz . "</option>";
}
$selected2 .= "</select>";

$brojSobe = "";
$idKorisnika = "";
$dolazak = "";
$trajanje = "";

if(isset($_POST["submit"])) {
    $brojSobe = $_POST["idsoba"];
    $idKorisnika = $_POST["idkorisnika"];
    $dolazak = $_POST["datumVrijeme"];
    $trajanje = $_POST["trajanje"];
    

    $sql_nova_rezervacija = "INSERT INTO `rezervacijaKorisnik`(`id_sobe`, `id_korisnika`, `dolazak`, `trajanje_boravka`) VALUES('" . $brojSobe . "', '" . $idKorisnika . "', '" . $dolazak . "', '" . $trajanje . "')";
    $novaRezervacija = $db->selectDB($sql_nova_rezervacija);
    $datum = date("Y-m-d H:i:s");
    $sql_dnevnik = "INSERT INTO `dnevnik`(`akcija`, `datumVrijeme`, `opis`) VALUES('Nova rezervacija', '" . $datum . "', 'Korisnik " . $idKorisnika . " je rezervirao sobu broj " . $brojSobe . "')";
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
        <title>Nova rezervacija</title>
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
        <form class="form-login" id="rezervacija" method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
            <div class="form-log-in-with-email">
                <div class="form-white-background">
                    <div class="form-title-row">
                        <h1>Rezervacija</h1>
                    </div>
                    <div class="form-row">
                        <h4>Broj sobe: </h4>
                        <?php echo($selected1);?>
                    </div>
                    <div class="form-row">
                        <h4>Korisnik: </h4>
                        <?php echo($selected2);?>
                    </div>
                    <div class="form-row">
                        <h4>Dolazak: </h4>
                        <input id="datum" name="datumVrijeme" type="date" />
                    </div>
                    <div class="form-row">
                        <h4>Trajanje boravka: </h4>
                        <input id="trajanje" name="trajanje" type="number"/>
                    </div>
                    <div class="form-row">
                        <input id="submit" class="button" type="submit" name="submit" value="REZERVIRAJ"/>
                    </div>
                </div>

            </div>
        </form>

    </body>
</html>