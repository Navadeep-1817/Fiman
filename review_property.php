<?php
// Start session
session_start();
include 'db.php'; // Use your mysqli connection ($conn)

if (!isset($_SESSION['user_id'])) {
    // Not logged in? Redirect to login page maybe
    die("You must be logged in to view properties.");
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT property_name, location, value FROM properties WHERE user_id = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();

$properties = [];
if ($result->num_rows > 0) {
    $properties = $result->fetch_all(MYSQLI_ASSOC);
}

$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Properties</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f6f8;
            padding: 50px;
        }
        .container {
            background: white;
            max-width: 800px;
            margin: auto;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .no-data {
            text-align: center;
            padding: 20px;
            color: #777;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Your Properties</h2>

    <?php if (count($properties) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Property Name</th>
                    <th>Location</th>
                    <th>Value (USD)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($properties as $property): ?>
                    <tr>
                        <td><?= htmlspecialchars($property['property_name']) ?></td>
                        <td><?= htmlspecialchars($property['location']) ?></td>
                        <td><?= number_format($property['value'], 2) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="no-data">No properties found.</div>
    <?php endif; ?>
</div>
</body>
</html>
