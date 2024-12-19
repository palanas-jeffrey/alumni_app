<?php
error_reporting(E_ALL);  // Enable all error reporting for debugging
ini_set('display_errors', 1);  // Show errors on screen

// Database connection
require 'database.php'; 

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Validate token
    $stmt = $conn->prepare("SELECT user_id FROM users WHERE reset_token = ?");
    if (!$stmt) {
        echo "Error preparing query: " . $conn->error;
        exit;
    }

    $stmt->bind_param('s', $token);

    // Execute the query and check if the token exists
    if ($stmt->execute()) {
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Token is valid, show reset password form
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $new_password = $_POST['password'];

                // Hash the password
                $hashedPassword = password_hash($new_password, PASSWORD_DEFAULT);

                // Update the password in the database
                $updateStmt = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL WHERE reset_token = ?");
                if (!$updateStmt) {
                    echo "Error preparing update query: " . $conn->error;
                    exit;
                }
                $updateStmt->bind_param('ss', $hashedPassword, $token);

                if ($updateStmt->execute()) {
                    echo "Password has been successfully reset.";
                } else {
                    echo "Failed to reset password. Please try again.";
                }
            }
        } else {
            echo "Invalid or expired token.";
        }
    } else {
        echo "Error in executing query: " . $stmt->error;
    }
} else {
    echo "No token provided.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
</head>
<body>
    <h1>Reset Your Password</h1>
    <form action="reset_password.php?token=<?php echo $_GET['token']; ?>" method="post">
        <label for="password">New Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <button type="submit">Reset Password</button>
    </form>
</body>
</html>
