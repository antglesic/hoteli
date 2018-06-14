<?php
include("sesija.class.php");
include("baza.class.php");

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

$ime = "";
$prezime = "";
$korisnickoIme = "";
$email = "";
$lozinka1 = "";
$lozinka2 = "";
$aktivacijskiLink = "";
$brojGresaka = 0;
$povratnaInformacija = "";
$db = new Baza();
$db->spojiDB();

if(isset($_POST["submit"])) {
    $ime = $_POST["ime"];
    $prezime = $_POST["prezime"];
    $korisnickoIme = $_POST["korIme"];
    $email = $_POST["email"];
    $lozinka1 = $_POST["lozinka1"];
    $lozinka2 = $_POST["lozinka2"];
    //Ukoliko nisu sva polja ispunjena
    if ($ime === '' || $prezime === '' || $korisnickoIme === '' || $email === '' || $lozinka1 === '' || $lozinka2 === '') {
        $brojGresaka++;
    }
    //Ukoliko su koristeni nedozvoljeni simboli
    if ((strpos($ime, "'") !== false) || (strpos($prezime, "'") !== false) || (strpos($korisnickoIme, "'") !== false) || (strpos($email, "'") !== false) || (strpos($lozinka1, "'") !== false) || (strpos($lozinka2, "'") !== false) || (strpos($ime, "!") !== false) || (strpos($prezime, "!") !== false) || (strpos($korisnickoIme, "!") !== false) || (strpos($email, "!") !== false) || (strpos($lozinka1, "!") !== false) || (strpos($lozinka2, "!") !== false) || (strpos($ime, "?") !== false) || (strpos($prezime, "?") !== false) || (strpos($korisnickoIme, "?") !== false) || (strpos($email, "?") !== false) || (strpos($lozinka1, "?") !== false) || (strpos($lozinka2, "?") !== false) || (strpos($ime, "#") !== false) || (strpos($prezime, "#") !== false) || (strpos($korisnickoIme, "#") !== false) || (strpos($email, "#") !== false) || (strpos($lozinka1, "#") !== false) || (strpos($lozinka2, "#") !== false)) {
        $brojGresaka++;
    }
    //Ukoliko mail nije dobrog formata
    if (preg_match("/[a-z0-9]+[_a-z0-9\.-]*[a-z0-9]+@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})/", $email) === false) {
        $brojGresaka++;
    }
    //Ukoliko lozinke nisu jednake
    if ($lozinka1 !== $lozinka2) {
        $brojGresaka++;
    }
    //Provjera korisnickog imena postoji li u bazi
    $sql_provjera_korimena = "SELECT * FROM korisnik WHERE korisnicko_ime LIKE '" . $korisnickoIme . "'";
    $rezultatProvjereKorimena = $db->selectDB($sql_provjera_korimena);
    if(mysqli_num_rows($rezultatProvjereKorimena) > 0) $brojGresaka++;
    //Provjera emaila postoji li u bazi
    $sql_provjera_emaila = "SELECT * FROM korisnik WHERE email LIKE '" . $email . "'";
    $rezultatProvjereEmaila = $db->selectDB($sql_provjera_emaila);
    if(mysqli_num_rows($rezultatProvjereEmaila) > 0) $brojGresaka++;
    //Uspjesna registracija
    if($brojGresaka === 0) {
        $sol = sha1(time());
        $kriptiranaLozinka = sha1($sol . "--" . $lozinka1);
        $opisAkcije = "Korisnik " . $korisnickoIme . " se registrirao";
        $datumRegistracije = date("Y-m-d H:i:s");
        $sql_novi_korisnik = "INSERT INTO `korisnik`(`korisnicko_ime`, `email`, `lozinka`, `kriptiranaLozinka`, `ime`, `prezime`, `tip_korisnika_ID_tip_korisnika`, `status`, `brojGresaka`) VALUES('" . $korisnickoIme . "', '" . $email . "', '" . $lozinka1 . "', '" . $kriptiranaLozinka . "', '" . $ime . "', '" . $prezime . "', '2', '0', '0')";
        $zapisivanjeNovogKorisnika = $db->selectDB($sql_novi_korisnik);
        $sql_dnevnik = "INSERT INTO `dnevnik`(`akcija`, `datumVrijeme`, `opis`) VALUES('Registracija', '" . $datumRegistracije . "', '" . $opisAkcije . "')";
        $dnevnikZapis = $db->selectDB($sql_dnevnik);
        $aktivacijskiLink = "<a href='http://barka.foi.hr/WebDiP/2017_projekti/WebDiP2017x066/aktivacija.php?korIme=" . $korisnickoIme . "&kod=" . $kriptiranaLozinka . ">Aktivirajte svoj racun</a>";
        $mail_to = $email;
        $mail_from = "From: WebDiP2017x066";
        $mail_subject = "Aktivacija registracije";      //$_POST["subjekt"];
        $mail_body = "Pritisnite na iduci link kako biste aktivirali vas racun: " . $aktivacijskiLink;
        if (mail($mail_to, $mail_subject, $mail_body, $mail_from)) {
            $povratnaInformacija = "Uspjesno ste registrirali vas racun!" . "<br>";
        } else {
            $povratnaInformacija = "Problem kod registracije korisnickog racuna!" . "<br>";
        }
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
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="http://code.jquery.com/jquery-1.10.2.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
        <script src='https://www.google.com/recaptcha/api.js'></script>
        <script src="js/registracija.js"></script>
        <title>Registracija</title>
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
        <div id="greske">
            <?php 
                if(isset($povratnaInformacija)) {
                    echo $povratnaInformacija."<br>";
                }
                if(isset($aktivacijskiLink)) {
                    echo $aktivacijskiLink."<br>";
                }
            ?>
        </div>
        <form class="form-login" id="prijava" method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
            <div class="form-log-in-with-email">
                <div class="form-white-background">
                    <div class="form-title-row">
                        <h1>Registracija</h1>
                    </div>
                    <div class="form-row">
                        <input id="ime" type="text" name="ime" placeholder="Ime"/>
                    </div>
                    <div class="form-row">
                        <input id="prezime" type="text" name="prezime" placeholder="Prezime"/>
                    </div>
                    <div class="form-row">
                        <input id="korIme" type="text" name="korIme" placeholder="Korisničko ime"/>
                    </div>
                    <div class="form-row">
                        <input id="email" type="text" name="email" placeholder="Email"/>
                    </div>
                    <div class="form-row">
                        <input id="lozinka1" type="password" name="lozinka1" placeholder="Lozinka"/>
                    </div>
                    <div class="form-row">
                        <input id="lozinka2" type="password" name="lozinka2" placeholder="Ponovljena lozinka"/>
                    </div>
                    <div class="g-recaptcha" data-sitekey="6LdqZVwUAAAAAEdeEurKcKCRShCfcMOusZepl-hn"></div>
                    <div class="form-row">
                        <input id="submit" class="button" type="submit" name="submit" value="REGISTRACIJA"/>
                    </div>
                    <a href="prijava.php" class="form-create-an-account">Imaš račun? Prijavi se... &rarr;</a>
                </div>

            </div>
        </form>

    </body>
</html>