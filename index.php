<?php
    include_once "scripts/setUpSession.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Automate your greenhouse!</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0,user-scalable=0"/>
</head>
<body>
    <?php
        if(isset($_SESSION["login"]) && isset($_SESSION["id"])) {
            include_once "includes/main.html";
        } else {
            include_once "includes/login.html";
        }
    ?>
</body>
</html>