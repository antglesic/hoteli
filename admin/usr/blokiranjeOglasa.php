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

$idOglasa = "";
$idKorisnika = "";
$nazivOglasa = "";
$img = "";

$db = new Baza();
$db->spojiDB();

$sql_upit_korisnik = "SELECT * FROM korisnik WHERE korisnicko_ime LIKE '" . $_SESSION["korisnik"] . "'";
$korisnik = $db->selectDB($sql_upit_korisnik);
while($row = $korisnik->fetch_assoc()) {
    $idKorisnika = $row["ID_korisnik"];
}

if(isset($_GET["id"])) {
    $idOglasa = $_GET["id"];
    $sql_upit_oglas = "SELECT * FROM Oglas WHERE oglas_ID LIKE '" . $idOglasa . "'";
    $oglas = $db->selectDB($sql_upit_oglas);
    while($row = $oglas->fetch_assoc()) {
        $nazivOglasa = $row["naziv"];
        $img = "<img id='slika' src='../../slike/" . $row["slika"] . "' height='100' width='150'>";
    }
}

if(isset($_POST["submit"])) {
    header("Location: blok.php?id=" . $_POST["sifraOglasa"] . "&razlog=" . $_POST["razlog"]);
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
        <link rel="stylesheet" type="text/css" href="../../css/style.css">
        <title>Oglas</title>
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
                        <li><a href="oglasi.php">Oglasi</a></li>
                        <li><a href="vrste.php">Novi oglas</a></li>
                        <li><a href="vasiZahtjevi.php">Vaši zahtjevi</a></li>
                        <li><a href="vasiOglasi.php">Vaši oglasi</a></li>
                    </ul>
                </nav>
        </header>
        <form class="form-login" id="blokiranje" method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
            <div class="form-log-in-with-email">
                <div class="form-white-background">
                    <div class="form-row">
                        <input type="hidden" id="sifraOglasa" name="sifraOglasa" value="<?php echo$idOglasa; ?>"
                    </div>
                    <div class="form-title-row">
                        <h1>Blokiraj <?php echo$nazivOglasa ?></h1>
                    </div>
                    <div class="form-row">
                        <?php echo$img; ?>
                    </div>
                    <div class="form-row">
                        <input id="razlog" type="text" name="razlog" placeholder="Razlog"/>
                    </div>
                    <div class="form-row">
                        <input id="submit" class="button" type="submit" name="submit" value="POŠALJI ZAHTJEV"/>
                    </div>
                </div>

            </div>
        </form>

    </body>
</html>