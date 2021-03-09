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
    }); 

});