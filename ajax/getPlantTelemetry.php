<?php
    $silent = true; // if $silent is set to true, getUserData.php wont echo

    // Connecting to Database
    include_once "../scripts/connectToDatabase.php";
    include_once "../scripts/setUpSession.php";
    include_once "../ajax/getUserData.php";
    include_once "../scripts/parseURLForQueries.php";

    if ($response["userData"]["role"] >= 1 ) {

        $sql = "SELECT fiFirma FROM tblsensorstation WHERE idIdentifikationsNummer = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $_GET["sensorID"]);
        $stmt->execute();
        $result = $stmt->get_result();
        $response["SQLError"] = $stmt->error;

        $row = $result->fetch_assoc();

        if($row["fiFirma"] == $response["userData"]["companyId"]) {

            $response = [];

            if(!isset($_GET["dateTo"]) && !isset($_GET["dateFrom"])) {

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

                if($_GET["intervalRate"] == "h") {
                    $interval = 3600;
                } elseif($_GET["intervalRate"] == "d") {
                    $interval = 86400 ;
                } elseif($_GET["intervalRate"] == "w") {
                    $interval = 604800 ;
                } elseif($_GET["intervalRate"] == "m") {
                    $interval = 2592000  ;
                } elseif($_GET["intervalRate"] == "y") {
                    $interval = 31536000 ;
                }

                $iterations = ceil(($_GET["dateTo"] - $_GET["dateFrom"]) / $interval);
                $dataArray = [];

                for ($i = 0; $i < $iterations; $i++) {

                    //echo ($_GET["dateFrom"] + ($i * $interval)) . ", " . ($_GET["dateFrom"] + (($i + 1) * $interval)) . "<br>";

                    $sql = "SELECT AVG(dtTemperatur), AVG(dtLuftfeuchtigkeit), AVG(dtBodenfeuchtigkeit), AVG(dtHelligkeit) FROM tblsensordaten WHERE idSensorDatenNummer IN (SELECT idSensorDatenNummer  FROM tblsensordaten WHERE dtTimestamp > FROM_UNIXTIME(" . ($_GET["dateFrom"] + ($i * $interval)) . ") AND dtTimestamp < FROM_UNIXTIME(" . ($_GET["dateFrom"] + (($i + 1) * $interval)) . ") AND fiSensorStation = ?)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("s", $_GET["sensorID"]);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $response["SQLError"] = $stmt->error;

                    $row = $result->fetch_assoc();
                    array_push($dataArray, [ replaceSpecialChars($row["AVG(dtLuftfeuchtigkeit)"]), replaceSpecialChars($row["AVG(dtTemperatur)"]), replaceSpecialChars($row["AVG(dtHelligkeit)"]), replaceSpecialChars($row["AVG(dtBodenfeuchtigkeit)"]), ($_GET["dateFrom"] + ($i * $interval))]);

                }

                array_push($response, $dataArray);

            }

        }

    }

    echo json_encode($response);