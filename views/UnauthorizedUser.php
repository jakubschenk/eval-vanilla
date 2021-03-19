<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <title>evaluační nástroj spšei</title>
    <link rel="stylesheet" href="public/css/unauthorized.css">
</head>
<body>
    <div class="main-div">
        <img src="public/images/spsei-logo.png"/>
        <p>Nejste v databázi povolených uživatelů pro tento školní rok!</p><br>
        <?php 
        if(isset($_GET["email"])) {
            if(strpos($_GET["email"],'@spseiostrava.cz') == false)
                echo '<p>Přihlašte se přes školní účet!</p>';
        }
        header("Refresh: 5;URL='/'"); 
        ?>
    </div>
</body>
</html>