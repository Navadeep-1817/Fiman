<?php
$host = "localhost:4902";
$user = "root";
$pass = "";
$dbname = "fiman";

$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>