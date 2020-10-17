<?php
    include_once "../scripts/setUpSession.php";
    session_destroy();
    $response["logoutSuccess"] = true;

    echo json_encode($response);
?>