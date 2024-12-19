<?php
require 'database.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Verify the token and check expiration (1 hour)
    $stmt = $conn->prepare("SELECT * FROM users WHERE reset_token = ? AND reset_requested_at > NOW() - INTERVAL 1 HOUR");
    $stmt->bind_param('s', $token);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        // Token is valid, handle the password reset
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $newPassword = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

            // Update password and clear reset token
            $stmt = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL WHERE reset_token = ?");
            $stmt->bind_param('ss', $newPassword, $token);
            $stmt->execute();

            echo "Your password has been reset successfully.";
        }
    } else {
        echo "Invalid or expired token.";
        exit();
    }
} else {
    echo "No token provided.";
    exit();
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
    <h1>Reset Password</h1>
    <form method="POST">
        <label for="new_password">New Password:</label>
        <input type="password" id="new_password" name="new_password" required>
        <button type="submit">Reset Password</button>
    </form>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-lg shadow-md max-w-md w-full">
        <h1 class="text-2xl font-semibold mb-6 text-center">Reset Password</h1>
        <p class="text-gray-600 text-center mb-4">Enter your new password below.</p>
        
        <form method="POST">
            <label for="new_password" class="block text-sm font-medium text-gray-700">New Password</label>
            <input type="password" id="new_password" name="new_password" required 
                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm mb-4">
            
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-md font-medium">Reset Password</button>
        </form>
    </div>
</body>
</html>
