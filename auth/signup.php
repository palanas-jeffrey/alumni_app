<?php

include 'navbar.php';
// Connect to the database
include '../database.php';

// Handle form submission for signup
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
    $userType = 'alumnus'; // Assume alumnus type for now, this could be selectable

    // Handle image upload
    $image = null;
    if (isset($_FILES['image']) && $_FILES['image']['tmp_name']) {
        $image = file_get_contents($_FILES['image']['tmp_name']);
    }

    // Set approved status based on user type
    $approved = ($userType === 'alumnus') ? 0 : 1; // 0 means needs approval, 1 means auto-approved

    // Prepare SQL statement to insert data
    $stmt = $conn->prepare("INSERT INTO user_details (last_name, first_name, middle_name, gender, batch, course_graduated, currently_connected_to, image, email, password, user_type, approved) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssssssi", $lastName, $firstName, $middleName, $gender, $batchYear, $courseGraduated, $currentlyConnectedTo, $image, $email, $password, $userType, $approved);

    // Execute and check success
    if ($stmt->execute()) {
        if ($userType === 'alumnus') {
            // Redirect to login with approval request message for alumni
            header("Location: login.php?signup=approval_pending");
        } else {
            // Redirect to login page with success message for other user types
            header("Location: login.php?signup=success");
        }
        exit(); // Stop further execution after the redirect
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close statement
    $stmt->close();
}

// Close the connection
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="flex justify-center items-center min-h-screen">
        <div class="bg-white p-6 rounded-lg shadow-md w-full max-w-lg">
            <h1 class="text-2xl font-semibold mb-6 text-center">Create Account</h1>
            <form action="#" method="post" enctype="multipart/form-data">
                <div class="grid grid-cols-2 gap-4">
                    <!-- Last Name -->
                    <div>
                        <label for="lastName" class="block text-sm font-medium text-gray-700">Last Name</label>
                        <input type="text" id="lastName" name="lastName" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>

                    <!-- First Name -->
                    <div>
                        <label for="firstName" class="block text-sm font-medium text-gray-700">First Name</label>
                        <input type="text" id="firstName" name="firstName" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>

                    <!-- Middle Name -->
                    <div>
                        <label for="middleName" class="block text-sm font-medium text-gray-700">Middle Name</label>
                        <input type="text" id="middleName" name="middleName" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>

                    <!-- Gender -->
                    <div>
                        <label for="gender" class="block text-sm font-medium text-gray-700">Gender</label>
                        <select id="gender" name="gender" class="mt-1 block w-full px-3 py-2 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                    </div>

                    <!-- Batch Year -->
                    <div>
                        <label for="batch" class="block text-sm font-medium text-gray-700">Batch Year</label>
                        <input type="number" id="batch" name="batch" min="1900" max="<?php echo date('Y'); ?>" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                    </div>

                    <!-- Course Graduated -->
                    <div>
                        <label for="course" class="block text-sm font-medium text-gray-700">Course Graduated</label>
                        <select id="course" name="course" class="mt-1 block w-full px-3 py-2 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <option>BS Information Technology</option>
                            <option>BS Computer Science</option>
                            <option>BS Software Engineering</option>
                        </select>
                    </div>

                    <!-- Currently Connected To -->
                    <div class="col-span-2">
                        <label for="connected" class="block text-sm font-medium text-gray-700">Currently Connected To</label>
                        <textarea id="connected" name="connected" rows="2" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"></textarea>
                    </div>

                    <!-- Image -->
                    <div class="col-span-2">
                        <label for="image" class="block text-sm font-medium text-gray-700">Image</label>
                        <input type="file" id="image" name="image" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" id="email" name="email" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <input type="password" id="password" name="password" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="mt-6">
                    <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Create Account
                    </button>
                </div>

                <!-- Login Option -->
                <div class="mt-4 text-center">
                    <p class="text-sm text-gray-600">Already have an account?</p>
                    <a href="login.php" class="text-sm text-blue-600 hover:underline">Login here</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
