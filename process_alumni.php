<?php
include 'database.php';

// Collect form data
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$middle_name = $_POST['middle_name'];
$gender = $_POST['gender'];
$course_graduated = $_POST['course_graduated'];
$batch_year = $_POST['batch_year'];
$current_occupation = $_POST['current_occupation'];
$company_name = $_POST['company_name'];
$location = $_POST['location'];
$email = $_POST['email'];
$phone_number = $_POST['phone_number'];
$linkedin_profile = $_POST['linkedin_profile'];

// Insert the data into the database
$query = "INSERT INTO alumni (first_name, last_name, middle_name, gender, course_graduated, batch_year, current_occupation, company_name, location, email, phone_number, linkedin_profile)
VALUES ('$first_name', '$last_name', '$middle_name', '$gender', '$course_graduated', '$batch_year', '$current_occupation', '$company_name', '$location', '$email', '$phone_number', '$linkedin_profile')";

if ($conn->query($query) === TRUE) {
    echo "Alumni information saved successfully.";
    // Optionally, redirect to another page
    header("Location: alumni_form.php");
} else {
    echo "Error: " . $query . "<br>" . $conn->error;
}
?>
