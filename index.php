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
<body onload="adjustZoom();">
    <pre>
        <?php
        print_r($_SESSION);
        ?>
    </pre>
    <?php
        if(isset($_SESSION["login"]) && isset($_SESSION["id"])) {
            include_once "includes/main.html";
        } else {
            include_once "includes/login.html";
        }
    ?>

<script>
    function adjustZoom() {
        document.body.style.zoom = (document.documentElement.clientWidth / 1920  * 100) + "%";
    }
</script>
</body>
</html>