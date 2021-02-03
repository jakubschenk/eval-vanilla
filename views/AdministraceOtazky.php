<?php

include 'templates/header.php';

$druh = $args[0];

new AdminOtazkyEditController($druh);

?>

<script>

function upravOtazku(id) {
    var select = document.getElementById("vyberDruhu" + id);
    var upravitBtn = document.getElementById("editBtn" + id);
    var ulozBtn = document.getElementById("ulozitOtazkuBtn" + id);
    var druh = document.getElementById("druh" + id);
    if(druh.innerText == 'otevřená') {
        var text = document.getElementById("textOtazky" + id);
        var textInput = document.getElementById("textOtazkyInput" + id);
        text.style.display = 'none';
        textInput.style.display = 'block';
    } else if (druh.innerText == 'výběrová') {
        var text = document.getElementById("textOtazky" + id);
        var textInput = document.getElementById("textOtazkyInput" + id);
        var levytext = document.getElementById("levytext" + id);
        var levytextInput = document.getElementById("levytextInput" + id);
        var pravytext = document.getElementById("pravytext" + id);
        var pravytextInput = document.getElementById("pravytextInput" + id);

        text.style.display = 'none';
        levytext.style.display = 'none';
        pravytext.style.display = 'none';
        textInput.style.display = 'block';
        levytextInput.style.display = 'block';
        pravytextInput.style.display = 'block';
    }
    ulozBtn.style.display = 'block';
    druh.style.display = 'none';
    select.style.display = 'block';
    upravitBtn.style.display = 'none';
}

function zmenTyp(id) {
    var druh = document.getElementById("druh" + id);
    var select = document.getElementById("vyberDruhu" + id);
    var text = document.getElementById("textOtazky" + id);
    var textInput = document.getElementById("textOtazkyInput" + id);
    var levytextInput = document.getElementById("levytextInput" + id);
    var pravytextInput = document.getElementById("pravytextInput" + id);

    if(select.value == "výběrová") {
        druh.innerText == "výběrová";
        levytextInput.style.display = 'block';
        pravytextInput.style.display = 'block';
    } else if (select.value == "otevřená") {
        druh.innerText == "otevřená";
        levytextInput.style.display = 'none';
        pravytextInput.style.display = 'none';   
    }
}

async function ulozOtazku(id) {
    var select = document.getElementById("vyberDruhu" + id);
    var upravitBtn = document.getElementById("editBtn" + id);
    var ulozBtn = document.getElementById("ulozitOtazkuBtn" + id);
    var druh = document.getElementById("druh" + id);
    var druh = document.getElementById("druh" + id);
    var select = document.getElementById("vyberDruhu" + id);
    var textInput = document.getElementById("textOtazkyInput" + id);
    var levytextInput = document.getElementById("levytextInput" + id);
    var pravytextInput = document.getElementById("pravytextInput" + id);
    var text = document.getElementById("textOtazky" + id);
    var levytext = document.getElementById("levytext" + id);
    var pravytext = document.getElementById("pravytext" + id);
    
    if(druh.innerText == "otevřená") {
        var otazka = {
            "id": id,
            "otazka": textInput.value,
            "druh": druh.innerText
        }

    } else if (druh.innerText == "výběrová") {
        var mujText = textInput.value + ';' + levytextInput.value + ';' + pravytextInput.value;
        var otazka = {
            id: id,
            otazka: mujText,
            druh: druh.innerText
        }
        levytext.innerText = levytextInput.value;
        pravytext.innerText = pravytextInput.value;
        levytext.style.display = "block";
        levytextInput.style.display = "none";
        pravytext.style.display = "block";
        pravytextInput.style.display = "none";
    }

    console.log(JSON.stringify(otazka));

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

    text.innerText = textInput.value;
    text.style.display = "block";
    textInput.style.display = "none";
    ulozBtn.style.display = 'none';
    druh.style.display = 'block';
    select.style.display = 'none';
    upravitBtn.style.display = 'block';
}
</script>
<?php
include 'templates/footer.php';
?>