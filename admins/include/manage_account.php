<?php
session_start();

// If the user is not logged in, redirect to the login page
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Connect to the database
$host = "localhost";
$user = "root";
$pass = "";
$db = "alumni_db";

$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user data from the database
$email = $_SESSION['email'];
$sql = "SELECT user_id, last_name, first_name, middle_name, gender, batch, course_graduated, currently_connected_to, image FROM user_details WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Handle form submission for account update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $lastName = $_POST['lastName'];
    $firstName = $_POST['firstName'];
    $middleName = $_POST['middleName'];
    $gender = $_POST['gender'];
    $batchYear = $_POST['batch']; 
    $courseGraduated = $_POST['course'];
    $currentlyConnectedTo = $_POST['connected'];

    // Handle image upload
    $image = $user['image']; // Keep existing image unless a new one is uploaded
    if (isset($_FILES['image']) && $_FILES['image']['tmp_name']) {
        $image = file_get_contents($_FILES['image']['tmp_name']);
    }

    // Prepare SQL statement to update data
    $updateStmt = $conn->prepare("UPDATE user_details SET last_name = ?, first_name = ?, middle_name = ?, gender = ?, batch = ?, course_graduated = ?, currently_connected_to = ?, image = ? WHERE email = ?");
    // Assuming that $image is a string and $email is valid
$updateStmt->bind_param("sssssssss", $lastName, $firstName, $middleName, $gender, $batchYear, $courseGraduated, $currentlyConnectedTo, $image, $email);

    // Execute and check success
    if ($updateStmt->execute()) {
        // Redirect to a success page
        header("Location: manage_account.php?update=success");
        exit();
    } else {
        echo "Error: " . $updateStmt->error;
    }

    // Close statement
    $updateStmt->close();
}

// Close the connection
$conn->close();
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
            <form action="#" method="post" enctype="multipart/form-data">
                <div class="grid grid-cols-2 gap-4">
                    <!-- Last Name -->
                    <div>
                        <label for="lastName" class="block text-sm font-medium text-gray-700">Last Name</label>
                        <input type="text" id="lastName" name="lastName" value="<?php echo htmlspecialchars($user['last_name']); ?>" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                    </div>

                    <!-- First Name -->
                    <div>
                        <label for="firstName" class="block text-sm font-medium text-gray-700">First Name</label>
                        <input type="text" id="firstName" name="firstName" value="<?php echo htmlspecialchars($user['first_name']); ?>" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                    </div>

                    <!-- Middle Name -->
                    <div>
                        <label for="middleName" class="block text-sm font-medium text-gray-700">Middle Name</label>
                        <input type="text" id="middleName" name="middleName" value="<?php echo htmlspecialchars($user['middle_name']); ?>" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>

                    <!-- Gender -->
                    <div>
                        <label for="gender" class="block text-sm font-medium text-gray-700">Gender</label>
                        <select id="gender" name="gender" class="mt-1 block w-full px-3 py-2 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <option value="male" <?php echo $user['gender'] == 'male' ? 'selected' : ''; ?>>Male</option>
                            <option value="female" <?php echo $user['gender'] == 'female' ? 'selected' : ''; ?>>Female</option>
                        </select>
                    </div>

                    <!-- Batch Year -->
                    <div>
                        <label for="batch" class="block text-sm font-medium text-gray-700">Batch Year</label>
                        <input type="number" id="batch" name="batch" min="1900" max="<?php echo date('Y'); ?>" value="<?php echo htmlspecialchars($user['batch']); ?>" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                    </div>

                    <!-- Course Graduated -->
                    <div>
                        <label for="course" class="block text-sm font-medium text-gray-700">Course Graduated</label>
                        <select id="course" name="course" class="mt-1 block w-full px-3 py-2 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <option value="BS Information Technology" <?php echo $user['course_graduated'] == 'BS Information Technology' ? 'selected' : ''; ?>>BS Information Technology</option>
                            <option value="BS Computer Science" <?php echo $user['course_graduated'] == 'BS Computer Science' ? 'selected' : ''; ?>>BS Computer Science</option>
                            <option value="BS Software Engineering" <?php echo $user['course_graduated'] == 'BS Software Engineering' ? 'selected' : ''; ?>>BS Software Engineering</option>
                        </select>
                    </div>

                    <!-- Currently Connected To -->
                    <div class="col-span-2">
                        <label for="connected" class="block text-sm font-medium text-gray-700">Currently Connected To</label>
                        <textarea id="connected" name="connected" rows="2" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"><?php echo htmlspecialchars($user['currently_connected_to']); ?></textarea>
                    </div>

                    <!-- Image -->
                    <div class="col-span-2">
                        <label for="image" class="block text-sm font-medium text-gray-700">Image</label>
                        <input type="file" id="image" name="image" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-6">
                        <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Update Account
                        </button>
                    </div>
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
    <title>Manage Account</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        // Display a success notification if 'update=success' is in the URL
        window.onload = function() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('update') === 'success') {
                alert("Update Profile Successfully");
            }
        };
    </script>
