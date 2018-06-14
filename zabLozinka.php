<?php
include("baza.class.php");
include("sesija.class.php");

Sesija::kreirajSesiju();

if (isset($_SESSION["korisnik"])) {
    if ($_SESSION["uloga"] == 'Registriran korisnik') {
        header("Location: usr/index.php");
    }
    if($_SESSION["uloga"] == 'Moderator') {
        header("Location: mod/index.php");
    }
    if ($_SESSION["uloga"] == 'Administrator') {
        header("Location: admin/index.php");
    }
}

$db = new Baza();
$db->spojiDB();

$korisnickoIme = "";
$lozinka = "";
$email = "";
$kontrola = 0;

if (isset($_POST["submit"])) {
    $email = $_POST["email"];

    $sql_upit_korisnika = "SELECT * FROM korisnik WHERE email LIKE '" . $email . "'";
    $korisnik = $db->selectDB($sql_upit_korisnika);
    if (mysqli_num_rows($korisnik) > 0) {
        while ($row = $korisnik->fetch_assoc()) {
            $lozinka = $row["lozinka"];
            $korisnickoIme = $row["korisnicko_ime"];
        }
        $sol = sha1(time());
        $nova_lozinka = sha1($sol . "--" . $lozinka);
        $sql_update_lozinka = "UPDATE korisnik SET lozinka = '" . $nova_lozinka . "' WHERE korisnicko_ime LIKE '" . $korIme . "'";
        $promijenjenaLozinka = $db->selectDB($sql_update_lozinka);
        
        $mail_to = $email;
        $mail_from = "From: WebDiP2017x066";
        $mail_subject = "Nova Lozinka";
        $mail_body = "Vaša nova lozinka izgleda ovako: " . $nova_lozinka;
        if (mail($mail_to, $mail_subject, $mail_body, $mail_from)) {
            header("Location: prijava.php");
        }
    } else {
        $kontrola = 1;
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
        <title>Zaboravljena lozinka</title>
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
            <?php if($kontrola === 1) echo("<h4>Nepostojeća email adresa!</h4><br>"); ?>
        </div>
        <form class="form-login" id="zabLozinka" method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
            <div class="form-log-in-with-email">
                <div class="form-white-background">
                    <div class="form-title-row">
                        <h1>Zaboravljena lozinka</h1>
                    </div>
                    <div class="form-row">
                        <input id="email" type="text" name="email" placeholder="Email"/>
                    </div>
                    <div class="form-row">
                        <input id="submit" class="button" type="submit" name="submit" value="POŠALJI"/>
                    </div>
                </div>
            </div>
        </form>
    </body>
</html>