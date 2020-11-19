<?php
    $silent = true; // if $silent is set to true, getUserData.php wont echo

    include_once "../scripts/connectToDatabase.php";
    include_once "../scripts/setUpSession.php";
    include_once "../ajax/getUserData.php";

    // checking some permissions
    if ($response["userData"]["role"] >= 2) {

        // modifying query depending on the user's persmissions
        if($response["userData"]["role"] == 2 && $response["userData"]["companyId"] != "") {
            $sqlAddition = " WHERE idFirmenNummer = " . $response["userData"]["companyId"];
        } else {
            $sqlAddition = "";
        }

        // selecting Data from Database
        $sql = "SELECT * FROM tblfirma" . $sqlAddition . ";";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        $response["SQLError"] = $stmt->error;

        $companyArray = [];

        // creating Array for JSON response
        for ($i = 0; $i < $result->num_rows;$i++) {

            $row = $result->fetch_assoc();

            array_push($companyArray,
                [
                    "name" => $row["dtName"],
                    "address" => $row["dtAdresse"],
                    "id" => $row["idFirmenNummer"],
                ]
            );

        }

        // inserting Data to JSON response
        $response["companyList"] = $companyArray;

        // encode Data in JSON format and echo it out
        echo json_encode($response);
    }
?>