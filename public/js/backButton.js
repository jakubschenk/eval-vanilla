$(document).ready(function() {
    if(window.location.href == window.location.origin+"/") {
        $('#backButton').hide();
    } else {
        $('#backButton').attr("href", document.referrer);
    }
});