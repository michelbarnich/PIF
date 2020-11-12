<?php
    $silent = true; // if $silent is set to true, getUserData.php wont echo

    include_once "../scripts/connectToDatabase.php";
    include_once "../scripts/setUpSession.php";
    include_once "../ajax/getUserData.php";

    if ($response["userData"]["role"] == 3) {

        $sql = "SELECT * FROM tblBenutzer";
        $stmt = $conn->prepare($sql);
        //$stmt->bind_param("s", $_SESSION["id"]);
        $stmt->execute();
        $result = $stmt->get_result();
        $response["SQLError"] = $stmt->error;

        $returnUserArray = [];

        for ($i = 0; $i < $result->num_rows; $i++) {

            $row = $result->fetch_assoc();

            array_push($returnUserArray, [
                "email" => $row["dtEmail"],
                "surname" => $row["dtName"],
                "name" => $row["dtVorname"],
                "role" => $row["dtRolle"]
            ]);

        }

        $response["userList"] = $returnUserArray;

        echo json_encode($response);
    }
?>