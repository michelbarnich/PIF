<?php
    $silent = true; // if $silent is set to true, getUserData.php wont echo

    include_once "../scripts/connectToDatabase.php";
    include_once "../scripts/setUpSession.php";
    include_once "../ajax/getUserData.php";

    if($response["userData"]["role"] == 3) {

        if ($conn != false) {
            // Deleting Dataset
            $sql = "DELETE FROM tblbenutzer WHERE idIdentifikationsNummer = ?";
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

    echo json_encode($response);
?>
