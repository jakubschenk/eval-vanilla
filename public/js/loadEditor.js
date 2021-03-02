$(document).ready(function () {
    $('.alert').hide();

    $('#pridatNovaBtn').click(async function () {
        var textInput = $("#textOtazkyInputNova");
        var levytextInput = $("#levytextInputNova");
        var pravytextInput = $("#pravytextInputNova");
        var select = $("#vyberDruhuNova");
        var otazka = {};


        if (select.val() == "otevřená") {
            if (textInput.val() != "") {
                otazka["text"] = textInput.val();
                otazka["druh"] = select.val();
            } else {
                alert("Některá pole nebyla vyplněna!");
                return;
            }
        } else if (select.val() == "výběrová") {
            if (textInput.val() != "" && levytextInput.val() != "" && pravytextInput.val() != "") {
                otazka["text"] = textInput.val() + ";" + levytextInput.val() + ";" + pravytextInput.val();
                otazka["druh"] = select.val();
                console.log(otazka);
            } else {
                alert("Některá pole nebyla vyplněna!");
                return;
            }
        }


        await fetch('pridat', {
            method: "POST",
            mode: "same-origin",
            credentials: "same-origin",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(otazka)
        }).then(response => console.log(response))
            .catch(err => console.log(err));

        location.reload();
    });

    $('#vyberDruhuNova').change(function () {
        $('label[for=levytextInputNova], #levytextInputNova').toggle();
        $('label[for=pravytextInputNova], #pravytextInputNova').toggle();
    });

    otazky.forEach(element => {
        var id = element["id"];
        var poradi = element["poradi"];
        var druh = element["druh"];
        $('#vyberDruhu'+id).val(druh);
        if(druh == "otevřená") {
            $('label[for=levytextInput'+id+'], #levytextInput'+id).toggle();
            $('label[for=pravytextInput'+id+'], #pravytextInput'+id).toggle();
        }

        $('#ulozitOtazkuBtn'+id).click(async function() {
            var otazka = {};
            druh = $('#vyberDruhu'+id).val();
            if(druh == "otevřená") {
                otazka["otazka"] = $('#textOtazkyInput'+id).val();
            } else if (druh == "výběrová") {
                otazka["otazka"] = $('#textOtazkyInput'+id).val()+';'+$('#levytextInput'+id).val()+';'+$('#pravytextInput'+id).val();
            }
            otazka["druh"] = druh;
            otazka["id"] = id;

            $('#nadpis'+id).text(poradi + '. ' + $('#textOtazkyInput'+id).val());

            await fetch('ulozit', {
                method: "POST",
                mode: "same-origin",
                credentials: "same-origin",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify(otazka)
            })
                .then(response => console.log(response))
                .catch(err => console.log(err));

                $('#otazka'+id).collapse("hide");
        });

        $('#vyberDruhu'+id).change(function() {
            $('label[for=levytextInput'+id+'], #levytextInput'+id).toggle();
            $('label[for=pravytextInput'+id+'], #pravytextInput'+id).toggle();
        });

        $('#delBtn'+id).click(async function() {
            var otazka = {
                "id": id
            }
        
            await fetch('smazat', {
                method: "POST",
                mode: "same-origin",
                credentials: "same-origin",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify(otazka)
            })
                .then(response => console.log(response))
                .catch(err => console.log(err));
        
            location.reload();
        });

        $('#ukazZmenu'+id).click(function() {
            $('label[for=noveCisloPro'+id+'], #noveCisloPro'+id).removeClass("d-none");
            $('#zmenCislo'+id).removeClass("d-none");
        });

        $('#zmenCislo'+id).click(async function() {
            var starecislo, novecislo;
            var starecislo = element["poradi"];
            var novecislo = $('#noveCisloPro'+id).val();

            var poradnik = {
                "id": id,
                "old": starecislo,
                "new": novecislo
            }

            await fetch('zmenitCislo', {
                method: "POST",
                mode: "same-origin",
                credentials: "same-origin",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify(poradnik)
            })
                .then(response => console.log(response))
                .catch(err => console.log(err));
        
            location.reload();
        });
    });

});