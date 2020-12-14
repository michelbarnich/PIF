<?php
    $silent = true; // if $silent is set to true, getUserData.php wont echo

    // Connecting to Database
    include_once "../scripts/connectToDatabase.php";
    include_once "../scripts/setUpSession.php";
    include_once "../ajax/getUserData.php";
    include_once "../scripts/parseURLForQueries.php";

    $sensorList = [];

    if ($response["userData"]["role"] >= 2 && $queries["page_name"] == "my plants") {

        $sql = "SELECT dtName, idIdentifikationsNummer FROM tblsensorstation WHERE fiFirma = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $response["userData"]["companyId"]);
        $stmt->execute();
        $result = $stmt->get_result();
        $response["SQLError"] = $stmt->error;

        for ($i = 0; $i < $result->num_rows; $i++) {
            $row = $result->fetch_assoc();

            array_push($sensorList, [
                "id" => replaceSpecialChars($row["idIdentifikationsNummer"]),
                "name" => replaceSpecialChars($row["dtName"])
            ]);
        }

    } elseif ($response["userData"]["role"] == 3) {

        $sql = "SELECT idIdentifikationsNummer, fiFirma FROM tblsensorstation";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        $response["SQLError"] = $stmt->error;

        for ($i = 0; $i < $result->num_rows; $i++) {
            $row = $result->fetch_assoc();

            if(!is_null($row["fiFirma"])) {
                $sql2 = "SELECT dtName FROM tblfirma WHERE idFirmenNummer = ?";
                $stmt2 = $conn->prepare($sql2);
                $stmt2->bind_param("i", $row["fiFirma"]);
                $stmt2->execute();
                $result2 = $stmt2->get_result();
                $response["SQLError"] = $stmt2->error;

                $row2 = $result2->fetch_assoc();
            } else {
                $row2["dtName"] = "-";
            }

            $sql3 = "SELECT dtTimestamp FROM tblsensordaten WHERE fiSensorStation  = ? ORDER BY dtTimestamp DESC LIMIT 1;";
            $stmt3 = $conn->prepare($sql3);
            $stmt3->bind_param("s", $row["idIdentifikationsNummer"]);
            $stmt3->execute();
            $result3 = $stmt3->get_result();
            $response["SQLError"] = $stmt3->error;

            $lastOnline = "-";

            if($result3->num_rows > 0) {
                $row3 = $result3->fetch_assoc();
                $lastOnline = replaceSpecialChars($row3["dtTimestamp"]);
            } else {
                $lastOnline = "N/A";
            }


            array_push($sensorList, [
                "id" => replaceSpecialChars($row["idIdentifikationsNummer"]),
                "companyId" => replaceSpecialChars($row["fiFirma"]),
                "company" => replaceSpecialChars($row2["dtName"]),
                "lastOnline" => replaceSpecialChars($lastOnline)
            ]);
        }

    }

    $response["sensorList"] = $sensorList;

    echo json_encode($response);