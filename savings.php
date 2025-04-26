<?php
session_start();
include 'db.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    die("Error: User not logged in.");
}

$user_id = $_SESSION['user_id'];

// Get current savings
$sql = "SELECT amount FROM savings WHERE user_id = '$user_id'";
$result = $conn->query($sql);

$savings = "0.00"; // Default value
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $savings = $row['amount'];
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Your Savings</title>
    <link rel="stylesheet" href="savings.css">
</head>
<body>
    <div class="container">
        <h2>Manage Your Savings</h2>
        
        <h3>Current Savings: <span id="current-savings"><?php echo $savings; ?></span></h3>

        <form action="save-savings.php" method="POST">
            <label>Add to Savings 💰</label>
            <input type="number" name="amount" step="0.01" min="0" required>

            <button type="submit">Save Money</button>
        </form>
    </div>
</body>
</html>
