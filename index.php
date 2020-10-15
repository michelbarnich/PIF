<?php
    // Start the session
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Automate your greenhouse!</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
    <?php

        if(isset($_SESSION["username"]) && isset($_SESSION["passwordHash"])) {
            include_once "includes/main.php";
        } else {
            include_once "includes/login.html";
        }
    ?>
</body>
</html>