<?php
include '../database.php';

if (isset($_GET['degree_program'])) {
    $degree_program = $_GET['degree_program'];

    // Fetch questions for the selected degree program
    $stmt = $conn->prepare("SELECT * FROM questions WHERE degree_program = ?");
    $stmt->bind_param("s", $degree_program);
    $stmt->execute();
    $result = $stmt->get_result();

    $questions = [];
    while ($row = $result->fetch_assoc()) {
        $questions[] = $row;
    }

    // Return the questions as JSON
    echo json_encode(['questions' => $questions]);
}
?>
