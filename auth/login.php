<?php
session_start();
include 'navbar.php';
include '../database.php'; // Database connection

// Handle logout request
if (isset($_GET['logout']) && $_GET['logout'] === 'true') {
    session_unset(); 
    session_destroy(); 
    header("Location: login.php?logged_out=true"); 
    exit();
}

$message = ''; // Initialize message variable

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (!empty($email) && !empty($password)) {
        $query = "SELECT * FROM user_details WHERE email = ?"; 
        $stmt = $conn->prepare($query);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // Verify password (plain text comparison)
            if ($password === $user['password']) {
                // Check if account is pending approval
                if ($user['user_type'] === 'alumnus' && $user['approved'] == 0) {
                    $message = 'Your account is pending admin approval. Please wait for approval.';
                } else {
                    // Set session variables
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['user_type'] = $user['user_type'];

                    // Redirect based on user type
                    switch ($user['user_type']) {
                        case 'alumnus':
                            header("Location: ../alumnus/alumnushomepage.php");
                            break;
                        case 'admin':
                            header("Location: ../admins/adminhomepage.php");
                            break;
                        case 'alumni_officer':
                            header("Location: ../officer/alumniofficerhomepage.php");
                            break;
                        default:
                            echo "Unknown user type.";
                            break;
                    }
                    exit();
                }
            } else {
                $message = 'Invalid password.';
            }
        } else {
            $message = 'Email not found.';
        }
    } else {
        $message = 'Please fill in all fields.';
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const message = <?php echo json_encode($message); ?>;
            const urlParams = new URLSearchParams(window.location.search);

            if (message) {
                showLoadingMessage(message);
            } else if (urlParams.has('signup')) {
                const signupStatus = urlParams.get('signup');
                if (signupStatus === 'success') {
                    showLoadingMessage('Account created successfully! Please log in.');
                } else if (signupStatus === 'approval_pending') {
                    showLoadingMessage('Your account has been created! Please wait for admin approval.');
                }
            }
        });

        // Display loading message and handle redirection
        function showLoadingMessage(message) {
            const loadingContainer = document.getElementById('loading-container');
            const messageElement = document.getElementById('loading-message');
            loadingContainer.style.display = 'flex';
            messageElement.textContent = message;

            // Redirect after 5 seconds for certain messages
            setTimeout(() => {
                if (message.includes('approval') || message.includes('created')) {
                    window.location.href = "login.php";
                }
            }, 5000);
        }
    </script>
</head>
<body class="bg-gray-100">
    <!-- Loading Screen -->
    <div id="loading-container" style="display: none;" class="fixed inset-0 bg-gray-800 bg-opacity-70 flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg text-center">
            <div class="text-xl font-semibold" id="loading-message">Loading...</div>
            <div class="mt-2">
                <svg class="animate-spin h-8 w-8 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 118 8 8 8 0 01-8-8z"></path>
                </svg>
            </div>
        </div>
    </div>

    <div class="flex justify-center items-center min-h-screen">
        <div class="bg-white p-6 rounded-lg shadow-md w-full max-w-sm">
            <h1 class="text-2xl font-semibold mb-6 text-center">Login</h1>

            <?php
            if (isset($_GET['logged_out']) && $_GET['logged_out'] === 'true') {
                echo "<p class='text-green-500 text-center'>You have been logged out successfully.</p>";
            }
            ?>

            <form id="login-form" action="login.php" method="post">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" id="email" name="email" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                </div>

                <div class="mt-4">
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" id="password" name="password" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                </div>

                <div class="mt-6">
                    <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Login
                    </button>
                </div>

                <div class="mt-4 text-center">
                    <p><a href="../forgot_password.php" class="text-blue-500 hover:underline">Forgot Password?</a></p>
                    <p class="text-sm text-gray-600">Don't have an account? 
                        <a href="signup.php" class="text-blue-500 hover:underline">Sign Up</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const loginForm = document.getElementById('login-form');
            const loadingContainer = document.getElementById('loading-container');

            // Add event listener for form submission
            loginForm.addEventListener('submit', function () {
                // Show the loading message
                loadingContainer.style.display = 'flex';
                const messageElement = document.getElementById('loading-message');
                messageElement.textContent = 'Logging In, Please Wait...';
            });
        });
    </script>
</head>