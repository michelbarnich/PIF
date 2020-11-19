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


            $sql = "SELECT *, tblbenutzer.dtName AS nutzerName, CASE WHEN fiFirma IS NULL THEN '-' ELSE tblfirma.dtname END AS firmenname FROM tblbenutzer, tblfirma " . $sqlAddition . ";";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->get_result();
            $response["SQLError"] = $stmt->error;

            for ($i = 0; $i < $result->num_rows; $i++) {

                $row = $result->fetch_assoc();

                array_push($returnUserArray, [
                    "email" => $row["dtEmail"],
                    "surname" => $row["nutzerName"],
                    "name" => $row["dtVorname"],
                    "role" => $row["dtRolle"],
                    "id" => $row["idIdentifikationsNummer"],
                    "company" => $row["firmenname"],
                    "companyId" => $row["fiFirma"],
                ]);

            }
        }


        $response["userList"] = $returnUserArray;

        echo json_encode($response);
    }
?>