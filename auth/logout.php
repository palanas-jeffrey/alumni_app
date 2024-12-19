<?php
session_start();

// Destroy the session to log the user out
session_unset(); // Unset all session variables
session_destroy(); // Destroy the session

// Display the loading screen before redirecting
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logging Out</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Custom style for body */
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f3f4f6;
            margin: 0;
        }
    </style>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="bg-white p-6 rounded-lg shadow-lg text-center">
        <h2 class="text-xl font-bold mb-4 text-gray-800">Logging Out, Please Wait...</h2>
        <!-- Spinner (Tailwind CSS animated spinner) -->
        <div class="w-16 h-16 border-4 border-t-4 border-blue-600 rounded-full animate-spin mx-auto mb-4"></div>
        <p class="text-gray-600">You are being logged out...</p>
    </div>

    <?php
    // Redirect the user after a few seconds to the login page
    header("Refresh: 3; url=login.php");  // Redirect after 3 seconds
    exit();
    ?>
