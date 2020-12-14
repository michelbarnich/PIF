<?php

$silent = true; // if $silent is set to true, getUserData.php wont echo

include_once "../scripts/connectToDatabase.php";
include_once "../scripts/setUpSession.php";
include_once "../ajax/getUserData.php";

if ($response["userData"]["role"] == 3 && isset($_POST["id"]) && isset($_POST["companyId"])) {

    $sql = "SELECT * FROM tblfirma WHERE idFirmenNummer = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $_POST["companyId"]);
    $stmt->execute();
    $result = $stmt->get_result();
    $response["SQLError"] = $stmt->error;

    if($result->num_rows == 1 && strlen($_POST["id"]) == 36) {

        // selecting Data from Database
        $sql = "INSERT INTO tblsensorstation(idIdentifikationsNummer, fiFirma) VALUES(?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $_POST["id"], $_POST["companyId"]);
        $stmt->execute();
        $result = $stmt->get_result();
        $response["SQLError"] = $stmt->error;

        $response["createdSensor"] = true;
    } else {
        $response["createdSensor"] = false;

    }

}

// Closing connection to avoid mem leak
$conn->close();

// output
echo json_encode($response);

?>