<?php
ob_start(); // Start output buffering

include '../database.php'; // Database connection
include 'sidebar.php'; // Admin navbar

// Handle approval or rejection for a specific user
if (isset($_GET['action']) && isset($_GET['user_id'])) {
    $userId = intval($_GET['user_id']); // Safely cast to integer
    $action = $_GET['action']; // Get the action (approve/reject)

    // Validate action
    if (in_array($action, ['approve', 'reject'])) {
        if ($action === 'approve') {
            // Approve only the specific user
            $approveQuery = "UPDATE user_details SET approved = 1 WHERE user_id = ?";
            $stmt = $conn->prepare($approveQuery);
            $stmt->bind_param('i', $userId);
            $stmt->execute();
            $stmt->close();
        } elseif ($action === 'reject') {
            // Reject user (delete the user record from the database)
            $rejectQuery = "DELETE FROM user_details WHERE user_id = ?";
            $stmt = $conn->prepare($rejectQuery);
            $stmt->bind_param('i', $userId);
            $stmt->execute();
            $stmt->close();
        }

        // Redirect to refresh the page
        header("Location: approve_alumni.php");
        exit();
    }
}

// Fetch all alumni (both approved and pending) to display in the table
$query = "SELECT * FROM user_details WHERE user_type = 'alumnus'";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Alumni</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto mt-10">
        <h1 class="text-3xl font-semibold text-center mb-6">Manage Alumni Accounts</h1>

        <?php if ($result->num_rows > 0): ?>
            <table class="min-w-full bg-white">
                <thead>
                    <tr>
                        <th class="py-2 px-4 border">ID</th>
                        <th class="py-2 px-4 border">Name</th>
                        <th class="py-2 px-4 border">Email</th>
                        <th class="py-2 px-4 border">Batch</th>
                        <th class="py-2 px-4 border">Course Graduated</th>
                        <th class="py-2 px-4 border">Status</th>
                        <th class="py-2 px-4 border">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td class="py-2 px-4 border"><?php echo $row['user_id']; ?></td>
                        <td class="py-2 px-4 border"><?php echo $row['first_name'] . ' ' . $row['last_name']; ?></td>
                        <td class="py-2 px-4 border"><?php echo $row['email']; ?></td>
                        <td class="py-2 px-4 border"><?php echo $row['batch']; ?></td>
                        <td class="py-2 px-4 border"><?php echo $row['course_graduated']; ?></td>
                        <td class="py-2 px-4 border">
                            <?php if ($row['approved'] == 1): ?>
                                <span class="text-green-500">Approved</span>
                            <?php else: ?>
                                <span class="text-red-500">Pending</span>
                            <?php endif; ?>
                        </td>
                        <td class="py-2 px-4 border">
                            <?php if ($row['approved'] == 0): ?>
                                <a href="approve_alumni.php?action=approve&user_id=<?php echo $row['user_id']; ?>" class="text-green-500 hover:underline">Approve</a> |
                                <a href="approve_alumni.php?action=reject&user_id=<?php echo $row['user_id']; ?>" class="text-red-500 hover:underline">Reject</a>
                            <?php else: ?>
                                <span class="text-gray-500">No action</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-center text-red-500">No alumni found.</p>
        <?php endif; ?>
    </div>
</body>
</html>

<?php
$conn->close();
ob_end_flush();
?>
