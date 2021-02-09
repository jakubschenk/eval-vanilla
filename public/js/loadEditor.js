let editory = new Array();

window.onload = function() {
    otazkyIds.forEach(function(value) {
        editory[value] = new EditorOtazek(value);
    });
}