<?php
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
        <script src="js/oglasi.js"></script>
        <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.0.3/cookieconsent.min.css" />
        <script src="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.0.3/cookieconsent.min.js"></script>
        <script>
            window.addEventListener("load", function () {
                window.cookieconsent.initialise({
                    "palette": {
                        "popup": {
                            "background": "#fde428",
                            "text": "#002e5b"
                        },
                        "button": {
                            "background": "#002e5b",
                            "text": "#ffffff"
                        }
                    },
                    "theme": "classic",
                    "content": {
                        "message": "Ova stranica koristi kolačiće kako bi vam osigurala što je moguće bolje iskustvo.",
                        "dismiss": "Prihvaćam",
                        "link": "Naslovnica",
                        "href": "http://barka.foi.hr/WebDiP/2017_projekti/WebDiP2017x066/"
                    }
                })
            });
        </script>
        <title>Naslovnica</title>
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
                window.location.href = "klik.php?idOglasa=" + id + "&prethodna=index";
            }
        </script>
    </body>
</html>
