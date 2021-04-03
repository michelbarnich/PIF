<?php
    $silent = true; // if $silent is set to true, getUserData.php wont echo

    // Connecting to Database
    include_once "../scripts/connectToDatabase.php";
    include_once "../scripts/setUpSession.php";
    include_once "../ajax/getUserData.php";

    if ($response["userData"]["role"] >= 1 ) {

        if($_GET["value"] == "moisture") {
            $fields = "dtMinimumBodenfeuchtigkeit AS min, dtMaximumBodenfeuchtigkeit AS max";
        } elseif($_GET["value"] == "humidity") {
            $fields = "dtMinimumLuftfeuchtigkeit AS min, dtMaximumLuftfeuchtigkeit AS max";
        } elseif($_GET["value"] == "light") {
            $fields = "dtMinimumHelligkeit AS min, dtMaximumHelligkeit AS max";
        } elseif($_GET["value"] == "temperature") {
            $fields = "dtMinimumTemperatur AS min, dtMaximumTemperatur AS max";
        }

        $sql = "SELECT " . $fields . " FROM tblbenachrichtigung WHERE fiSensor = ? AND fiErstelltVon = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $_GET["sensorID"], $_SESSION["id"]);
        $stmt->execute();
        $result = $stmt->get_result();
        $response["SQLError"] = $stmt->error;

        $row = $result->fetch_assoc();

        $response["values"] = [
            "max" => $row["max"],
            "min" => $row["min"]
        ];

    }

    echo json_encode($response);
