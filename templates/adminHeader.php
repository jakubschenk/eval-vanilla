<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="/public/css/navigace.css" />
    <?php
    if (!empty($args['stylesheets'])) {
        foreach ($args['stylesheets'] as $stylesheet) {
            echo ('<link rel="stylesheet" type="text/css" href="/public/css/' . $stylesheet . '.css"/>');
        }
    }
    ?>
    <title><?php echo ($pageName); ?></title>
</head>
<body>

    <nav class="navbar navbar-dark py-2">
        <a class="navbar-brand px-3 py-2" href="/administrace">
            <img src="/public/images/spsei-logo.png" width="160" height="40" alt="">
        </a>
        <div class="nav navbar-nav navbar-center px-3 mid-text">
            <span class="text-white">Administrace evaluační aplikace</span>
        </div>
        <div class="form-inline px-2">
            <p class="mx-auto my-auto pr-2 text-white"><?php echo $_SESSION['login']; ?></p>
            <a href="/auth/google/logout" class="btn mx-auto btn-outline-secondary text-white" id="logout">
                <span class="logout-text">Odhlásit se</span>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-arrow-bar-right logout-icon" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M6 8a.5.5 0 0 0 .5.5h5.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L12.293 7.5H6.5A.5.5 0 0 0 6 8zm-2.5 7a.5.5 0 0 1-.5-.5v-13a.5.5 0 0 1 1 0v13a.5.5 0 0 1-.5.5z" />
                </svg>
            </a>
    </nav>
    <div id="mySidepanel" class="sidepanel">
        <a id="closeMenu" class="closebtn btn">&times;</a>
        <a href="/administrace">Domů</a>
        <a href="/administrace/prohlizeni">Procházet odpovědi</a>
        <a href="/administrace/import">Import dat</a>
        <a href="/administrace/import">Export dat</a>
        <a href="/administrace/student/uzivatele/upravit">Upravit studenty</a>
        <a href="/administrace/ucitel/uzivatele/upravit">Upravite učitele</a>
        <a href="/administrace/student/otazky/upravit">Upravit otázky pro studenty</a>
        <a href="/administrace/student/otazky/upravit">Upravit otázky pro učitele</a>
        <a href="/administrace/nastaveni">Nastavení</a>
    </div>
    <button class="openbtn ml-2 mt-2 rounded" id="openMenu">&#9776;</button>
    <main>
        <div class="container obsah">