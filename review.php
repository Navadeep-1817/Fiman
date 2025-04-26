<?php
include 'db.php'; // Database connection

header("Content-Type: application/json");

// Read JSON input from frontend
$data = json_decode(file_get_contents("php://input"), true);
$days = intval($data['days']);
$category = $data['category'];

if (!$days || $days <= 0) {
    echo json_encode(["success" => false, "error" => "Invalid number of days"]);
    exit();
}

// Define valid categories based on your database columns
$validCategories = ["health", "study", "grocery", "clothing", "housing", "others"];

// SQL Query based on category selection
if ($category == "all") {
    // Sum only the category columns (EXCLUDING the "total" column)
    $sql = "SELECT SUM(health) + SUM(study) + SUM(grocery) + SUM(clothing) + SUM(housing) + SUM(others) AS total_spent 
            FROM expenses 
            WHERE DATE(date) >= CURDATE() - INTERVAL ? DAY";
} elseif (in_array($category, $validCategories)) {
    // Sum a specific category
    $sql = "SELECT SUM($category) AS total_spent 
            FROM expenses 
            WHERE DATE(date) >= CURDATE() - INTERVAL ? DAY";
} else {
    echo json_encode(["success" => false, "error" => "Invalid category"]);
    exit();
}

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $days);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$response = [
    "success" => true,
    "total" => $row['total_spent'] ?? 0
];

$stmt->close();
$conn->close();

echo json_encode($response);
?>
