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

    function feedOtazky() {
        console.log("pog");
    }
});