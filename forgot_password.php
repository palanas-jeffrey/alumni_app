<?php
error_reporting(E_ALL);  // Enable all error reporting for debugging
ini_set('display_errors', 1);  // Show errors on screen

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require 'database.php'; // Database connection

    // Sanitize and trim the email
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);

    // Validate the email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format.";
        exit;
    }

    // Check if the email exists in the database
    $stmt = $conn->prepare("SELECT email FROM users WHERE email = ?");
    if (!$stmt) {
        echo "Error preparing query: " . $conn->error;
        exit;
    }

    $stmt->bind_param('s', $email);

    // Execute the query and handle errors
    if ($stmt->execute()) {
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Email exists, generate reset token
            $token = bin2hex(random_bytes(50)); // Generate a unique token

            // Save the token and request time in the database
            $updateStmt = $conn->prepare("UPDATE users SET reset_token = ?, reset_requested_at = NOW() WHERE email = ?");
            if (!$updateStmt) {
                echo "Error preparing update query: " . $conn->error;
                exit;
            }
            $updateStmt->bind_param('ss', $token, $email);

            if ($updateStmt->execute()) {
                // Create a reset link
                $resetLink = "http://localhost/reset_password.php?token=" . urlencode($token);

                // Send the email (Ensure your mail server is configured correctly)
                $subject = "Password Reset Request";
                $message = "Hello, \n\nClick on this link to reset your password: $resetLink";
                $headers = "From: no-reply@yourdomain.com";

                if (mail($email, $subject, $message, $headers)) {
                    // Redirect to the same page with a success message
                    header("Location: forgot_password.php?message=sent");
                    exit;
                } else {
                    echo "Failed to send reset email. Please try again.";
                }
            } else {
                echo "Failed to update reset token. Error: " . $updateStmt->error;
            }
        } else {
            // Email address not found - silently handle this, no error message to user
            header("Location: forgot_password.php?message=not_found");
            exit;
        }
    } else {
        echo "Error in executing query: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-lg shadow-md max-w-md w-full">
        <h1 class="text-2xl font-semibold mb-6 text-center">Forgot Password</h1>
        <p class="text-gray-600 text-center mb-4">Enter your email address below, and we'll send you a link to reset your password.</p>
        
        <form action="forgot_password.php" method="post">
            <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
            <input type="email" id="email" name="email" required 
                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm mb-4">
            
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-md font-medium">Send Reset Link</button>
        </form>

        <?php
        // If the message is set in URL parameters, display the corresponding alert
        if (isset($_GET['message']) && $_GET['message'] === 'sent') {
            echo '<script>alert("The Reset Link has been successfully sent to your Gmail.");</script>';
        }
        elseif (isset($_GET['message']) && $_GET['message'] === 'not_found') {
            echo '<script>alert("The email address was not found.");</script>';
        }
        ?>
    </div>
</body>
</html>
