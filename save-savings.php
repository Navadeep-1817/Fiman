<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    die("Error: User not logged in.");
}

$user_id = $_SESSION['user_id'];
$amount = isset($_POST['amount']) ? $_POST['amount'] : 0;

// Check if user already has savings
$sql = "SELECT amount FROM savings WHERE user_id = '$user_id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Update existing savings
    $sql = "UPDATE savings SET amount = amount + $amount WHERE user_id = '$user_id'";
} else {
    // Insert new savings record
    $sql = "INSERT INTO savings (user_id, amount) VALUES ('$user_id', '$amount')";
}

if ($conn->query($sql) === TRUE) {
    echo "Savings updated successfully!";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
header("Location: savings.php");
exit();
?>
