$(document).ready(function() {
    var countTo = new Date(cas_od).getTime();

    var x = setInterval(function() {
        var ted = new Date().getTime();
        var vzdalenost = countTo - ted;

        var dny = Math.floor(vzdalenost/(1000 * 60 * 60 * 24));
        var hodiny = Math.floor((vzdalenost % (1000 * 60 * 60 * 24))/(1000 * 60 * 60));
        var minuty = Math.floor((vzdalenost % (1000 * 60 * 60))/(1000 * 60));
        var sekundy = Math.floor((vzdalenost % (1000 * 60))/1000);

        $("#clock").text(dny + "d " + hodiny + "h "+ minuty + "m " + sekundy + "s ");

        if (vzdalenost < 0) {
            clearInterval(x);
            location.reload();
        }
    }, 1000);
});