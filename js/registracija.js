$(document).ready(function () {
    var simb1 = "!";
    var simb2 = "?";
    var simb3 = "#";
    $('#ime').keyup(function () {
        var ime = $("#ime").val();
        var zastavica = 0;
        if (ime.indexOf(simb1) != -1) {
            zastavica = 1;
        }
        if (ime.indexOf(simb2) != -1) {
            zastavica = 1;
        }
        if (ime.indexOf(simb3) != -1) {
            zastavica = 1;
        }
        if (zastavica === 0) {
            $('input[type="submit"]').removeAttr('disabled');
            $('#greske').html('');
            console.log('Sve u redu!');
        } else {
            $('input[type="submit"]').attr('disabled','disabled');
            $('#greske').html('<h2>Ime sadrži nedozvoljeni simbol!</h2><br>');
            console.log('Koristen nedozvoljeni simbol!');
        }
    });
    $('#prezime').keyup(function () {
        var prezime = $("#prezime").val();
        var zastavica = 0;
        if (prezime.indexOf(simb1) != -1) {
            zastavica = 1;
        }
        if (prezime.indexOf(simb2) != -1) {
            zastavica = 1;
        }
        if (prezime.indexOf(simb3) != -1) {
            zastavica = 1;
        }
        if (zastavica === 0) {
            $('input[type="submit"]').removeAttr('disabled');
            $('#greske').html('');
            console.log('Sve u redu!');
        } else {
            $('input[type="submit"]').attr('disabled','disabled');
            $('#greske').html('<h2>Prezime sadrži nedozvoljeni simbol!</h2><br>');
            console.log('Koristen nedozvoljeni simbol!');
        }
    });


    $('#korIme').keyup(function () {
        var zastavica = 0;
        var response = '';
        $.ajax({
            type: "GET",
            url: "korisnici.php",
            async: false,
            success: function (text) {
                response = text;
            }
        });
        var korisnickoIme = $("#korIme").val();
        var zastavica = response.indexOf(korisnickoIme);
        if(zastavica == -1) {
            $('input[type="submit"]').removeAttr('disabled');
            $('#greske').html('');
            console.log('Korisnik ne postoji!');
        }
        else {
            $('input[type="submit"]').attr('disabled','disabled');
            $('#greske').html('<h2>Korisnik sa tim korisničkim imenom već postoji!</h2><br>');
            console.log('Korisnik vec postoji!');
        }
    });
    
    $('#email').keyup(function () {
        var email = $("#email").val();
        var reg = new RegExp(/^\w+([-+.'][^\s]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/);
        if (reg.test(email)) {
            $('input[type="submit"]').removeAttr('disabled');
            $('#greske').html('');
        } else {
            $('input[type="submit"]').attr('disabled','disabled');
            $('#greske').html('<h2>Email nije valjanog formata!</h2><br>');
        }
    });

    $('#lozinka2').keyup(function () {
        var lozinka1 = $("#lozinka1").val();
        var lozinka2 = $("#lozinka2").val();
        if (lozinka2 !== lozinka1) {
            $('input[type="submit"]').attr('disabled','disabled');
            $('#greske').html('<h2>Lozinke nisu jednake!</h2><br>');
        } else {
            $('input[type="submit"]').removeAttr('disabled');
            $('#greske').html('');
        }
    });

});