<?php
include 'db.php';
$goal_id = $_GET['goal_id'];

$sql = "SELECT last_updated, saved_amount FROM goals WHERE id = '$goal_id'";
$result = $conn->query($sql);
$dates = [];
$amounts = [];

while ($row = $result->fetch_assoc()) {
    $dates[] = $row['last_updated'];
    $amounts[] = $row['saved_amount'];
}

echo json_encode(["dates" => $dates, "amounts" => $amounts]);
$conn->close();
?>
