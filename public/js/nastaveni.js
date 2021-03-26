$(document).ready(function() {

    $('#zmenDatumBtn').click(async function() {
        var dat_od = $('#datum_od').val();
        var dat_do = $('#datum_do').val();       

        if((dat_od != "" || dat_do != "") && dat_od < dat_do ) {
            var datum = {
                datum_od: dat_od,
                datum_do: dat_do
            }
    
            console.log(datum);

            await fetch('nastaveni/zmenDatum', {
                method: "POST",
                mode: "same-origin",
                credentials: "same-origin",
                headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(datum)
            })
                .then(response => console.log(response))
                .catch(err => console.log(err));
        } else {
            alert("Zadejte správné datum!");
        }
        $('.container.obsah').prepend('<div class="alert alert-primary" role="alert">'
            + 'Datum přístupu bylo změněno!'
            + '</div>');
        $(window).scrollTop(0);
    });

    $('#smazPristupBtn').click(async function() {
        var smaz = {}; //tohle je hack, jelikoz json bude prazdny, datum_od a datum_do se nastavi na null

            await fetch('nastaveni/zmenDatum', {
                method: "POST",
                mode: "same-origin",
                credentials: "same-origin",
                headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(smaz)
            })
                .then(response => console.log(response))
                .catch(err => console.log(err));
        $('.container.obsah').prepend('<div class="alert alert-primary" role="alert">'
            + 'Datum přístupu bylo smazáno!'
            + '</div>');
        $(window).scrollTop(0);
    }); 

    $('#zmenHesloBtn').click(async function() {
        var stare = $("#heslo_stare");
        var nove = $("#heslo_nove");
        var nove_2 = $("#heslo_nove_potvrdit");

        let heslo = {
            "stare": stare.val(),
            "nove": nove.val()
        }

        if(nove.val() == nove_2.val()) {
            await fetch('nastaveni/zmenHeslo', {
                method:"POST",
                mode:"same-origin",
                credentials: "same-origin", 
                headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(heslo)
            })
            .then(response => console.log(response))
            .catch(err => console.log(err));

            location.reload();
        } else {
            alert("Hesla se neshodují!");
        }
    });

    async function smazAdmina(jmeno) {
        var admin = { "admin": jmeno }

        await fetch('nastaveni/smazAdmina', {
            method:"POST",
            mode:"same-origin",
            credentials: "same-origin", 
            headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify(admin)
        })
        .then(response => console.log(response))
        .catch(err => console.log(err));

        location.reload();
    }
});
