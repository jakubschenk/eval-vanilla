function upravOtazku() {
    if (this.druh.innerText == 'otevřená') {
        this.text.style.display = 'none';
        this.textInput.style.display = 'block';
    } else if (this.druh.innerText == 'výběrová') {
        this.text.style.display = 'none';
        this.levytext.style.display = 'none';
        this.pravytext.style.display = 'none';
        this.textInput.style.display = 'block';
        this.levytextInput.style.display = 'block';
        this.pravytextInput.style.display = 'block';
    }
    this.ulozBtn.style.display = 'block';
    this.druh.style.display = 'none';
    this.select.style.display = 'block';
    this.upravitBtn.style.display = 'none';
}

function zmenTyp() {
    if (this.select.value == "výběrová") {
        this.druh.innerText == "výběrová";
        this.levytextInput.style.display = 'block';
        this.pravytextInput.style.display = 'block';
    } else if (this.select.value == "otevřená") {
        this.druh.innerText == "otevřená";
        this.levytextInput.style.display = 'none';
        this.pravytextInput.style.display = 'none';
    }
}

async function smazOtazku() {
    var otazka = {
        "id": this.id
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
}

async function ulozOtazku() {

    if (this.druh.innerText == "otevřená") {
        var otazka = {
            "id": this.id,
            "otazka": this.textInput.value,
            "druh": this.druh.innerText
        }
    } else if (this.druh.innerText == "výběrová") {
        var mujText = this.textInput.value + ';' + this.levytextInput.value + ';' + this.pravytextInput.value;
        var otazka = {
            id: this.id,
            otazka: mujText,
            druh: this.druh.innerText
        }
        this.levytext.innerText = this.levytextInput.value;
        this.pravytext.innerText = this.pravytextInput.value;
        this.levytext.style.display = "block";
        this.levytextInput.style.display = "none";
        this.pravytext.style.display = "block";
        this.pravytextInput.style.display = "none";
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

    this.text.innerText = this.textInput.value;
    this.text.style.display = "block";
    this.textInput.style.display = "none";
    this.ulozBtn.style.display = 'none';
    this.druh.style.display = 'block';
    this.select.style.display = 'none';
    this.upravitBtn.style.display = 'block';
}

    function zmenTypNove() {
    var levytextInput = document.getElementById("levytextInputNova");
    var pravytextInput = document.getElementById("pravytextInputNova");
    var select = document.getElementById("vyberDruhuNova");

    if (select.value == "otevřená") {
        levytextInput.style.display = "none";
        pravytextInput.style.display = "none";
    } else if (select.value = "výběrová") {
        levytextInput.style.display = "block";
        pravytextInput.style.display = "block";
    }
}

    function zobrazPridaniOtazky() {
    var div = document.getElementById("pridatOtazku");
    var btn = document.getElementById("pridatOtazkuBtn");
    var textInput = document.getElementById("textOtazkyInputNova");
    var levytextInput = document.getElementById("levytextInputNova");
    var pravytextInput = document.getElementById("pravytextInputNova");
    var select = document.getElementById("vyberDruhuNova");
    var pridatBtn = document.getElementById("pridatNovaBtn");

    $('textOtazkyInputNova').removeClass('d-none');
    console.log("WTF");
    levytextInput.style.display = "block";
    pravytextInput.style.display = "block";
    select.style.display = "block";
    pridatBtn.style.display = "block";
}
    
    async function pridatOtazku() {
    var textInput = document.getElementById("textOtazkyInputNova");
    var levytextInput = document.getElementById("levytextInputNova");
    var pravytextInput = document.getElementById("pravytextInputNova");
    var select = document.getElementById("vyberDruhuNova");
    var otazka = {};
    if (select.value == "otevřená") {
        otazka["text"] = textInput.value;
        otazka["druh"] = select.value;
    } else if (select.value = "výběrová") {
        otazka["text"] = textInput.value + ";" + levytextInput.value + ";" + pravytextInput.value;
        otazka["druh"] = select.value;
    }

    await fetch('pridat', {
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
}