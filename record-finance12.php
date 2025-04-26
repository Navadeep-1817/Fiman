<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    die("Error: User not logged in.");
}

$user_id = $_SESSION['user_id'];

$date = $_POST['date'];
$health = $_POST['health'] ?: 0;
$study = $_POST['study'] ?: 0;
$grocery = $_POST['grocery'] ?: 0;
$clothing = $_POST['clothing'] ?: 0;
$housing = $_POST['housing'] ?: 0;
$others = $_POST['others'] ?: 0;

$total = $health + $study + $grocery + $clothing + $housing + $others;

// Insert expense record
$sql = "INSERT INTO expenses (user_id, date, health, study, grocery, clothing, housing, others, total) 
        VALUES ('$user_id', '$date', '$health', '$study', '$grocery', '$clothing', '$housing', '$others', '$total')";

if ($conn->query($sql) === TRUE) {
    // Now reduce the total expense from savings
    $sql = "UPDATE savings SET amount = amount - $total WHERE user_id = '$user_id' AND amount >= $total";
    
    if ($conn->query($sql) === TRUE) {
        echo "Expense recorded and savings updated!";
    } else {
        echo "Expense recorded, but failed to update savings!";
    }
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
