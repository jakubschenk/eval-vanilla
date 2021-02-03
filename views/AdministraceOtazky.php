<?php

include 'templates/header.php';

$druh = $args[0];

new AdminOtazkyEditController($druh);

?>

<script>

document.getElementById("druh1").innerText = i;

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

function ulozOtazku(id) {
    var druh = document.getElementById("druh" + id);
    var select = document.getElementById("vyberDruhu" + id);
    var text = document.getElementById("textOtazky" + id);
    var textInput = document.getElementById("textOtazkyInput" + id);
    var levytextInput = document.getElementById("levytextInput" + id);
    var pravytextInput = document.getElementById("pravytextInput" + id);
    
    if(druh.innerText == "otevřená") {
        var otazka = {
            "id": id,
            "otazka": textInput,
            "druh": druh
        }
    } else if (druh.innerText == "výběrová") {
        var mujText = textInput + ';' + levytextInput + ';' + pravytextInput;
        var otazka = {
            "id": id,
            "otazka": mujText,
            "druh": druh
        }
    }

    //TODO: fetch
    
}
</script>
<?php
include 'templates/footer.php';
?>