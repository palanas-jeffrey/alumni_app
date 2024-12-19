<?php
function redirectBasedOnUserType($user) {
    if ($user['user_type'] === 'alumnus' && $user['approved'] == 0) {
        echo "<p class='text-red-500 text-center'>Your account is pending admin approval. Please wait for approval.</p>";
        return false; // Stop further processing
    }

    // Redirect based on user type
    switch ($user['user_type']) {
        case 'alumnus':
            header("Location: ../alumnus/alumnushomepage.php");
            exit();
        case 'admin':
            header("Location: ../admins/adminhomepage.php");
            exit();
        default:
            // Log the issue and provide feedback for unexpected user_type
            error_log("Unknown user_type detected: {$user['user_type']}");
            echo "<p class='text-red-500 text-center'>Your account role is not recognized. Please contact support.</p>";
            return false; // Stop further processing
    }
}
?>
