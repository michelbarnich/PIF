<?php
    include_once "../scripts/connectToDatabase.php";
    include_once "../scripts/setUpSession.php";

    if($_GET["page_name"] == "system") {
        include_once "../includes/system.php";
    }
?>