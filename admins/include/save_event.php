<?php include 'navbar.php'; ?>
<script src="https://cdn.tailwindcss.com"></script>

<style>
    /* Add custom styles for background image */
    .bg-image {
        background-image: url('path_to_your_image.jpg'); /* Replace with your image path */
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
    }
</style>

<div class="relative bg-image min-h-screen">
    <div class="sticky top-0 h-screen flex flex-col items-center justify-center bg-gradient-to-b from-green-200 to-blue-200">
        <h2 class="text-4xl font-bold text-black">Welcome to Alumni Management System</h2>
        
        <div class="container mx-auto mt-10 bg-white bg-opacity-80 p-6 rounded-lg shadow-md">
            <h2 class="mb-4 text-xl font-semibold">Upcoming Events</h2>

            <!-- Event Table -->
            <table class="min-w-full bg-white shadow-md rounded-lg">
                <thead>
                    <tr>
                        <th class="px-4 py-2">Event Name</th>
                        <th class="px-4 py-2">Date & Time</th>
                        <th class="px-4 py-2">Venue</th>
                        <th class="px-4 py-2">Description</th>
                        <th class="px-4 py-2">Image</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $query->fetch_assoc()): ?>
                        <tr>
                            <td class="px-4 py-2"><?= htmlspecialchars($row['event']) ?></td>
                            <td class="px-4 py-2"><?= date('l, F j, Y', strtotime($row['schedule'])) . ' at ' . date('g:i A', strtotime($row['schedule'])) ?></td>
                            <td class="px-4 py-2"><?= htmlspecialchars($row['venue']) ?></td>
                            <td class="px-4 py-2"><?= htmlspecialchars($row['description']) ?></td>
                            <td class="px-4 py-2">
                                <!-- Handle missing images by displaying a placeholder image -->
                                <?php 
                                    $imagesPath = 'uploads/' . $row['banner']; // Adjust the column name if needed
                                    if (!empty($row['banner']) && file_exists($imagesPath)) {
                                        $imageSrc = htmlspecialchars($imagesPath);
                                    } else {
                                        $imageSrc = 'path_to_placeholder.jpg'; // Replace with your placeholder image path
                                    }
                                ?>
                                <img src="<?= $imageSrc ?>" alt="Event Banner" class="max-w-xs max-h-24 object-contain cursor-pointer" onclick="openModal('<?= $imageSrc ?>')">
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- Modal to view images -->
        <div id="images-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
            <div class="bg-white p-4 rounded-lg max-w-lg w-full">
                <span class="text-gray-600 cursor-pointer" onclick="closeModal()">&times;</span>
                <img id="modal-image" src="" alt="Event Banner" class="max-w-full max-h-screen object-contain">
            </div>
        </div>

        <script>
            // Open modal and display the clicked image
            function openModal(imageSrc) {
                console.log(imageSrc); // Debugging: Log the image source
                const modal = document.getElementById('images-modal');
                const modalImage = document.getElementById('modal-image');
                modalImage.src = imageSrc;
                modal.classList.remove('hidden');
            }

            // Close modal and clear the image source to prevent caching issues
            function closeModal() {
                const modal = document.getElementById('images-modal');
                const modalImage = document.getElementById('modal-image');
                modalImage.src = ''; // Clear the image source
                modal.classList.add('hidden');
            }
        </script>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

