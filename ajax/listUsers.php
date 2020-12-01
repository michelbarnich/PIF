<?php
    include_once "../scripts/replaceSpecialChars.php";

    $silent = true; // if $silent is set to true, getUserData.php wont echo

    include_once "../scripts/connectToDatabase.php";
    include_once "../scripts/setUpSession.php";
    include_once "../ajax/getUserData.php";
    include_once "../scripts/parseURLForQueries.php";

    if ($response["userData"]["role"] >= 2) {

        if($response["userData"]["role"] == 2 || $queries["page_name"] == "my company") {
            if ($response["userData"]["companyId"] == "") {
                $companyId = 0;
            } else {
                $companyId = $response["userData"]["companyId"];
            }
            $sqlAddition = " WHERE fiFirma = " . $companyId;
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
                    "email" => replaceSpecialChars($row["dtEmail"]),
                    "surname" => replaceSpecialChars($row["dtName"]),
                    "name" => replaceSpecialChars($row["dtVorname"]),
                    "role" => replaceSpecialChars($row["dtRolle"]),
                    "id" => replaceSpecialChars($row["idIdentifikationsNummer"]),
                    "company" => replaceSpecialChars($row2["dtName"]),
                    "companyId" => replaceSpecialChars($row["fiFirma"])
                ]);

            }
        }


        $response["userList"] = $returnUserArray;

        echo json_encode($response);
    }
?>