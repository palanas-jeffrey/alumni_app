<?php
ob_start();  // Start output buffering
include 'sidebar.php'; 
include '../database.php'; // Database connection

// Set upload directory
$upload_dir = __DIR__ . '/uploads/';

// Handle image upload
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['gallery_image'])) {
    $image = $_FILES['gallery_image'];
    $image_name = time() . "_" . basename($image['name']);
    $image_path = $upload_dir . $image_name;

    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    if (move_uploaded_file($image['tmp_name'], $image_path)) {
        $sql = "INSERT INTO gallery (about) VALUES (?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $image_name);
        if ($stmt->execute()) {
            echo "<p class='text-green-500'>Image uploaded successfully!</p>";
            // Prevent re-execution on refresh
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } else {
            echo "<p class='text-red-500'>Error: " . $stmt->error . "</p>";
        }
    } else {
        echo "<p class='text-red-500'>Error: Failed to upload file. Check directory permissions!</p>";
    }
}

// Handle image deletion
if (isset($_GET['delete'])) {
    $image_id = intval($_GET['delete']);

    $sql = "SELECT about FROM gallery WHERE gallery_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $image_id);
    $stmt->execute();
    $stmt->bind_result($image_name);
    $stmt->fetch();
    $stmt->close();

    $image_path = $upload_dir . $image_name;

    if (isset($image_name) && file_exists($image_path)) {
        if (unlink($image_path)) {
            $sql = "DELETE FROM gallery WHERE gallery_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('i', $image_id);
            if ($stmt->execute()) {
                echo "<p class='text-green-500'>Image deleted successfully!</p>";
                header("Location: " . $_SERVER['PHP_SELF']);
                exit;
            } else {
                echo "<p class='text-red-500'>Error deleting record from database.</p>";
            }
        } else {
            echo "<p class='text-red-500'>Error: Failed to delete the image file.</p>";
        }
    } else {
        echo "<p class='text-red-500'>Error: File not found!</p>";
    }
}

// Delete orphaned files
if (file_exists($upload_dir)) {
    $uploaded_files = array_diff(scandir($upload_dir), ['.', '..']);
    $db_files = [];
    $query = $conn->query("SELECT about FROM gallery");
    while ($row = $query->fetch_assoc()) {
        $db_files[] = $row['about'];
    }

    foreach ($uploaded_files as $file) {
        if (!in_array($file, $db_files)) {
            unlink($upload_dir . $file);
        }
    }
}

// Fetch all images
$query = $conn->query("SELECT * FROM gallery ORDER BY gallery_id DESC");
?>
    <script src="https://cdn.tailwindcss.com"></script>

<!-- Start HTML Section -->
<div class="bg-white dark:bg-blue-800 h-screen py-6 sm:py-8 lg:py-12">
    <div class="mx-auto max-w-screen-2xl px-4 md:px-8">
        <div class="mb-4 flex items-center justify-between gap-8 sm:mb-8 md:mb-12">
            <div class="flex items-center gap-12">
                <h2 class="text-2xl font-bold text-gray-800 lg:text-3xl dark:text-white">Gallery</h2>
            </div>
        </div>

        <!-- Upload Image Form -->
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Upload New Image</h3>
            <form method="POST" enctype="multipart/form-data">
                <div class="mt-4">
                    <input type="file" name="gallery_image" class="block w-full p-2 border border-gray-300 rounded-lg" required />
                </div>
                <button type="submit" class="mt-4 bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition duration-200">Upload</button>
            </form>
        </div>

        <!-- Gallery Display -->
        <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-6">
            <?php while ($row = $query->fetch_assoc()): ?>
                <div class="group relative flex h-48 items-end overflow-hidden rounded-lg bg-gray-100 shadow-lg md:h-80">
                    <?php 
                        $imagePath = 'uploads/' . $row['about'];
                        if (file_exists($upload_dir . $row['about'])): 
                    ?>
                        <img src="<?= $imagePath ?>" alt="Gallery Image" 
                             class="absolute inset-0 h-full w-full object-cover object-center transition duration-200 group-hover:scale-110" />
                    <?php else: ?>
                    <?php endif; ?>
                    <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-gray-800 via-transparent to-transparent opacity-50"></div>
                    <div class="absolute top-2 right-2">
                        <a href="?delete=<?= $row['gallery_id'] ?>" 
                           class="bg-red-500 text-white px-3 py-1 rounded-full text-xs hover:bg-red-700 transition">
                            Delete
                        </a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</div>

<?php ob_end_flush(); // Flush the output buffer to send the content to the browser ?>
