<?php
    $silent = true; // if $silent is set to true, getUserData.php wont echo

    // Connecting to Database
    include_once "../scripts/connectToDatabase.php";
    include_once "../scripts/setUpSession.php";
    include_once "../ajax/getUserData.php";

    if(isset($_SESSION["id"])) {
        $response["NotLoggedIn"] = false;

        if ($response["userData"]["role"] >=2 && isset($_POST["id"])) {
            $id = $_POST["id"];
        } else {
            $id = $_SESSION["id"];
        }

        if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
            $response["validEmail"] = false;
        }elseif($conn != false) {
            $response["validEmail"] = true;

            // Checking if email Address is already used
            $sql = "SELECT dtEmail, dtPasswortHash FROM tblBenutzer WHERE dtEmail = ? OR idIdentifikationsNummer = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $_POST["email"], $id);
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

                if($response["userData"]["role"] == 3 && isset($_POST["companyId"])) {

                    // if update is requested by a System Admin

                    $companyId = $_POST["companyId"];

                    if ($_POST["companyId"] == -1) {
                        $companyId = NULL;
                    }

                    if ($conn != false) {

                        // Inserting new dataset
                        $sql = "UPDATE tblBenutzer SET dtEmail = ? , dtPasswortHash = ?, dtVorname = ?, dtName = ?, fiFirma = ?, dtRolle = ? WHERE idIdentifikationsNummer = ?";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param(
                            "sssssii",
                            $_POST["email"],
                            $passwordHash,
                            $_POST["name"],
                            $_POST["surName"],
                            $companyId,
                            $_POST["role"],
                            $id
                        );
                        $stmt->execute();
                        $response["SQLError"] = $stmt->error;

                        $response["updatedAccount"] = true;

                    } else {
                        $response["updatedAccount"] = false;
                    }

                } elseif ($response["userData"]["role"] == 2 && isset($_POST["companyId"])) {

                    if($_POST["role"] < 3) {

                        // Inserting new dataset
                        $sql = "UPDATE tblBenutzer SET dtEmail = ? , dtPasswortHash = ?, dtVorname = ?, dtName = ?, fiFirma = ?, dtRolle = ? WHERE idIdentifikationsNummer = ? AND fiFirma = " . $response["userData"]["companyId"];
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param(
                            "sssssii",
                            $_POST["email"],
                            $passwordHash,
                            $_POST["name"],
                            $_POST["surName"],
                            $companyId,
                            $_POST["role"],
                            $id
                        );
                        $stmt->execute();
                        $response["SQLError"] = $stmt->error;

                        $response["updatedAccount"] = true;
                    } else {
                        $response["updatedAccount"] = false;
                    }

                } else {
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
                            $id
                        );
                        $stmt->execute();
                        $response["SQLError"] =  $stmt->error;

                        $response["updatedAccount"] = true;

                    } else {
                        $response["updatedAccount"] = false;
                    }
                }
            }
        }


    } else {
        $response["NotLoggedIn"] = true;
    }

    echo json_encode($response);
?>