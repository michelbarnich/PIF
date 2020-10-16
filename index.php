<?php
    include_once "scripts/setUpSession.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Automate your greenhouse!</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
    <pre>
        <?php
        print_r($_SESSION);
        ?>
    </pre>
    <?php
        if($_SESSION["login"]) {
            include_once "includes/main.html";
        } else {
            include_once "includes/login.html";
        }
    ?>
</body>
</html>