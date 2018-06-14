<?php
include("../sesija.class.php");
include("../baza.class.php");


$idOglasa = "";
$prethodnaStranica = "";
$brojKlikova = "";
$db = new Baza();
$db->spojiDB();

if(isset($_GET["idOglasa"]) && isset($_GET["prethodna"])) {
    $idOglasa = $_GET["idOglasa"];
    $prethodnaStranica = $_GET["prethodna"];
}

$sql_oglas = "SELECT * FROM Oglas WHERE oglas_ID LIKE '" . $idOglasa . "'";
$oglas = $db->selectDB($sql_oglas);
if(mysqli_num_rows($oglas) > 0) {
    while($row = $oglas->fetch_assoc()) {
        $brojKlikova = $row["klikovi"];
    }
    $brojKlikova++;
    $sql_klik = "UPDATE Oglas SET klikovi = '" . $brojKlikova . "' WHERE oglas_ID LIKE '" . $idOglasa . "'";
    $dodanKlik = $db->selectDB($sql_klik);
    header("Location: " . $prethodnaStranica . ".php");
}
else {
    header("Location: " . $prethodnaStranica . ".php");
}

$db->zatvoriDB();