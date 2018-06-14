$(document).ready(function () {
    var response = '';
    $.ajax({
        type: "GET",
        url: "dohvatiOglase.php",
        async: false,
        success: function (text) {
            response = text;
        }
    });
    var oglasi = '';
    $.each(response, function(key,value) {
       $.each(value, function(k,v) {
           if(k == 'oglas_ID') {
               oglasi = oglasi + "<div onClick='klik("+v+")' id='" + v + "'>";
           }
           if(k == 'url') {
               oglasi = oglasi + "<a href='" + v + "' target='_blank'>";
           }
           if(k == 'slika') {
               oglasi = oglasi + "<img src='slike/" + v + "' width='275' height='200'></a></div>"
           }
       });
    });
    $('#slideshow').append(oglasi);
});