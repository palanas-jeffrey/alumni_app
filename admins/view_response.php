<?php
include '../database.php';  // Include the database connection
include 'sidebar.php';

// Check if the ID is passed via GET
if (!isset($_GET['id'])) {
    die("Invalid request.");
}

$id = $_GET['id'];  // Get the alumni ID

// Fetch the response for the specific alumni
$query = "SELECT * FROM alumni_responses WHERE id_number = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('s', $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("No responses found for this ID.");
}

$response = $result->fetch_assoc();
$response['responses'] = json_decode($response['responses'], true); // Decode JSON responses
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Alumni Response</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">

<div class="min-h-screen flex flex-col items-center justify-center py-6 px-4">
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-4xl">
        <!-- Header -->
        <h1 class="text-3xl font-bold text-center text-blue-600 mb-6">Response Details for ID: <?= htmlspecialchars($response['id_number']) ?></h1>

        <!-- Display Alumni Information -->
        <p class="text-lg mb-4"><strong>Degree Program:</strong> <?= htmlspecialchars($response['degree_program']) ?></p>
        <p class="text-lg mb-4"><strong>Batch Year:</strong> <?= htmlspecialchars($response['batch']) ?></p>

        <!-- Display Responses Section -->
        <div class="mb-6">
            <h2 class="text-2xl font-semibold mb-4 text-gray-800">Responses</h2>
            <ul class="space-y-4">
                <?php foreach ($response['responses'] as $question_id => $answer): ?>
                    <li class="p-4 bg-gray-100 rounded-lg shadow-sm">
                        <p class="text-lg font-medium text-gray-900">Question <?= htmlspecialchars($question_id) ?>:</p>
                        <p class="text-gray-700 mt-2"><?= htmlspecialchars($answer) ?></p>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <!-- Back Button -->
        <div class="mt-8 flex justify-center">
            <a href="index.php" class="bg-blue-500 text-white px-6 py-2 rounded-lg shadow hover:bg-blue-600 transition">
                Back to Responses
            </a>
        </div>
    </div>
</div>

</body>
</html>
