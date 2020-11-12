<?php
    $silent = true; // if $silent is set to true, getUserData.php wont echo

    include_once "../scripts/connectToDatabase.php";
    include_once "../scripts/setUpSession.php";
    include_once "../ajax/getUserData.php";

    if($_GET["page_name"] == "system" && $response["userData"]["role"] == 3) {
        include_once "../includes/system.php";
    }

    if($_GET["page_name"] == "my account" && $response["userData"]["role"] >= 1) {
        include_once "../includes/my account.html";
    }

    if($_GET["page_name"] == "accounts" && $response["userData"]["role"] == 3) {
        include_once "../includes/accounts.html";
    }
?>