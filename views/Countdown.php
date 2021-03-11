<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <link rel="stylesheet" type="text/css" href="/public/css/landing.css"/>
    <title>evaluační nástroj spšei</title>
    <script src="https://apis.google.com/js/api:client.js"></script>
</head>
<body>
    <div class="main-div">
        <div class="spsei-logo">
            <div class="animate-bottom">
                <div class="fade-in">
                    <img class="logo" src="/public/images/spsei-logo.png" alt="logo spšei"/>
                </div>
                <div class="fade-in-delay">
                    <p class="text">evaluační nástroj spšei Ostrava</p>
                </div>
            </div>
        </div>
        <div class="fade-in-delay container justify-content-center">
            <h5>Čas do přístupu: </h5>
            <div id="clock">
            </div>
        </div>
    </div>
    <div id="name"></div>
    <script>var cas_od = "<?php echo Config::getValueFromConfig("pristup_od"); ?>";</script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
    <script src="/public/js/casovac.js"></script>
</body>
</html>