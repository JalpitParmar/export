<?php
$servername = "localhost";
$username = "u754868166_bhoomitradelin";
$password = "Hqut@628";
$dbname = "u754868166_export";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
?>
