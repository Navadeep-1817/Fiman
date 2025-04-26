<?php
$conn = new mysqli("localhost", "root", "", "finance_db");

$result = $conn->query("SELECT * FROM loans");

$loans = [];
while ($row = $result->fetch_assoc()) {
    $loans[] = $row;
}

echo json_encode($loans);
$conn->close();
?>

