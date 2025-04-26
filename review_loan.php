<?php
include 'db.php'; // Database connection

// Fetch all loans
$sql = "SELECT * FROM loans ORDER BY id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View All Loans</title>
    <style>
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color:whitesmoke;
            margin: 0;
            padding: 40px 20px;
        }

        .container {
            max-width: 1000px;
            margin: auto;
            background-color: #9c67d1;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 14px 16px;
            text-align: left;
            border-bottom: 1px solid #9c67d1;
        }

        th {
            background-color:white;
            color: #333;
            font-weight: 600;
        }

        tr:hover {
            background-color: #f1f5f9;
        }
        th:hover {
            background-color: #f1f5f9;
        }

        p {
            text-align: center;
            color: #777;
            font-size: 18px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Loans Saved By You</h2>

    <?php if ($result && $result->num_rows > 0): ?>
        <table>
            <tr>
                <th>ID</th>
                <th>Lender</th>
                <th>Borrower</th>
                <th>Amount</th>
                <th>Interest (%)</th>
                <th>Duration (months)</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Total Repayment</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                    <td><?php echo htmlspecialchars($row['lender']); ?></td>
                    <td><?php echo htmlspecialchars($row['borrower']); ?></td>
                    <td><?php echo htmlspecialchars($row['amount']); ?></td>
                    <td><?php echo htmlspecialchars($row['interest']); ?></td>
                    <td><?php echo htmlspecialchars($row['duration']); ?></td>
                    <td><?php echo htmlspecialchars($row['start_date']); ?></td>
                    <td><?php echo htmlspecialchars($row['end_date']); ?></td>
                    <td><?php echo htmlspecialchars($row['total_repayment']); ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No loans found.</p>
    <?php endif; ?>

    <?php $conn->close(); ?>
</div>

</body>
</html>