</head>
<body class="bg-gray-100">
    <div class="flex justify-center items-center min-h-screen">
        <div class="bg-white p-6 rounded-lg shadow-md w-full max-w-lg">
            <h1 class="text-2xl font-semibold mb-6 text-center">Manage Account</h1>
            <form action="#" method="post" enctype="multipart/form-data">
                <div class="grid grid-cols-2 gap-4">
                    <!-- Last Name -->
                    <div>
                        <label for="lastName" class="block text-sm font-medium text-gray-700">Last Name</label>
                        <input type="text" id="lastName" name="lastName" value="<?php echo htmlspecialchars($user['last_name']); ?>" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                    </div>

                    <!-- First Name -->
                    <div>
                        <label for="firstName" class="block text-sm font-medium text-gray-700">First Name</label>
                        <input type="text" id="firstName" name="firstName" value="<?php echo htmlspecialchars($user['first_name']); ?>" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                    </div>

                    <!-- Middle Name -->
                    <div>
                        <label for="middleName" class="block text-sm font-medium text-gray-700">Middle Name</label>
                        <input type="text" id="middleName" name="middleName" value="<?php echo htmlspecialchars($user['middle_name']); ?>" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>

                    <!-- Gender -->
                    <div>
                        <label for="gender" class="block text-sm font-medium text-gray-700">Gender</label>
                        <select id="gender" name="gender" class="mt-1 block w-full px-3 py-2 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <option value="male" <?php echo $user['gender'] == 'male' ? 'selected' : ''; ?>>Male</option>
                            <option value="female" <?php echo $user['gender'] == 'female' ? 'selected' : ''; ?>>Female</option>
                        </select>
                    </div>

                    <!-- Batch Year -->
                    <div>
                        <label for="batch" class="block text-sm font-medium text-gray-700">Batch Year</label>
                        <input type="number" id="batch" name="batch" min="1900" max="<?php echo date('Y'); ?>" value="<?php echo htmlspecialchars($user['batch']); ?>" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                    </div>

                    <!-- Course Graduated -->
                    <div>
                        <label for="course" class="block text-sm font-medium text-gray-700">Course Graduated</label>
                        <select id="course" name="course" class="mt-1 block w-full px-3 py-2 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <option value="BS Information Technology" <?php echo $user['course_graduated'] == 'BS Information Technology' ? 'selected' : ''; ?>>BS Information Technology</option>
                            <option value="BS Computer Science" <?php echo $user['course_graduated'] == 'BS Computer Science' ? 'selected' : ''; ?>>BS Computer Science</option>
                            <option value="BS Software Engineering" <?php echo $user['course_graduated'] == 'BS Software Engineering' ? 'selected' : ''; ?>>BS Software Engineering</option>
                        </select>
                    </div>

                    <!-- Currently Connected To -->
                    <div class="col-span-2">
                        <label for="connected" class="block text-sm font-medium text-gray-700">Currently Connected To</label>
                        <textarea id="connected" name="connected" rows="2" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"><?php echo htmlspecialchars($user['currently_connected_to']); ?></textarea>
                    </div>

                    <!-- Image -->
                    <div class="col-span-2">
                        <label for="image" class="block text-sm font-medium text-gray-700">Image</label>
                        <input type="file" id="image" name="image" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-6">
                        <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Update Account
                        </button>
                    </div>
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
    <title>Manage Account</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        // Function to display the image preview
        function previewImage(event) {
            var reader = new FileReader();
            reader.onload = function(){
                var output = document.getElementById('imagePreview');
                output.src = reader.result;
            }
            reader.readAsDataURL(event.target.files[0]);
        }

        // Display a success notification if 'update=success' is in the URL
        window.onload = function() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('update') === 'success') {
                alert("Update Profile Successfully");
            }
        };
    </script>
