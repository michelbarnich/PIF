<?php
    include_once "../scripts/connectToDatabase.php";
    include_once "../scripts/setUpSession.php";

    if($conn != false) {
        // Requesting Data from SQL Server
        $sql = "SELECT * FROM tblBenutzer WHERE idIdentifikationsNummer = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $_SESSION["id"]);
        $stmt->execute();
        $result = $stmt->get_result();
        $response["SQLError"] = $stmt->error;

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            $response["userData"] = [
                "email" => $row["dtEmail"],
                "role" => $row["dtRolle"],
                "company" => $row["fiFirma"],
                "name" => $row["dtName"],
                "firstName" => $row["dtVorname"]
            ];

        }

    }

    // output
    if(!isset($silent) || $silent == false) {
        echo json_encode($response);
    }
?>