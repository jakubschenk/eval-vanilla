<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <?php
        if(!empty($stylesheets)) {
            foreach($stylesheets as $styleesheet) {
                echo('<link rel="stylesheet" type="text/css" href="/public/css/' . $stylesheet . '.css"/>');
            }
        }
    ?>
    <title><?php echo ($pageName); ?></title>
</head>
<body>
    