</head>
<body class="bg-gray-100">
    <div class="flex justify-center items-center min-h-screen">
        <div class="bg-white p-6 rounded-lg shadow-md w-full max-w-lg">
            <h1 class="text-2xl font-semibold mb-6 text-center">Manage Account</h1>
            <form action="#" method="post" enctype="multipart/form-data">
                <div class="grid grid-cols-2 gap-4">
                    <!-- Last Name -->
                    <div>
                        <label for="lastName" class="block text-sm font-medium text-gray-700">Last Name</label>
                        <input type="text" id="lastName" name="lastName" value="<?php echo htmlspecialchars($user['last_name']); ?>" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                    </div>

                    <!-- First Name -->
                    <div>
                        <label for="firstName" class="block text-sm font-medium text-gray-700">First Name</label>
                        <input type="text" id="firstName" name="firstName" value="<?php echo htmlspecialchars($user['first_name']); ?>" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                    </div>

                    <!-- Middle Name -->
                    <div>
                        <label for="middleName" class="block text-sm font-medium text-gray-700">Middle Name</label>
                        <input type="text" id="middleName" name="middleName" value="<?php echo htmlspecialchars($user['middle_name']); ?>" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>

                    <!-- Gender -->
                    <div>
                        <label for="gender" class="block text-sm font-medium text-gray-700">Gender</label>
                        <select id="gender" name="gender" class="mt-1 block w-full px-3 py-2 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <option value="male" <?php echo $user['gender'] == 'male' ? 'selected' : ''; ?>>Male</option>
                            <option value="female" <?php echo $user['gender'] == 'female' ? 'selected' : ''; ?>>Female</option>
                        </select>
                    </div>

                    <!-- Batch Year -->
                    <div>
                        <label for="batch" class="block text-sm font-medium text-gray-700">Batch Year</label>
                        <input type="number" id="batch" name="batch" min="1900" max="<?php echo date('Y'); ?>" value="<?php echo htmlspecialchars($user['batch']); ?>" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                    </div>

                    <!-- Course Graduated -->
                    <div>
                        <label for="course" class="block text-sm font-medium text-gray-700">Course Graduated</label>
                        <select id="course" name="course" class="mt-1 block w-full px-3 py-2 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <option value="BS Information Technology" <?php echo $user['course_graduated'] == 'BS Information Technology' ? 'selected' : ''; ?>>BS Information Technology</option>
                            <option value="BS Computer Science" <?php echo $user['course_graduated'] == 'BS Computer Science' ? 'selected' : ''; ?>>BS Computer Science</option>
                            <option value="BS Software Engineering" <?php echo $user['course_graduated'] == 'BS Software Engineering' ? 'selected' : ''; ?>>BS Software Engineering</option>
                        </select>
                    </div>

                    <!-- Currently Connected To -->
                    <div class="col-span-2">
                        <label for="connected" class="block text-sm font-medium text-gray-700">Currently Connected To</label>
                        <textarea id="connected" name="connected" rows="2" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"><?php echo htmlspecialchars($user['currently_connected_to']); ?></textarea>
                    </div>

                    <!-- Image -->
                    <div class="col-span-2">
                        <label for="image" class="block text-sm font-medium text-gray-700">Image</label>
                        <input type="file" id="image" name="image" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" onchange="previewImage(event)">
                        <div class="mt-4 text-center">
                            <img id="imagePreview" src="<?php echo $user['image'] ? 'data:image/jpeg;base64,' . base64_encode($user['image']) : ''; ?>" alt="Profile Picture" class="w-32 h-32 object-cover rounded-full mx-auto">
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-6">
                        <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Update Account
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</body>
</html>