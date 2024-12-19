<?php
include '../database.php'; // Database connection
include 'sidebar.php'; // Admin navbar

// Fetch alumni and their donation data with DISTINCT to avoid duplicates
$query = "
 SELECT DISTINCT
        u.user_id,
        CONCAT(u.first_name, ' ', u.middle_name, ' ', u.last_name) AS name,
        u.email,
        u.batch,
        u.course_graduated,
        u.currently_connected_to,
        u.approved,
        u.created_at AS registration_date
    FROM
        user_details u
    LEFT JOIN
        donations d ON d.user_id = u.user_id
    WHERE
        u.user_type = 'alumnus'
    ORDER BY
        u.user_id";

// Execute the query
$result = $conn->query($query);

// Check if the query was successful
if ($result === false) {
    // Query failed, output an error message
    echo "Error: " . $conn->error;
    exit(); // Stop further execution if query fails
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alumni Report</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="flex justify-center items-start mt-8"> <!-- Centered horizontally, aligned to the top -->
        <div class="max-w-screen-xl w-full px-4 md:px-8 py-6 bg-white shadow-lg rounded-lg">
            <h1 class="text-3xl font-semibold text-center mb-6">Alumni Report</h1>

            <!-- Responsive Table Container -->
            <div class="overflow-x-auto">

                <!-- Display alumni data in a table -->
                <?php if ($result->num_rows > 0): ?>
                    <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="py-2 px-2 md:px-4 border text-xs md:text-base">Alumnus ID</th>
                                <th class="py-2 px-2 md:px-4 border text-xs md:text-base">Name</th>
                                <th class="py-2 px-2 md:px-4 border text-xs md:text-base">Email</th>
                                <th class="py-2 px-2 md:px-4 border text-xs md:text-base">Batch</th>
                                <th class="py-2 px-2 md:px-4 border text-xs md:text-base">Course Graduated</th>
                                <th class="py-2 px-2 md:px-4 border text-xs md:text-base">Currently Connected To</th>
                                <th class="py-2 px-2 md:px-4 border text-xs md:text-base">Approval Status</th>
                                <th class="py-2 px-2 md:px-4 border text-xs md:text-base">Registration Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                            <tr class="text-xs md:text-base">
                                <td class="py-2 px-2 md:px-4 border"><?php echo $row['user_id']; ?></td>
                                <td class="py-2 px-2 md:px-4 border"><?php echo $row['name']; ?></td>
                                <td class="py-2 px-2 md:px-4 border"><?php echo $row['email']; ?></td>
                                <td class="py-2 px-2 md:px-4 border"><?php echo $row['batch']; ?></td>
                                <td class="py-2 px-2 md:px-4 border"><?php echo $row['course_graduated']; ?></td>
                                <td class="py-2 px-2 md:px-4 border"><?php echo $row['currently_connected_to']; ?></td>
                                <td class="py-2 px-2 md:px-4 border">
                                    <?php echo $row['approved'] ? '<span class="text-green-500">Approved</span>' : '<span class="text-red-500">Pending</span>'; ?>
                                </td>
                                <td class="py-2 px-2 md:px-4 border"><?php echo $row['registration_date']; ?></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="text-center text-red-500">No alumni data available.</p>
                <?php endif; ?>
            </div>

            <!-- Optional: Export button -->
            <div class="mt-6 text-center">
                <a href="export_alumni_report.php" class="bg-blue-500 text-white px-4 py-2 rounded shadow hover:bg-blue-600">Export Data</a>
            </div>
        </div>
    </div>
</body>
</html>

<?php
// Close database connection
$conn->close();
?>
