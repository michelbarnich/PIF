<?php
    // check if all fields are set, and sensor data fields are float
    if(
        isset($_POST["id"]) &&
        isset($_POST["moisture"]) && is_float((float)$_POST["moisture"]) &&
        isset($_POST["light"]) && is_float((float)$_POST["light"]) &&
        isset($_POST["temp"]) && is_float((float)$_POST["temp"]) &&
        isset($_POST["humidity"]) && is_float((float)$_POST["humidity"])
    ) {
        // if so, check if sensorStation does exist in database
        include_once "../scripts/connectToDatabase.php";

        if($conn != false) {
            // Requesting Data from SQL Server
            $sql = "SELECT * FROM tblsensorstation WHERE idIdentifikationsNummer = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $_POST["id"]);
            $stmt->execute();
            $result = $stmt->get_result();
            $response["SQLError"] = $stmt->error;

            if ($result->num_rows == 1) {
                // Sensor does exist in database
                // Inserting new dataset
                $sql = "INSERT INTO tblsensordaten(fiSensorStation, dtLuftfeuchtigkeit, dtHelligkeit, dtBodenfeuchtigkeit, dtTemperatur) VALUES(?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param( "sdddd",
                    $_POST["id"],
                    $_POST["humidity"],
                    $_POST["light"],
                    $_POST["moisture"],
                    $_POST["temp"]
                );
                $stmt->execute();
                $response["SQLErrorInsert"] = $stmt->error;

            }

            // Closing connection to avoid mem leak
            $conn->close();

            echo json_encode($response);
        }
    }
?>