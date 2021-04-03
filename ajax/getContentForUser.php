<?php

    $silent = true; // if $silent is set to true, getUserData.php wont echo

    include_once "../scripts/connectToDatabase.php";
    include_once "../scripts/setUpSession.php";
    include_once "../ajax/getUserData.php";

    if($_GET["page_name"] == "shell" && $response["userData"]["role"] == 3) {
        include_once "../includes/shell.php";
    }

    if($_GET["page_name"] == "companies" && $response["userData"]["role"] == 3) {
        include_once "../includes/companies.html";
    }

    if($_GET["page_name"] == "sensors" && $response["userData"]["role"] == 3) {
        include_once "../includes/sensors.html";
    }

    if($_GET["page_name"] == "my account" && $response["userData"]["role"] >= 1) {
        include_once "../includes/my account.html";
    }

    if(($_GET["page_name"] == "accounts" || $_GET["page_name"] == "my company") && $response["userData"]["role"] >= 2) {
        if($_GET["page_name"] == "my company" && $response["userData"]["role"] == 3) {
            $bountToCompany = true;
        }
        include_once "../includes/accounts.html";
    }

    if($_GET["page_name"] == "my plants" && $response["userData"]["role"] >= 1) {
        include_once "../includes/my plants.html";
    }

    if($_GET["page_name"] == "notifications" && $response["userData"]["role"] >= 1) {
        include_once "../includes/notifications.html";
    }
?>