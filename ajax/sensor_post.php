<?php
    // check if all fields are set, and sensor data fields are float
    if(
        isset($_POST["id"]) &&
        isset($_POST["moisture"]) && filter_var($_POST["moisture"], FILTER_VALIDATE_FLOAT) &&
        isset($_POST["light"]) && filter_var($_POST["light"], FILTER_VALIDATE_FLOAT) &&
        isset($_POST["temp"]) && filter_var($_POST["temp"], FILTER_VALIDATE_FLOAT) &&
        isset($_POST["humidity"]) && filter_var($_POST["humidity"], FILTER_VALIDATE_FLOAT)
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
                $stmt->bind_param( "ddddd",
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