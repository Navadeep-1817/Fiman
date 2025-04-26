<?php
session_start();
include 'db.php';

$goalSaved = false;
$errorMessage = '';

// Make sure user is logged in
if (!isset($_SESSION['user_id'])) {
    die("Unauthorized. Please log in.");
}

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $goalName = trim($_POST['goal_name']);
    $duration = intval($_POST['duration']);
    $goalAmount = floatval($_POST['goal_amount']);
    $today = date('Y-m-d');

    if (empty($goalName) || $duration <= 0 || $goalAmount <= 0) {
        $errorMessage = "Please fill out all fields with valid data.";
    } else {
        $stmt = $conn->prepare("INSERT INTO goals (goal_name, created_at, duration, goal_amount) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssid", $goalName, $today, $duration, $goalAmount);

        if ($stmt->execute()) {
            $goalSaved = true;
        } else {
            $errorMessage = "Error saving goal: " . $stmt->error;
        }

        $stmt->close();
    }
}

// Fetch user-specific savings
$savingsQuery = "SELECT DATE(last_updated) AS save_date, SUM(amount) AS total_saved 
                 FROM savings 
                 WHERE user_id = ? 
                 GROUP BY DATE(last_updated) 
                 ORDER BY last_updated ASC";

$stmt = $conn->prepare($savingsQuery);
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$savingDates = [];
$savingAmounts = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $savingDates[] = $row['save_date'];
        $savingAmounts[] = $row['total_saved'];
    }
}
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Save Goal</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 30px;
            background-color: #f4f6f8;
        }
        .container {
            background: #fff;
            padding: 25px;
            max-width: 600px;
            margin: auto;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0,0,0,0.05);
        }
        h2 {
            text-align: center;
            color: #333;
        }
        input, button {
            width: 100%;
            padding: 12px;
            margin: 8px 0;
            border-radius: 6px;
            border: 1px solid #ccc;
        }
        button {
            background-color: #9c67d1;
            color: white;
            font-weight: bold;
            border: none;
            cursor: pointer;
        }
        .message {
            text-align: center;
            color: green;
            margin-top: 15px;
        }
        canvas {
            margin-top: 40px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Create a New Savings Goal</h2>
    <form method="POST" action="">
        <input type="text" name="goal_name" placeholder="Goal Name" required>
        <input type="number" name="duration" placeholder="Goal Duration (in days)" required>
        <input type="number" step="0.01" name="goal_amount" placeholder="Goal Amount" required>
        <button type="submit">Save Goal</button>
    </form>

    <?php if ($goalSaved): ?>
        <div class="message">Goal saved successfully!</div>
    <?php elseif (!empty($errorMessage)): ?>
        <div class="message" style="color: red;"><?php echo htmlspecialchars($errorMessage); ?></div>
    <?php endif; ?>

    <canvas id="savingsChart" width="400" height="200"></canvas>
</div>

<script>
    const ctx = document.getElementById('savingsChart').getContext('2d');
    const chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?php echo json_encode($savingDates); ?>,
            datasets: [{
                label: 'Money Saved',
                data: <?php echo json_encode($savingAmounts); ?>,
                borderColor: '#9c67d1',
                backgroundColor: 'rgba(156, 103, 209, 0.2)',
                tension: 0.3,
                fill: true,
                pointRadius: 4,
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Date'
                    }
                },
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Amount Saved'
                    }
                }
            }
        }
    });
</script>

</body>
</html>
