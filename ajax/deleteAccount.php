<?php

    $silent = true; // if $silent is set to true, getUserData.php wont echo

    include_once "../scripts/connectToDatabase.php";
    include_once "../scripts/setUpSession.php";
    include_once "../ajax/getUserData.php";

    if($response["userData"]["role"] >= 2) {

        if($response["userData"]["role"] == 2) {
            $sqlAddition = " AND fiFirma = " . $response["userData"]["companyId"];
        } else {
            $sqlAddition = "";
        }

        $sql = "SELECT dtRolle FROM tblBenutzer WHERE idIdentifikationsNummer = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "i",
            $_POST["id"]
        );
        $stmt->execute();
        $response["SQLError"] = $stmt->error;
        $result = $stmt->get_result();

        $role = $result->fetch_assoc()["dtRolle"];
        echo $role;

        if ($response["userData"]["role"] < $role) {
            $response["deletedAccount"] = false;
        } else {
            if ($conn != false) {
                // Deleting Dataset
                $sql = "DELETE FROM tblbenutzer WHERE idIdentifikationsNummer = ?" . $sqlAddition;
                $stmt = $conn->prepare($sql);
                $stmt->bind_param(
                    "s",
                    $_POST["id"]
                );
                $stmt->execute();
                $response["SQLError"] = $stmt->error;

                $response["deletedAccount"] = true;
            }
        }

    }

    echo json_encode($response);
?>
