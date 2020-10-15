<?php
// Connecting to Database
include_once "../scripts/connectToDatabase.php";

// Checking if email Address is already used
$sql = "SELECT dtEmail FROM tblBenutzer WHERE dtEmail = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $_POST["email"]);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows != 0) {
    $response["emailAlreadyInUse"] = true;
    $response["createdAccount"] = false;
} else {
    $response["emailAlreadyInUse"] = false;

    $passwordHash = hash("sha256", $_POST["password"]);

    if($conn) {
        // Inserting new dataset
        $sql = "INSERT INTO tblBenutzer(dtEmail, dtPasswortHash, dtName, dtVorname) VALUES(?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "ssss",
            $_POST["email"],
            $passwordHash,
            $_POST["name"],
            $_POST["surName"]
        );
        $stmt->execute();

        $response["createdAccount"] = true;
    } else {
        $response["createdAccount"] = false;
    }
}
// output
echo json_encode($response);

// Closing connection to avoid mem leak
$conn->close();
?>