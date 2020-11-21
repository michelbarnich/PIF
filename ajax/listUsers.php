<?php
    $silent = true; // if $silent is set to true, getUserData.php wont echo

    include_once "../scripts/connectToDatabase.php";
    include_once "../scripts/setUpSession.php";
    include_once "../ajax/getUserData.php";

    if ($response["userData"]["role"] >= 2) {

        if($response["userData"]["role"] == 2) {
            $sqlAddition = " WHERE fiFirma = " . $response["userData"]["companyId"];
        } else {
            $sqlAddition = "";
        }

        $returnUserArray = [];

        if($response["userData"]["role"] != 2 || $response["userData"]["companyId"] != NULL) {


            $sql = "SELECT dtEmail, dtVorname, dtRolle, idIdentifikationsNummer, fiFirma, dtName FROM tblbenutzer" . $sqlAddition . ";";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->get_result();
            $response["SQLError"] = $stmt->error;

            for ($i = 0; $i < $result->num_rows; $i++) {

                $row = $result->fetch_assoc();

                if(!is_null($row["fiFirma"])) {
                    $sql2 = "SELECT dtName FROM tblfirma WHERE idFirmenNummer = ?";
                    $stmt2 = $conn->prepare($sql2);
                    $stmt2->bind_param("i", $row["fiFirma"]);
                    $stmt2->execute();
                    $result2 = $stmt2->get_result();
                    $response["SQLError"] = $stmt2->error;

                    $row2 = $result2->fetch_assoc();
                } else {
                    $row2["dtName"] = "-";
                }

                array_push($returnUserArray, [
                    "email" => $row["dtEmail"],
                    "surname" => $row["dtName"],
                    "name" => $row["dtVorname"],
                    "role" => $row["dtRolle"],
                    "id" => $row["idIdentifikationsNummer"],
                    "company" => $row2["dtName"],
                    "companyId" => $row["fiFirma"],
                ]);

            }
        }


        $response["userList"] = $returnUserArray;

        echo json_encode($response);
    }
?>