$(document).ready(function() {

    $("#selectRok").change(async function() {
        var selRok = $("#selectRok").val();
        var rok = {rok: selRok};
        
        await fetch('prohlizeni/zmenProhlizenyRok', {
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

    if(listStudent.length > 0) {
        listStudent.forEach(element => {
            $("#otazka"+element).click(async function() {
                let q = {
                    q: element
                }
                await fetch('prohlizeni/getOtazkaStatStudent', {
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
    
    function parseQuestionData(data, element) {
        var div = $('#obsah'+element).children();
        var a = div[0];
        var celek = 0, i = 0;
        data.forEach(element => {
            celek += parseInt(element.odpoved);
            console.log(element);
            console.log(i); 
            i++;
        });
        var prum = celek/i;
        $('#obsah'+element).children().text("Průměrná známka: " + prum);
    }
});