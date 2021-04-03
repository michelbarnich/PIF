<?php
    $silent = true; // if $silent is set to true, getUserData.php wont echo

    include_once "../scripts/connectToDatabase.php";
    include_once "../scripts/setUpSession.php";
    include_once "../ajax/getUserData.php";

    $response["notifications"] = [];

    $sql = "SELECT * FROM tblbenachrichtigung WHERE fiErstelltVon = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $_SESSION["id"]);
    $stmt->execute();
    $result = $stmt->get_result();
    $response["SQLError"] = $stmt->error;

    for ($i = 0; $i < $result->num_rows; $i++) {

        $row = $result->fetch_assoc();

        $sql = "SELECT * FROM tblsensorstation WHERE idIdentifikationsNummer = ? AND fiFirma = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $row["fiSensor"], $response["userData"]["companyId"]);
        $stmt->execute();
        $result2 = $stmt->get_result();
        $response["SQLError"] = $stmt->error;

        if($result2->num_rows > 0) {

            $sql3 = "SELECT * FROM tblsensordaten WHERE fiSensorStation  = ? ORDER BY dtTimestamp DESC LIMIT 1;";
            $stmt3 = $conn->prepare($sql3);
            $stmt3->bind_param("s", $row["fiSensor"]);
            $stmt3->execute();
            $result3 = $stmt3->get_result();
            $response["SQLError"] = $stmt3->error;

            $row3 = $result3->fetch_assoc();

            //print_r($row3);

            if($row["dtMinimumLuftfeuchtigkeit"] > $row3["dtLuftfeuchtigkeit"] && !is_null($row["dtMinimumLuftfeuchtigkeit"])) {
                array_push($response["notifications"], [
                    "sensorID" => $row["fiSensor"],
                    "cause" => "not humid enough"
                ]);
            }

            if($row["dtMaximumLuftfeuchtigkeit"] < $row3["dtLuftfeuchtigkeit"] && !is_null($row["dtMaximumLuftfeuchtigkeit"])) {
                array_push($response["notifications"], [
                    "sensorID" => $row["fiSensor"],
                    "cause" => "too humid"
                ]);
            }

            if($row["dtMinimumHelligkeit"] > $row3["dtHelligkeit"] && !is_null($row["dtMinimumHelligkeit"])) {
                array_push($response["notifications"], [
                    "sensorID" => $row["fiSensor"],
                    "cause" => "not bright enough"
                ]);
            }

            if($row["dtMaximumHelligkeit"] < $row3["dtLuftfeuchtigkeit"] && !is_null($row["dtMaximumHelligkeit"])) {
                array_push($response["notifications"], [
                    "sensorID" => $row["fiSensor"],
                    "cause" => "too bright"
                ]);
            }

            if($row["dtMinimumBodenfeuchtigkeit"] > $row3["dtBodenfeuchtigkeit"] && !is_null($row["dtMinimumBodenfeuchtigkeit"])) {
                array_push($response["notifications"], [
                    "sensorID" => $row["fiSensor"],
                    "cause" => "too moist"
                ]);
            }

            if($row["dtMaximumBodenfeuchtigkeit"] < $row3["dtBodenfeuchtigkeit"] && !is_null($row["dtMaximumBodenfeuchtigkeit"])) {
                array_push($response["notifications"], [
                    "sensorID" => $row["fiSensor"],
                    "cause" => "not moist enough"
                ]);
            }

            if($row["dtMinimumTemperatur"] > $row3["dtTemperatur"] && !is_null($row["dtMinimumTemperatur"])) {
                array_push($response["notifications"], [
                    "sensorID" => $row["fiSensor"],
                    "cause" => "too cold"
                ]);
            }

            if($row["dtMaximumTemperatur"] < $row3["dtTemperatur"] && !is_null($row["dtMaximumTemperatur"])) {
                array_push($response["notifications"], [
                    "sensorID" => $row["fiSensor"],
                    "cause" => "too warm"
                ]);
            }

        }

    }

    echo json_encode($response);