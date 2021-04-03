<?php
$silent = true; // if $silent is set to true, getUserData.php wont echo

// Connecting to Database
include_once "../scripts/connectToDatabase.php";
include_once "../scripts/setUpSession.php";
include_once "../ajax/getUserData.php";
include_once "../scripts/parseURLForQueries.php";

if ($response["userData"]["role"] >= 1 ) {

    if($_GET["value"] == 3) {
        $fields = "dtMinimumBodenfeuchtigkeit = ?, dtMaximumBodenfeuchtigkeit = ?";
    } elseif($_GET["value"] == 0) {
        $fields = "dtMinimumLuftfeuchtigkeit = ?, dtMaximumLuftfeuchtigkeit = ?";
    } elseif($_GET["value"] == 2) {
        $fields = "dtMinimumHelligkeit = ?, dtMaximumHelligkeit = ?";
    } elseif($_GET["value"] == 1) {
        $fields = "dtMinimumTemperatur = ?, dtMaximumTemperatur = ?";
    }

    $sql = "SELECT * FROM tblbenachrichtigung WHERE fiSensor = ? AND fiErstelltVon = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $_GET["sensorID"], $_SESSION["id"]);
    $stmt->execute();
    $result = $stmt->get_result();
    $response["SQLError"] = $stmt->error;

    if($result->num_rows != 1) {

        $sql = "INSERT INTO tblbenachrichtigung(fiSensor, fiErstelltVon) VALUES( ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $_GET["sensorID"], $_SESSION["id"]);
        $stmt->execute();
        $result = $stmt->get_result();
        $response["SQLError"] = $stmt->error;

    }


    $sql = "UPDATE tblbenachrichtigung SET " . $fields . " WHERE  fiSensor = ? AND fiErstelltVon = ? ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ddss", $_GET["min"], $_GET["max"], $_GET["sensorID"], $_SESSION["id"]);
    $stmt->execute();
    $result = $stmt->get_result();
    $response["SQLError"] = $stmt->error;

}

echo json_encode($response);
