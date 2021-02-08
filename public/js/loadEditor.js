let editory = new Array();
editory.push(new EditorOtazek(0));

window.onload = function() {
    for(var i = 1; i <= pocetOtazek; i++) {
        editory.push(new EditorOtazek(i));
    }
}