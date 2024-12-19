<?php

include '../database.php'; // Database connection
include 'sidebar.php';
$qry = $conn->query("SELECT * FROM events ORDER BY schedule ASC");
?>

<body class="bg-gray-100">

    <title>Events</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <div class="container mx-auto mt-10">
         <h2 class="mb-4 text-2xl font-semibold">Event List</h2>
        
        <!-- Create New Event Button -->
        <a href="include/create_event.php" class="inline-block mb-3 py-2 px-4 bg-green-500 text-white rounded-md hover:bg-green-600 transition duration-300">
            Create New Event
        </a>
        
        <!-- Events Table -->
        <table class="min-w-full bg-white shadow-md rounded-lg">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 text-left">ID</th>
                    <th class="px-4 py-2 text-left">Event Name</th>
                    <th class="px-4 py-2 text-left">Schedule</th>
                    <th class="px-4 py-2 text-left">Description</th>
                    <th class="px-4 py-2 text-left">Banner</th>
                    <th class="px-4 py-2 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $qry->fetch_assoc()): ?>
                    <tr class="border-b">
                        <td class="px-4 py-2"><?= $row['event_id'] ?></td>
                        <td class="px-4 py-2"><?= htmlspecialchars($row['title']) ?></td>
                        <td class="px-4 py-2"><?= date('Y-m-d H:i', strtotime($row['schedule'])) ?></td>
                        <td class="px-4 py-2"><?= htmlspecialchars($row['content']) ?></td>
                        <td class="px-4 py-2">
                            <img src="include/uploads/<?= $row['banner'] ?>" alt="Event Banner" class="w-24 h-24 object-cover rounded-md">
                        </td>
                        <td class="px-4 py-2 flex space-x-2">
                            <!-- Edit Button -->
                            <a href="include/edit_event.php?id=<?= $row['event_id'] ?>" class="py-2 px-4 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition duration-300">
                                Edit
                            </a>
                            
                            <!-- Delete Button -->
                            <a href="include/delete_event.php?id=<?= $row['event_id'] ?>" class="py-2 px-4 bg-red-500 text-white rounded-md hover:bg-red-600 transition duration-300" onclick="return confirm('Are you sure you want to delete this event?');">
                                Delete
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
