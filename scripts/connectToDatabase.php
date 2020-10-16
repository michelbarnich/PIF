<?php
$response = [];

// Set up connection to MySQL Server
$conn = mysqli_connect("172.16.248.176", "pif", "pif2020", "pif");

// Saving SQL Server connection status to output
if($conn != false) {
    $response["SQLServerConnection"] = true;
} else {
    $response["SQLServerConnection"] = false;
}