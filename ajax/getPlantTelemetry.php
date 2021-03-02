<?php
    $silent = true; // if $silent is set to true, getUserData.php wont echo

    // Connecting to Database
    include_once "../scripts/connectToDatabase.php";
    include_once "../scripts/setUpSession.php";
    include_once "../ajax/getUserData.php";
    include_once "../scripts/parseURLForQueries.php";

    if ($response["userData"]["role"] >= 1 && $queries["page_name"] == "my plants") {

        $response = [];

        if($_GET["latest"] == true) {
            $sql = "SELECT * FROM tblsensordaten WHERE fiSensorStation = ? ORDER BY dtTimestamp DESC LIMIT 1;";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $_GET["sensorID"]);
            $stmt->execute();
            $result = $stmt->get_result();
            $response["SQLError"] = $stmt->error;

            for ($i = 0; $i < $result->num_rows; $i++) {
                $row = $result->fetch_assoc();

                $response["plantTelemetry"] = [
                    "humidity" => replaceSpecialChars($row["dtLuftfeuchtigkeit"]),
                    "temperature" => replaceSpecialChars($row["dtTemperatur"]),
                    "light" => replaceSpecialChars($row["dtHelligkeit"]),
                    "moisture" => replaceSpecialChars($row["dtBodenfeuchtigkeit"])
                ];
            }

        } else {

        }

    }

    echo json_encode($response);