<?php

    $silent = true; // if $silent is set to true, getUserData.php wont echo

    include_once "../scripts/connectToDatabase.php";
    include_once "../scripts/setUpSession.php";
    include_once "../ajax/getUserData.php";

    if ($response["userData"]["role"] == 3 && isset($_POST["id"])) {

        $sql = "DELETE FROM tblsensordaten WHERE fiSensorStation = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $_POST["id"]);
        $stmt->execute();
        $result = $stmt->get_result();
        $response["SQLError"] = $stmt->error;

        // selecting Data from Database
        $sql = "DELETE FROM tblsensorstation WHERE idIdentifikationsNummer = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $_POST["id"]);
        $stmt->execute();
        $result = $stmt->get_result();
        $response["SQLError"] = $stmt->error;

        $response["deletedSensor"] = true;

    }

    // Closing connection to avoid mem leak
    $conn->close();

    // output
    echo json_encode($response);

?>