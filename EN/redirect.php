<?php
ini_set('display_errors', 0);
ini_set('log_errors', 0);
ini_set('error_log', NULL); 
include 'config.php';
$conn = getDbConnection();
if ($conn->connect_error) {
    die("There is a problem connecting to the database.");
}
if (isset($_GET['code'])) {
    $code = $conn->real_escape_string($_GET['code']);
    $decoded_code = urldecode($code);
    $sql = "SELECT original_link FROM links WHERE short_code LIKE '%$decoded_code%'";
    $result = $conn->query($sql);
    if ($result === false) {
        die("Error executing database query.");
    }
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $original_link = $row['original_link'];
        header("Location: $original_link", true, 302);
        exit();
    } else {
        die("The requested link was not found.");
    }
} else {
    die("Invalid link parameter.");
}
$conn->close();
