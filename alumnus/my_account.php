<?php
ob_start(); // Start output buffering

include '../database.php';
include 'navbar.php';

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['email'];

// Fetch the user's details from the database
$stmt = $conn->prepare("SELECT * FROM user_details WHERE email = ?");
$stmt->bind_param("s", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user) {
    echo "User not found!";
    exit();
}

// Handle form submission for profile updates
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $lastName = $_POST['lastName'];
    $firstName = $_POST['firstName'];
    $middleName = $_POST['middleName'];
    $gender = $_POST['gender'];
    $batchYear = $_POST['batch'];
    $courseGraduated = $_POST['course'];
    $currentlyConnectedTo = $_POST['connected'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Handle image upload
    $image = $user['image'];
    if (isset($_FILES['image']) && $_FILES['image']['tmp_name']) {
        $image = file_get_contents($_FILES['image']['tmp_name']);
    }

    // Check for email duplication
    $stmt = $conn->prepare("SELECT * FROM user_details WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $emailCheckResult = $stmt->get_result();
    $stmt->close();

    if ($emailCheckResult->num_rows > 0 && $email != $user['email']) {
        echo "The email is already in use.";
        exit();
    }

    // Update user details in the database
    $stmt = $conn->prepare("UPDATE user_details SET last_name = ?, first_name = ?, middle_name = ?, gender = ?, batch = ?, course_graduated = ?, currently_connected_to = ?, image = ?, email = ?, password = ? WHERE email = ?");
    $stmt->bind_param("sssssssssss", $lastName, $firstName, $middleName, $gender, $batchYear, $courseGraduated, $currentlyConnectedTo, $image, $email, $password, $userId);

    if ($stmt->execute()) {
        // Update session email after successful update
        $_SESSION['email'] = $email; // Update session email

        // Redirect to account page with success message
        header("Location: my_account.php?update=success");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}
ob_end_flush(); 
$conn->close(); // Close connection here
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Account</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="flex justify-center items-center min-h-screen">
        <div class="bg-white p-6 rounded-lg shadow-md w-full max-w-lg">
            <h1 class="text-2xl font-semibold mb-6 text-center">Manage Account</h1>

            <?php if (isset($_GET['update']) && $_GET['update'] === 'success'): ?>
                <div class="mb-4 text-green-500 text-center">Profile updated successfully!</div>
            <?php endif; ?>

            <table class="min-w-full table-auto border-collapse">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="px-4 py-2 text-left">Field</th>
                        <th class="px-4 py-2 text-left">Value</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Display User Information -->
                    <tr><td class="px-4 py-2 font-medium">Last Name</td><td class="px-4 py-2"><?php echo htmlspecialchars($user['last_name']); ?></td></tr>
                    <tr><td class="px-4 py-2 font-medium">First Name</td><td class="px-4 py-2"><?php echo htmlspecialchars($user['first_name']); ?></td></tr>
                    <tr><td class="px-4 py-2 font-medium">Middle Name</td><td class="px-4 py-2"><?php echo htmlspecialchars($user['middle_name']); ?></td></tr>
                    <tr><td class="px-4 py-2 font-medium">Gender</td><td class="px-4 py-2"><?php echo htmlspecialchars($user['gender']); ?></td></tr>
                    <tr><td class="px-4 py-2 font-medium">Batch Year</td><td class="px-4 py-2"><?php echo htmlspecialchars($user['batch']); ?></td></tr>
                    <tr><td class="px-4 py-2 font-medium">Course Graduated</td><td class="px-4 py-2"><?php echo htmlspecialchars($user['course_graduated']); ?></td></tr>
                    <tr><td class="px-4 py-2 font-medium">Currently Connected To</td><td class="px-4 py-2"><?php echo htmlspecialchars($user['currently_connected_to']); ?></td></tr>
                    <tr><td class="px-4 py-2 font-medium">Email</td><td class="px-4 py-2"><?php echo htmlspecialchars($user['email']); ?></td></tr>
                    <tr><td class="px-4 py-2 font-medium">Profile Picture</td>
                        <td class="px-4 py-2">
                            <?php if ($user['image']): ?>
                                <img src="data:image/jpeg;base64,<?php echo base64_encode($user['image']); ?>" alt="Profile Picture" class="w-24 h-24 rounded-full object-cover">
                            <?php else: ?>
                                <span>No image available</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="mt-6 text-center">
                <button id="editButton" class="text-blue-600 hover:underline">Edit Profile</button>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="editModal" class="hidden fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-md p-6 w-full max-w-lg">
            <h2 class="text-xl font-semibold mb-6">Edit Profile</h2>
            <form action="" method="POST" enctype="multipart/form-data">
                <div>
                    <label for="lastName" class="block font-medium">Last Name</label>
                    <input type="text" id="lastName" name="lastName" value="<?php echo htmlspecialchars($user['last_name']); ?>" class="w-full border rounded p-2 mt-2" required>
                </div>
                <div class="mt-4">
                    <label for="firstName" class="block font-medium">First Name</label>
                    <input type="text" id="firstName" name="firstName" value="<?php echo htmlspecialchars($user['first_name']); ?>" class="w-full border rounded p-2 mt-2" required>
                </div>
                <div class="mt-4">
                    <label for="middleName" class="block font-medium">Middle Name</label>
                    <input type="text" id="middleName" name="middleName" value="<?php echo htmlspecialchars($user['middle_name']); ?>" class="w-full border rounded p-2 mt-2" required>
                </div>
                <div class="mt-4">
                    <label for="gender" class="block font-medium">Gender</label>
                    <select name="gender" id="gender" class="w-full border rounded p-2 mt-2" required>
                        <option value="Male" <?php if ($user['gender'] == 'Male') echo 'selected'; ?>>Male</option>
                        <option value="Female" <?php if ($user['gender'] == 'Female') echo 'selected'; ?>>Female</option>
                    </select>
                </div>
                <div class="mt-4">
                    <label for="batch" class="block font-medium">Batch Year</label>
                    <input type="text" id="batch" name="batch" value="<?php echo htmlspecialchars($user['batch']); ?>" class="w-full border rounded p-2 mt-2" required>
                </div>
                <div class="mt-4">
                    <label for="course" class="block font-medium">Course Graduated</label>
                    <input type="text" id="course" name="course" value="<?php echo htmlspecialchars($user['course_graduated']); ?>" class="w-full border rounded p-2 mt-2" required>
                </div>
                <div class="mt-4">
                    <label for="connected" class="block font-medium">Currently Connected To</label>
                    <input type="text" id="connected" name="connected" value="<?php echo htmlspecialchars($user['currently_connected_to']); ?>" class="w-full border rounded p-2 mt-2" required>
                </div>
                <div class="mt-4">
                    <label for="email" class="block font-medium">Email</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" class="w-full border rounded p-2 mt-2" required>
                </div>
                <div class="mt-4">
                    <label for="password" class="block font-medium">Password</label>
                    <input type="password" id="password" name="password" value="<?php echo htmlspecialchars($user['password']); ?>" class="w-full border rounded p-2 mt-2" required>
                </div>
                <div class="mt-4">
                    <label for="image" class="block font-medium">Profile Picture</label>
                    <input type="file" id="image" name="image" class="w-full border rounded p-2 mt-2">
                </div>
                <div class="mt-6 text-center">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-full hover:bg-blue-700">Save Changes</button>
                    <button type="button" id="cancelButton" class="ml-4 text-gray-600">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Toggle Edit Profile Modal
        document.getElementById('editButton').addEventListener('click', function() {
            document.getElementById('editModal').classList.remove('hidden');
        });

        document.getElementById('cancelButton').addEventListener('click', function() {
            document.getElementById('editModal').classList.add('hidden');
        });
    </script>
</body>
</html>
