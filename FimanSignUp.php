<?php
session_start();
include 'db.php';

$errorMessage = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $passwordInput = $_POST['password'];
    $confirmPass = $_POST['confirm_password'];

    if (empty($username) || empty($email) || empty($passwordInput) || empty($confirmPass)) {
        $errorMessage = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorMessage = "Invalid email format.";
    } elseif (strlen($passwordInput) < 6) {
        $errorMessage = "Password must be at least 6 characters long.";
    } elseif ($passwordInput !== $confirmPass) {
        $errorMessage = "Passwords do not match.";
    } else {
        $passwordHash = password_hash($passwordInput, PASSWORD_DEFAULT);

        // Check for duplicate username or email
        $checkSql = "SELECT username, email FROM users WHERE username = ? OR email = ?";
        if ($checkStmt = $conn->prepare($checkSql)) {
            $checkStmt->bind_param("ss", $username, $email);
            $checkStmt->execute();
            $result = $checkStmt->get_result();

            if ($result->num_rows > 0) {
                $existingUser = $result->fetch_assoc();
                if ($existingUser['username'] == $username) {
                    $errorMessage = "Username is not available.";
                } elseif ($existingUser['email'] == $email) {
                    $errorMessage = "Email is already registered.";
                }
            } else {
                // Generate unique user ID
                do {
                    $userID = "FM" . mt_rand(100000, 999999);
                    $checkID = "SELECT user_id FROM users WHERE user_id = ?";
                    if ($checkIDStmt = $conn->prepare($checkID)) {
                        $checkIDStmt->bind_param("s", $userID);
                        $checkIDStmt->execute();
                        $checkIDStmt->store_result();
                        $idExists = $checkIDStmt->num_rows > 0;
                        $checkIDStmt->close();
                    } else {
                        $errorMessage = "Database error.";
                        break;
                    }
                } while ($idExists);

                if (empty($errorMessage)) {
                    // Insert new user record
                    $sql = "INSERT INTO users (user_id, username, email, password) VALUES (?, ?, ?, ?)";
                    if ($stmt = $conn->prepare($sql)) {
                        $stmt->bind_param("ssss", $userID, $username, $email, $passwordHash);
                        if ($stmt->execute()) {
                            $stmt->close();
                            $checkStmt->close();
                            $conn->close();
                            header("Location: FimanLogin.php");
                            exit();
                        } else {
                            $errorMessage = "Error: " . $stmt->error;
                        }
                        $stmt->close();
                    }
                }
            }
            $checkStmt->close();
        }
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Fiman - Sign Up</title>
  <link rel="stylesheet" href="http://localhost/FinanceManagement/SignUpStyle.css">
</head>
<body>
  <div class="container">
      <h2>Sign Up</h2>
      <?php if (!empty($errorMessage)): ?>
          <div class="error-message"><?php echo htmlspecialchars($errorMessage); ?></div>
      <?php endif; ?>
      <form action="FimanSignUp.php" method="POST">
          <input type="text" name="username" placeholder="Username" required>
          <input type="email" name="email" placeholder="Email" required>
          <input type="password" name="password" placeholder="Password" required>
          <input type="password" name="confirm_password" placeholder="Confirm Password" required>
          <button type="submit">Sign Up</button>
      </form>
      <p>Already have an account? <a href="http://localhost/FinanceManagement/FimanLogin.php">Login here</a></p>
  </div>
</body>
</html>
