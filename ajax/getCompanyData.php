<?php
    include_once "../scripts/replaceSpecialChars.php";

    $silent = true; // if $silent is set to true, getUserData.php wont echo

    include_once "../scripts/connectToDatabase.php";
    include_once "../scripts/setUpSession.php";
    include_once "../ajax/getUserData.php";

    if ($response["userData"]["role"] == 3 && isset($_POST["id"])) {
        if ($conn != false) {
            // Requesting Data from SQL Server
            $sql = "SELECT * FROM tblfirma WHERE idFirmenNummer = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $_POST["id"]);
            $stmt->execute();
            $result = $stmt->get_result();
            $response["SQLError"] = $stmt->error;

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();

                $response["companyData"] = [
                    "name" => replaceSpecialChars($row["dtName"]),
                    "address" => replaceSpecialChars($row["dtAdresse"])
                ];

            }

        }
    }

    echo json_encode($response);

?>