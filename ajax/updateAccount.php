<?php

    if(isset($_SESSION["id"])) {



    } else {
        echo json_encode("NotLoggedIn");
    }

?>