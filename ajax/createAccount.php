<?php
// Connecting to Database
include_once "../scripts/connectToDatabase.php";
include_once "../scripts/setUpSession.php";

if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
    $response["validEmail"] = false;
}elseif($conn != false) {
    $response["validEmail"] = true;
    // Checking if email Address is already used
    $sql = "SELECT dtEmail FROM tblBenutzer WHERE dtEmail = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $_POST["email"]);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows != 0) {
        $response["emailAlreadyInUse"] = true;
        $response["createdAccount"] = false;
    } else {
        $response["emailAlreadyInUse"] = false;

        $passwordHash = hash("sha256", $_POST["password"]);

        if ($conn != false) {
            // Inserting new dataset
            $sql = "INSERT INTO tblBenutzer(dtEmail, dtPasswortHash, dtVorname, dtName, dtRolle) VALUES(?, ?, ?, ?, 1)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param(
                "ssss",
                $_POST["email"],
                $passwordHash,
                $_POST["name"],
                $_POST["surName"]
            );
            $stmt->execute();
            $response["SQLError"] = $stmt->error;

            $response["createdAccount"] = true;
            $_SESSION["login"] = true;
        } else {
            $response["createdAccount"] = false;
        }
    }

}

// Closing connection to avoid mem leak
$conn->close();

// output
echo json_encode($response);
?>