<?php

// Connecting to Database
include_once "../scripts/connectToDatabase.php";
include_once "../scripts/setUpSession.php";

    if(isset($_SESSION["id"])) {
        $response["NotLoggedIn"] = false;

        if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
            $response["validEmail"] = false;
        }elseif($conn != false) {
            $response["validEmail"] = true;

            // Checking if email Address is already used
            $sql = "SELECT dtEmail, dtPasswortHash FROM tblBenutzer WHERE dtEmail = ? OR idIdentifikationsNummer = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $_POST["email"], $_SESSION["id"]);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows != 1) {
                $response["emailAlreadyInUse"] = true;
                $response["updatedAccount"] = false;
            } else {
                $response["emailAlreadyInUse"] = false;

                if($_POST["password"] != "") {
                    $passwordHash = hash("sha256", $_POST["password"]);
                } else {
                    $row = $result->fetch_assoc();
                    $passwordHash = $row["dtPasswortHash"];
                }

                //echo $passwordHash;

                if ($conn != false) {
                    // Inserting new dataset
                    $sql = "UPDATE tblBenutzer SET dtEmail = ? , dtPasswortHash = ?, dtVorname = ?, dtName = ? WHERE idIdentifikationsNummer = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param(
                        "sssss",
                        $_POST["email"],
                        $passwordHash,
                        $_POST["name"],
                        $_POST["surName"],
                        $_SESSION["id"]
                    );
                    $stmt->execute();
                    $response["SQLError"] =  $stmt->error;

                    $response["updatedAccount"] = true;

                } else {
                    $response["updatedAccount"] = false;
                }
            }
        }


    } else {
        $response["NotLoggedIn"] = true;
    }

    echo json_encode($response);
?>