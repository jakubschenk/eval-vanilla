$('#closeMenu').click(function () {
    $('#mySidepanel').width(0);
});

$('#openMenu').click(function () {
    $('#mySidepanel').width(320);
});

$(window).scroll(function() {
    $('#mySidepanel').width(0);
});