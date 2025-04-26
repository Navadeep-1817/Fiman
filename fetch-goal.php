<?php
include 'db.php';
session_start();
$user_id = $_SESSION['user_id'];

$sql = "SELECT * FROM goals WHERE user_id = '$user_id'";
$result = $conn->query($sql);
$goals = [];

while ($row = $result->fetch_assoc()) {
    $goals[] = $row;
}

echo json_encode($goals);
$conn->close();
?>
