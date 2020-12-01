<?php
    include_once "../scripts/replaceSpecialChars.php";
    include_once "../scripts/connectToDatabase.php";
    include_once "../scripts/setUpSession.php";

    if($conn != false) {
        // Requesting Data from SQL Server
        $sql = "SELECT *, tblbenutzer.dtName AS nutzerName, CASE WHEN fiFirma IS NULL THEN '' ELSE tblfirma.dtname END AS firmenname FROM tblbenutzer, tblfirma WHERE idIdentifikationsNummer = ?;";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $_SESSION["id"]);
        $stmt->execute();
        $result = $stmt->get_result();
        $response["SQLError"] = $stmt->error;

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            $response["userData"] = [
                "email" => replaceSpecialChars($row["dtEmail"]),
                "role" => replaceSpecialChars($row["dtRolle"]),
                "name" => replaceSpecialChars($row["nutzerName"]),
                "firstName" => replaceSpecialChars($row["dtVorname"]),
                "company" => replaceSpecialChars($row["firmenname"]),
                "companyId" => replaceSpecialChars($row["fiFirma"])
            ];

        }

    }

    // output
    if(!isset($silent) || $silent == false) {
        echo json_encode($response);
    }
?>