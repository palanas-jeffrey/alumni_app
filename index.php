<?php include 'navbar.php'; ?>
<script src="https://cdn.tailwindcss.com"></script>

<style>
    /* Add custom styles for background image */
    .bg-image {
        background-image: url('https://www.isatu.edu.ph/wp-content/uploads/2024/01/Default-Image-blue-min.jpg'); /* Replace with your image path */
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
    }
</style>

<div class="relative bg-image min-h-screen">
    <div class="https://www.isatu.edu.ph/wp-content/uploads/2024/01/Default-Image-blue-min.jpg">
        <li>
            <div></div>
        </li>
    <h2 class="text-4xl font-bold text-white text-center">Welcome to Alumni Management System</h2>
        
        <?php
            include 'database.php'; // Database connection
            
            // Fetch all events from the database
            $query = $conn->query("SELECT * FROM events ORDER BY schedule ASC");
        ?>

<div class="container mx-auto mt-10 bg-yellow-400 bg-opacity-100 p-1 rounded-lg shadow-md">
    <h2 class="mb-1 text-xl font-semibold">Upcoming Events</h2>
</div>


           <!-- Event Table -->
<div class="container mx-auto mt-10 bg-white p-6 rounded-lg shadow-md">
    <table class="min-w-full bg-gray-100 shadow-md rounded-lg">
        <thead>
            <tr>
                <th class="px-4 py-2 text-left bg-yellow-200">Event Name</th>
                <th class="px-4 py-2 text-left bg-yellow-200">Date & Time</th>
                <th class="px-4 py-2 text-left bg-yellow-200">Description</th>
                <th class="px-4 py-2 text-left bg-yellow-200">Image</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $query->fetch_assoc()): ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-2"><?= htmlspecialchars($row['title']) ?></td>
                    <td class="px-4 py-2"><?= date('l, F j, Y', strtotime($row['schedule'])) . ' at ' . date('g:i A', strtotime($row['schedule'])) ?></td>
                    <td class="px-4 py-2"><?= htmlspecialchars($row['content']) ?></td>
                    <td class="px-4 py-2">
                        <?php
                            // Set up paths for the image
                            $uploadsDir = 'admins/include/uploads/';
                            $bannerPath = $uploadsDir . $row['banner'];

                            // Check if the image exists
                            if (!empty($row['banner']) && file_exists($bannerPath)) {
                                $imageSrc = $bannerPath;
                            } else {
                                $imageSrc = 'path_to_placeholder.jpg'; // Replace with your placeholder image
                            }
                        ?>
                        <img 
                            src="<?= htmlspecialchars($imageSrc) ?>" 
                            alt="Event image" 
                            class="max-w-xs max-h-24 object-contain cursor-pointer rounded-lg border"
                            onclick="openModal('<?= htmlspecialchars($imageSrc) ?>')"
                        >
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- Modal to view images -->
<div id="images-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white p-6 rounded-lg max-w-lg w-full relative overflow-hidden">
        <!-- Close button -->
        <span class="text-gray-600 cursor-pointer text-xl font-bold absolute top-4 right-4" onclick="closeModal()">&times;</span>

        <!-- Image Container -->
        <div class="flex justify-center items-center mb-6" id="image-container">
            <img id="modal-image" src="" alt="Event image" class="max-w-full max-h-[80vh] object-contain border-4 border-gray-300 transition-all cursor-move" />
        </div>

        <!-- Zoom Controls and Back Button -->
        <div class="absolute bottom-6 left-1/2 transform -translate-x-1/2 flex items-center justify-between w-full px-6">
            <!-- Zoom Out button on the left -->
            <button onclick="zoomOut()" class="text-white bg-blue-500 p-2 rounded absolute left-6">Zoom Out</button>

            <!-- Back button in the center -->
            <button onclick="goBack()" class="text-white bg-gray-500 p-2 rounded absolute left-1/2 transform -translate-x-1/2">Back</button>

            <!-- Zoom In button on the right -->
            <button onclick="zoomIn()" class="text-white bg-blue-500 p-2 rounded absolute right-6">Zoom In</button>
        </div>
    </div>
</div>

<script>
    // Variables to keep track of zoom level and limits
    let zoomLevel = 1;
    const zoomIncrement = 0.2;
    const zoomMin = 0.5;
    const zoomMax = 3;
    let isDragging = false;
    let offsetX, offsetY;

    // Open modal and display the clicked image
    function openModal(imageSrc) {
        const modal = document.getElementById('images-modal');
        const modalImage = document.getElementById('modal-image');
        const imageContainer = document.getElementById('image-container');
        
        modalImage.src = imageSrc;
        modal.classList.remove('hidden');
        zoomLevel = 1; // Reset zoom level when modal opens
        modalImage.style.transform = `scale(${zoomLevel})`; // Apply initial zoom
        modalImage.classList.remove('transition-all'); // Remove transition when first setting
        imageContainer.style.borderWidth = `${zoomLevel * 4}px`; // Adjust border width according to zoom level
    }

    // Close modal and clear the image source to prevent caching issues
    function closeModal() {
        const modal = document.getElementById('images-modal');
        const modalImage = document.getElementById('modal-image');
        modalImage.src = ''; // Clear the image source
        modal.classList.add('hidden');
    }

    // Zoom In function
    function zoomIn() {
        const modalImage = document.getElementById('modal-image');
        const imageContainer = document.getElementById('image-container');
        
        if (zoomLevel < zoomMax) {
            zoomLevel += zoomIncrement;
            modalImage.style.transform = `scale(${zoomLevel})`;
            imageContainer.style.borderWidth = `${zoomLevel * 4}px`; // Adjust the border width with zoom
        }
    }

    // Zoom Out function
    function zoomOut() {
        const modalImage = document.getElementById('modal-image');
        const imageContainer = document.getElementById('image-container');
        
        if (zoomLevel > zoomMin) {
            zoomLevel -= zoomIncrement;
            modalImage.style.transform = `scale(${zoomLevel})`;
            imageContainer.style.borderWidth = `${zoomLevel * 4}px`; // Adjust the border width with zoom
        }
    }

    // Go Back function (close the modal)
    function goBack() {
        closeModal();
    }

    // Mouse down event to initiate drag
    document.getElementById('modal-image').addEventListener('mousedown', function(e) {
        isDragging = true;
        offsetX = e.clientX - this.offsetLeft;
        offsetY = e.clientY - this.offsetTop;
        this.style.cursor = 'grabbing'; // Change cursor to grabbing
    });

    // Mouse move event to drag the image
    document.getElementById('modal-image').addEventListener('mousemove', function(e) {
        if (isDragging) {
            const modalImage = document.getElementById('modal-image');
            modalImage.style.left = `${e.clientX - offsetX}px`;
            modalImage.style.top = `${e.clientY - offsetY}px`;
        }
    });

    // Mouse up event to stop dragging
    document.addEventListener('mouseup', function() {
        isDragging = false;
        const modalImage = document.getElementById('modal-image');
        modalImage.style.cursor = 'move'; // Reset cursor to move when drag stops
    });

    // Prevent image from being dragged out of the modal area
    document.getElementById('modal-image').addEventListener('dragstart', function(e) {
        e.preventDefault();
    });
</script>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
