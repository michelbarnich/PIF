<?php
    $silent = true; // if $silent is set to true, getUserData.php wont echo

    include_once "../scripts/replaceSpecialChars.php";
    include_once "../scripts/connectToDatabase.php";
    include_once "../scripts/setUpSession.php";
    include_once "../ajax/getUserData.php";
    include_once "../scripts/parseURLForQueries.php";

    if($conn != false) {
        if ($response["userData"]["role"] >= 2 && $queries["page_name"] == "my plants") {
            // Requesting Data from SQL Server
            $sql = "SELECT * FROM tblsensorstation";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $_GET["id"]);
            $stmt->execute();
            $result = $stmt->get_result();
            $response["SQLError"] = $stmt->error;

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                /*
                $response["userData"] = [
                    "email" => replaceSpecialChars($row["dtEmail"]),
                    "role" => replaceSpecialChars($row["dtRolle"]),
                    "name" => replaceSpecialChars($row["nutzerName"]),
                    "firstName" => replaceSpecialChars($row["dtVorname"]),
                    "company" => replaceSpecialChars($row["firmenname"]),
                    "companyId" => replaceSpecialChars($row["fiFirma"])
                ];
                */

            }

        } elseif ($response["userData"]["role"] >= 2 && $queries["page_name"] == "sensors") {
            $sql = "SELECT fiFirma, idIdentifikationsNummer FROM tblsensorstation WHERE idIdentifikationsNummer = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $_GET["id"]);
            $stmt->execute();
            $result = $stmt->get_result();
            $response["SQLError"] = $stmt->error;

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $response["sensorData"] = [
                    "sensorID" => $row["idIdentifikationsNummer"],
                    "companyId" => replaceSpecialChars($row["fiFirma"])
                ];

            }
        }
    }

    echo json_encode($response);
?>