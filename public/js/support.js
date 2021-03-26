$(document).ready(function() {

if(!Modernizr.inputtypes['datetime-local']) {
        $('input[type=datetime-local]').datetimepicker();
}

});
