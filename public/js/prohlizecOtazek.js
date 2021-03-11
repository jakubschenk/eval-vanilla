$(document).ready(function() {

    $("#selectRok").change(async function() {
        var selRok = $("#selectRok").val();
        var rok = {rok: selRok};
        
        await fetch('/administrace/prohlizeni/zmenProhlizenyRok', {
            method: "POST",
            mode: "same-origin",
            credentials: "same-origin",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(rok)
        })
            .then(response => console.log(response))
            .catch(err => console.log(err));
    
        location.reload();
    });

    var typ = window.location.pathname.replace("/administrace/prohlizeni/", '');
    if(typ == "student") {
        if(listStudent.length > 0) {
            listStudent.forEach(element => {
                $("#otazka"+element).click(async function() {
                    let q = {
                        q: element
                    }
                    await fetch('/administrace/prohlizeni/getOtazkaStatStudent', {
                        method: "POST",
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify(q),
                    })
                    .then(response => response.json())
                    .then(data => {
                        parseQuestionData(data,element);
                    })
                    .catch((error) => {
                        console.error('Error: ', error);
                    })
                });
            });
        } else {
            console.log("Tento rok nemá žádné data!");      
        }
    } else if (typ == "ucitel") {
        if(listUcitel.length > 0) {
            listUcitel.forEach(element => {
                $("#otazka"+element).click(async function() {
                    let q = {
                        q: element
                    }

                    console.log(q);
                    await fetch('/administrace/prohlizeni/getOtazkaStatUcitel', {
                        method: "POST",
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify(q),
                    })
                    .then(response => response.json())
                    .then(data => {
                        parseQuestionData(data,element);
                    })
                    .catch((error) => {
                        console.error('Error: ', error);
                    })
                });
            });
        } else {
            console.log("Tento rok nemá žádné data!");      
        }
    }
    
    function parseQuestionData(data, element) {
        if(data[0].druh == "výběrová") {
            var celek = 0, i = 0;
            data.forEach(otazka => {
                celek += parseInt(otazka.odpoved);
                i++;
            });
            var prum = celek/i;
            $('#obsah'+element).children().text("Známka: " + prum);
        } else if (data[0].druh == "otevřená") {
            var i = 1;
            $('#obsah'+element).children().html("<h5>Odpovědi</h5>");
            data.forEach(otazka => {
                $('#obsah'+element).children().append("<p>" + i + ". " + otazka.odpoved + "</p>");
            });   
        }
        
    }
});