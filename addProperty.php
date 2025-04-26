<?php
// Start session
session_start();
include 'db.php'; // Make sure this file defines $conn

// Fake login for demo (in real app, user must be logged in)
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1; // Temporary hardcoded user ID
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $property_name = trim($_POST['property_name'] ?? '');
    $location = trim($_POST['location'] ?? '');
    $value = trim($_POST['value'] ?? '');

    if ($property_name && $location && is_numeric($value)) {
        // NOW using $conn -> prepare() instead of $pdo
        $stmt = $conn->prepare("INSERT INTO properties (user_id, property_name, location, value) VALUES (?, ?, ?, ?)");

        if ($stmt === false) {
            die('Prepare() failed: ' . htmlspecialchars($conn->error));
        }

        $stmt->bind_param("isss", $_SESSION['user_id'], $property_name, $location, $value);

        if ($stmt->execute()) {
            $message = "✅ Property added successfully!";
        } else {
            $message = "❌ Error: " . htmlspecialchars($stmt->error);
        }

        $stmt->close();
    } else {
        $message = "❌ Please enter valid property details.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Property</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f6f8;
            padding: 50px;
        }
        .container {
            background: white;
            max-width: 500px;
            margin: auto;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #333;
        }
        label {
            display: block;
            margin-top: 15px;
            color: #555;
        }
        input[type="text"], input[type="number"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 8px;
        }
        button {
            margin-top: 20px;
            width: 100%;
            background-color: #4CAF50;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover {
            background-color: #43a047;
        }
        .message {
            margin-top: 20px;
            text-align: center;
            font-weight: bold;
            color: #444;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Add New Property</h2>
    <form method="POST" action="">
        <label for="property_name">Property Name</label>
        <input type="text" name="property_name" id="property_name" required>

        <label for="location">Location</label>
        <input type="text" name="location" id="location" required>

        <label for="value">Value (USD)</label>
        <input type="number" name="value" id="value" step="0.01" required>

        <button type="submit">Add Property</button>
    </form>

    <?php if ($message): ?>
        <div class="message"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
</div>
</body>
</html>
