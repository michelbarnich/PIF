<?php

    $silent = true; // if $silent is set to true, getUserData.php wont echo

    include_once "../scripts/connectToDatabase.php";
    include_once "../scripts/setUpSession.php";
    include_once "../ajax/getUserData.php";

    if ($response["userData"]["role"] == 3 && isset($_POST["id"]) && isset($_POST["name"]) && $_POST["address"]) {
        if ($conn != false) {
            // Requesting Data from SQL Server
            $sql = "UPDATE tblfirma SET dtName = ?, dtAdresse= ? WHERE idFirmenNummer = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssi", $_POST["name"], $_POST["address"], $_POST["id"]);
            $stmt->execute();
            $result = $stmt->get_result();
            $response["SQLError"] = $stmt->error;

            $response["updatedCompany"] = true;

        }
    }

    echo json_encode($response);

?>