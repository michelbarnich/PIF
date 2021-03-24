<?php
    $silent = true; // if $silent is set to true, getUserData.php wont echo

    include_once "../scripts/replaceSpecialChars.php";
    include_once "../scripts/connectToDatabase.php";
    include_once "../scripts/setUpSession.php";
    include_once "../ajax/getUserData.php";
    include_once "../scripts/parseURLForQueries.php";

    if($conn != false) {
        if ($response["userData"]["role"] == 3 && $queries["page_name"] == "sensors") {

            if($_POST["companyId"] != -1) {

                $sql = "SELECT * FROM tblfirma WHERE idFirmenNummer = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $_POST["companyId"]);
                $stmt->execute();
                $result = $stmt->get_result();
                $response["SQLError"] = $stmt->error;

                if($result->num_rows == 1) {

                    $sql = "UPDATE tblsensorstation SET fiFirma = ? WHERE idIdentifikationsNummer = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("is", $_POST["companyId"], $_POST["id"]);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $response["SQLError"] = $stmt->error;

                    $response["updatedSensor"] = true;

                } else {
                    $response["updatedSensor"] = false;
                }

            } else {
                $sql = "UPDATE tblsensorstation SET fiFirma = NULL WHERE idIdentifikationsNummer = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $_POST["id"]);
                $stmt->execute();
                $result = $stmt->get_result();
                $response["SQLError"] = $stmt->error;

                $response["updatedSensor"] = true;
            }
        } elseif ($response["userData"]["role"] >= 2 && $queries["page_name"] == "my plants") {

            $sql = "SELECT * FROM tblsensorstation WHERE idIdentifikationsNummer = ? AND fiFirma = '" . $response["userData"]["companyId"] . "'";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $_POST["id"]);
            $stmt->execute();
            $result = $stmt->get_result();
            $response["SQLError"] = $stmt->error;

            if($result->num_rows == 1) {

                $sql = "UPDATE tblsensorstation SET dtName = ? WHERE idIdentifikationsNummer = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ss",$_POST["name"], $_POST["id"]);
                $stmt->execute();
                $result = $stmt->get_result();
                $response["SQLError"] = $stmt->error;

                $response["updatedSensor"] = true;

            }

        }
    }

    echo json_encode($response);
?>