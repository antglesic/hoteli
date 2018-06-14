<?php
include("../../sesija.class.php");
include("../../baza.class.php");

Sesija::kreirajSesiju();

if (isset($_SESSION["korisnik"])) {
    if ($_SESSION["uloga"] == 'Moderator') {
        header("Location: ../../mod/index.php");
    }
    if ($_SESSION["uloga"] == 'Registriran korisnik') {
        header("Location: ../../usr/index.php");
    }
}

$db = new Baza();
$db->spojiDB();

$idKorisnika = "";

$sql_upit_korisnik = "SELECT * FROM korisnik WHERE korisnicko_ime LIKE '" . $_SESSION["korisnik"] . "'";
$korisnik = $db->selectDB($sql_upit_korisnik);
while($row = $korisnik->fetch_assoc()) {
    $idKorisnika = $row["ID_korisnik"];
}

$sql_pozicije = "SELECT pozicija_oglasa.ID_pozicija_oglasa, pozicija_oglasa.sirina, pozicija_oglasa.visina FROM pozicija_oglasa, moderira_poziciju WHERE moderira_poziciju.korisnik_ID_korisnik LIKE '" . $idKorisnika . "' AND moderira_poziciju.pozicija_oglasa_ID_pozicija_oglasa LIKE pozicija_oglasa.ID_pozicija_oglasa";
$pozicije = $db->selectDB($sql_pozicije);
$selected1 = "<select id='pozicija' name='idpozicija'>";
while ($row = $pozicije->fetch_assoc()) {
    $prikaz = $row['sirina'] . "x" . $row["visina"];
    $selected1 .= "<option value='" . $row['ID_pozicija_oglasa'] . "'>" . $prikaz . "</option>";
}
$selected1 .= "</select>";

$trajanje = "";
$izmjena = "";
$cijena = "";
$pozicija = "";

if(isset($_POST["submit"])) {
    $trajanje = $_POST["trajanje"];
    $izmjena = $_POST["izmjena"];
    $cijena = $_POST["cijena"];
    $pozicija = $_POST["idpozicija"];
    
    $datum = date("Y-m-d H:i:s");
    $sql_insert_vrsta = "INSERT `vrsta_oglasa`(`trajanje_prikazivanja`, `brzina_izmjene`, `cijena_oglasa`, `id_pozicije`) VALUES('" . $trajanje . "', '" . $izmjena . "', '" . $cijena . "', '" . $pozicija . "')";
    $novaVrsta = $db->selectDB($sql_insert_vrsta);
    $sql_dnevnik = "INSERT INTO `dnevnik`(`akcija`, `datumVrijeme`, `opis`) VALUES('Nova vrsta oglasa', '" . $datum . "', 'Definirana nova vrsta oglasa')";
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
        <link rel="stylesheet" type="text/css" href="../../css/style.css">
        <title>Nova vrsta</title>
    </head>
    <body>
        <header>
            <a id="odjava" href="odjava.php">Odjava</a>
            <section>
                <label id="toggle" for="toggle-1" class="toggle-menu"><ul><li></li> <li></li> <li></li></ul></label>
                <input type="checkbox" id="toggle-1">

                <nav>
                    <ul>
                        <li><a href="../index.php">Naslovna</a></li>
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
                        <h1>Nova vrsta</h1>
                    </div>
                    <div class="form-row">
                        <h4>Trajanje: </h4>
                        <input name="trajanje" type="number"/>
                    </div>
                    <div class="form-row">
                        <h4>Brzina izmjene: </h4>
                        <input name="izmjena" type="number"/>
                    </div>
                    <div class="form-row">
                        <h4>Cijena: </h4>
                        <input name="cijena" type="number"/>
                    </div>
                    <div class="form-row">
                        <h4>Pozicija: </h4>
                        <?php echo($selected1);?>
                    </div>
                    <div class="form-row">
                        <input id="submit" class="button" type="submit" name="submit" value="DEFINIRAJ"/>
                    </div>
                </div>

            </div>
        </form>

    </body>
</html>