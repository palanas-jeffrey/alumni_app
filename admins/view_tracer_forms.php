<?php
include '../database.php'; // Ensure the database connection
include 'sidebar.php'; // Include sidebar (if applicable)

// Handle POST request for adding and deleting questions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action === 'add') {
        $question_text = $_POST['question_text'];
        $question_type = $_POST['question_type'];
        $degree_program = $_POST['degree_program']; // Get degree program
        $options = isset($_POST['options']) ? $_POST['options'] : null;

        // Prepare and execute the SQL statement to add a question
        $stmt = $conn->prepare("INSERT INTO questions (question_text, question_type, degree_program, options) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $question_text, $question_type, $degree_program, $options);
        $stmt->execute();
    }

    if ($action === 'delete') {
        $question_id = $_POST['question_id'];
        // Prepare and execute the SQL statement to delete a question
        $stmt = $conn->prepare("DELETE FROM questions WHERE id = ?");
        $stmt->bind_param("i", $question_id);
        $stmt->execute();
    }
}

// Handle search query
$searchQuery = '';
if (isset($_GET['search_id']) && !empty($_GET['search_id'])) {
    $search_id = $_GET['search_id'];
    $searchQuery = "WHERE id = $search_id";  // Add a condition to filter questions by ID
}

// Fetch questions with or without search condition
$questions = $conn->query("SELECT * FROM questions $searchQuery");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel - Customize Tracer</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800 p-6">
    <h1 class="text-2xl font-bold mb-6">Admin Panel - Customize Tracer</h1>

    <!-- Search Form -->
    <form action="" method="GET" class="bg-white p-4 rounded shadow mb-6">
        <h2 class="text-lg font-semibold mb-4">Search for Question by ID</h2>
        <label class="block mb-2">
            Question ID:
            <input type="number" name="search_id" class="border p-2 w-full" value="<?= isset($_GET['search_id']) ? htmlspecialchars($_GET['search_id']) : '' ?>" placeholder="Enter Question ID">
        </label>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Search</button>
    </form>

    <!-- Add Question Form -->
    <form action="" method="POST" class="bg-white p-4 rounded shadow mb-6">
        <h2 class="text-lg font-semibold mb-4">Add a New Question</h2>
        <input type="hidden" name="action" value="add">

        <!-- Question Text -->
        <label class="block mb-2">
            Question Text:
            <textarea name="question_text" class="border p-2 w-full" required></textarea>
        </label>

        <!-- Question Type -->
        <label class="block mb-2">
            Question Type:
            <select name="question_type" class="border p-2 w-full">
                <option value="text">Text</option>
                <option value="number">Number</option>
                <option value="date">Date</option>
                <option value="textarea">Textarea</option>
                <option value="select">Select</option>
            </select>
        </label>

        <!-- Degree Program -->
        <label class="block mb-2">
            Degree Program:
            <select name="degree_program" class="border p-2 w-full">
                <option value="BSIT">BSIT</option>
                <option value="BSCS">BSCS</option>
                <option value="BSIS">BSIS</option>
                <option value="BSCE">BSCE</option>
            </select>
        </label>

        <!-- Options (For select-type questions only) -->
        <label class="block mb-4">
            Options (comma-separated, for Select type only):
            <input type="text" name="options" class="border p-2 w-full">
        </label>

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Add Question</button>
    </form>

    <h2 class="text-lg font-semibold mb-4">Existing Questions</h2>

    <table class="min-w-full bg-white rounded shadow">
        <thead class="bg-gray-200 text-gray-600">
            <tr>
                <th class="py-3 px-4 text-left">ID</th>
                <th class="py-3 px-4 text-left">Question</th>
                <th class="py-3 px-4 text-left">Type</th>
                <th class="py-3 px-4 text-left">Degree Program</th>
                <th class="py-3 px-4 text-left">Options</th>
                <th class="py-3 px-4 text-left">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $questions->fetch_assoc()): ?>
                <tr>
                    <td class="py-3 px-4"><?= $row['id'] ?></td>
                    <td class="py-3 px-4"><?= $row['question_text'] ?></td>
                    <td class="py-3 px-4"><?= $row['question_type'] ?></td>
                    <td class="py-3 px-4"><?= $row['degree_program'] ?></td>
                    <td class="py-3 px-4"><?= $row['options'] ?: 'N/A' ?></td>
                    <td class="py-3 px-4">
                        <form action="" method="POST" class="inline">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="question_id" value="<?= $row['id'] ?>">
                            <button type="submit" class="text-red-500">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
