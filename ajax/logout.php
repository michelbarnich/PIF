<?php
    include_once "../scripts/setUpSession.php";

    setcookie (session_id(), "", time() - 3600);
    session_destroy();
    session_write_close();
    
    $response["logoutSuccess"] = true;

    echo json_encode($response);
?>