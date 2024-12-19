<?php
// Database connection
$host = "localhost";
$username = "root";
$password = "";
$dbname = "alumni_db"; // Update with your database name

$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['event'];  // Event title
    $schedule = $_POST['schedule']; // Event schedule
    $content = $_POST['description'];  // Event description

    // Handle banner image upload
    if (isset($_FILES['banner']) && $_FILES['banner']['error'] == 0) {
        $upload_dir = "uploads/";
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $file_name = $_FILES['banner']['name'];
        $file_tmp = $_FILES['banner']['tmp_name'];
        $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);

        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array(strtolower($file_ext), $allowed_ext)) {
            $new_file_name = time() . "_" . basename($file_name);
            $target_file = $upload_dir . $new_file_name;

            if (move_uploaded_file($file_tmp, $target_file)) {
                // Insert event data into the database
                $stmt = $conn->prepare("INSERT INTO events (title, content, schedule, banner) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssss", $title, $content, $schedule, $new_file_name);

                if ($stmt->execute()) {
                    echo "<script>alert('Event successfully saved!'); window.location.href = '../manage_event.php?page=events';</script>";
                } else {
                    echo "<script>alert('Failed to save event details.'); window.history.back();</script>";
                }

                $stmt->close();
            } else {
                echo "<script>alert('Failed to upload banner image.'); window.history.back();</script>";
            }
        } else {
            echo "<script>alert('Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Please upload a banner image.'); window.history.back();</script>";
    }
}

$conn->close();
?>
