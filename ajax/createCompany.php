<?php

    $silent = true; // if $silent is set to true, getUserData.php wont echo

    include_once "../scripts/connectToDatabase.php";
    include_once "../scripts/setUpSession.php";
    include_once "../ajax/getUserData.php";

    if ($response["userData"]["role"] == 3) {

        // selecting Data from Database
        $sql = "INSERT INTO tblfirma(dtName, dtAdresse) VALUES(?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $_POST["name"], $_POST["address"]);
        $stmt->execute();
        $result = $stmt->get_result();
        $response["SQLError"] = $stmt->error;

        $response["createdCompany"] = true;

    }

    // Closing connection to avoid mem leak
    $conn->close();

    // output
    echo json_encode($response);

?>