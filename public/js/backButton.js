$(document).ready(function() {
    console.log(window.location.href);
    console.log(window.location.origin);
    console.log(document.referrer);
    if(window.location.href == window.location.origin+"/") {
        $('#backButton').hide();
    } else {
        $('#backButton').attr("href", document.referrer);
    }
});