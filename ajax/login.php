<?php
// Connecting to Database
include_once "../scripts/connectToDatabase.php";
include_once "../scripts/setUpSession.php";

if($conn != false) {
    // Requesting Data from SQL Server
    $sql = "SELECT dtPasswortHash, idIdentifikationsNummer FROM tblBenutzer WHERE dtEmail = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $_POST["email"]);
    $stmt->execute();
    $result = $stmt->get_result();
    $response["SQLError"] = $stmt->error;

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Comparing provided password to the stored one in Database
        if (hash("sha256", $_POST["password"]) == $row["dtPasswortHash"]) {
            // saving identifications to Session
            $_SESSION["id"] = $row["idIdentifikationsNummer"];
            $_SESSION["login"] = true;

            // Saving Login success status to output
            $response["LoginSuccess"] = $_SESSION["login"];
        } else {
            $response["LoginSuccess"] = false;
        }
    }


    // Closing connection to avoid mem leak
    $conn->close();
}

    // output
    echo json_encode($response);
?>
