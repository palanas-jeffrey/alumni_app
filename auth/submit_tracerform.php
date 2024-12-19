<?php
// Include the database connection file
include '../database.php';

// Retrieve form data
$designation = $_POST['designation'];
$name = $_POST['name'];
$email = $_POST['email'];
$contact = $_POST['contact'];
$address = $_POST['address'];
$company = $_POST['company'];
$employee_name = $_POST['employee_name'];
$degree_program = $_POST['degree_program'];
$position = $_POST['position'];
$status = $_POST['status'];
$salary_range = $_POST['salary_range'];
$sex = $_POST['sex'];
$quality_of_work = $_POST['quality_of_work'];
$quantity_of_work = $_POST['quantity_of_work'];
$work_habits = $_POST['work_habits'];
$other_skills = $_POST['other_skills'];
$recommend = $_POST['recommend'];
$suggestions = $_POST['suggestions'];

// Insert into database
$sql = "INSERT INTO alumni_tracer (
    designation, name, email, contact, address, company, employee_name, degree_program,
    position, status, salary_range, sex, quality_of_work, quantity_of_work, work_habits,
    other_skills, recommend, suggestions
) VALUES (
    ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
)";

$stmt = $conn->prepare($sql);
$stmt->bind_param(
    "ssssssssssssiiisss",
    $designation, $name, $email, $contact, $address, $company, $employee_name, $degree_program,
    $position, $status, $salary_range, $sex, $quality_of_work, $quantity_of_work, $work_habits,
    $other_skills, $recommend, $suggestions
);

if ($stmt->execute()) {
    echo "Survey submitted successfully!";
} else {
    echo "Error: " . $stmt->error;
}

// Close connection
$stmt->close();
$conn->close();
?>
