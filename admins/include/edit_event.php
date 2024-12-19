<?php
include '../../database.php'; // Database connection

// Get the event ID from the URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $event_id = $_GET['id'];

    // Fetch the event details from the database
    $qry = $conn->query("SELECT * FROM events WHERE event_id = $event_id");

    if ($qry->num_rows > 0) {
        $event = $qry->fetch_assoc();
    } else {
        echo "<script>alert('Event not found!'); window.location.href = 'index.php?page=events';</script>";
        exit;
    }
} else {
    echo "<script>alert('Invalid event ID!'); window.location.href = 'index.php?page=events';</script>";
    exit;
}

// Process the form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $schedule = $_POST['schedule'];

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
                // Update the event in the database with the new banner
                $stmt = $conn->prepare("UPDATE events SET title = ?, content = ?, schedule = ?, banner = ? WHERE event_id = ?");
                $stmt->bind_param("ssssi", $title, $content, $schedule, $new_file_name, $event_id);
            }
        } else {
            // If no new image is uploaded, just update the event without changing the banner
            $stmt = $conn->prepare("UPDATE events SET title = ?, content = ?, schedule = ? WHERE event_id = ?");
            $stmt->bind_param("sssi", $title, $content, $schedule, $event_id);
        }
    } else {
        // If no new image is uploaded, just update the event without changing the banner
        $stmt = $conn->prepare("UPDATE events SET title = ?, content = ?, schedule = ? WHERE event_id = ?");
        $stmt->bind_param("sssi", $title, $content, $schedule, $event_id);
    }

    if ($stmt->execute()) {
        echo "<script>alert('Event updated successfully!'); window.location.href = '../manage_event.php?page=events';</script>";
    } else {
        echo "<script>alert('Failed to update event.'); window.history.back();</script>";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Event</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Edit Event</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="title" class="form-label">Event Name</label>
                <input type="text" class="form-control" id="title" name="title" value="<?= htmlspecialchars($event['title']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="schedule" class="form-label">Event Schedule</label>
                <input type="datetime-local" class="form-control" id="schedule" name="schedule" value="<?= date('Y-m-d\TH:i', strtotime($event['schedule'])) ?>" required>
            </div>
            <div class="mb-3">
                <label for="content" class="form-label">Event Description</label>
                <textarea class="form-control" id="content" name="content" rows="4" required><?= htmlspecialchars($event['content']) ?></textarea>
            </div>
            <div class="mb-3">
                <label for="banner" class="form-label">Event Banner</label>
                <input type="file" class="form-control" id="banner" name="banner">
                <small class="form-text text-muted">Leave blank if you don't want to change the banner image.</small>
            </div>
            <div class="mb-3">
                <label for="current-banner" class="form-label">Current Banner</label><br>
                <!-- Display current banner image -->
                <?php if (!empty($event['banner']) && file_exists('uploads/' . $event['banner'])): ?>
                    <img src="uploads/<?= htmlspecialchars($event['banner']) ?>" alt="Current Banner" class="img-fluid" style="max-height: 100px;">
                <?php else: ?>
                    <p>No banner image available</p>
                <?php endif; ?>
            </div>
            <button type="submit" class="btn btn-primary">Update Event</button>
            <a href="../manage_event.php?page=events" class="btn btn-secondary">Cancel</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
