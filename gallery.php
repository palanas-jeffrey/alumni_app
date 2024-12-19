<?php
include 'navbar.php';
include 'database.php';

// Fetch all images from the `gallery` table
$query = $conn->query("SELECT * FROM gallery ORDER BY gallery_id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gallery with Popup and Zoom</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-100">

<!-- Page Container -->
<div class="min-h-screen flex flex-col items-center py-10">
    <h1 class="text-3xl font-bold mb-8">Image Gallery</h1>

    <!-- Gallery Grid -->
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-6 px-4 max-w-screen-lg w-full">
        <?php while ($row = $query->fetch_assoc()): ?>
            <div class="relative group">
                <?php 
                    $imagePath = 'admins/uploads/' . $row['about'];
                    if (file_exists(__DIR__ . '/admins/uploads/' . $row['about'])): 
                ?>
                    <img src="<?= $imagePath ?>" alt="Gallery Image" 
                         class="popup-trigger h-48 w-full object-cover rounded-lg shadow-md cursor-pointer transition-transform duration-200 group-hover:scale-105"
                         data-image="<?= $imagePath ?>" />
                <?php else: ?>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<!-- Popup Modal -->
<div id="popup" class="hidden fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center">
    <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-lg max-w-4xl mx-auto p-6">
        <!-- Close Button -->
        <button id="close-popup" class="absolute top-3 right-3 text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-gray-100">
            &times;
        </button>

        <!-- Popup Image -->
        <div class="relative flex flex-col items-center">
            <img id="popup-image" src="" alt="Popup Image" class="w-full h-auto rounded-lg transition-transform" style="max-width: 100%; max-height: 90vh;" />
            <div class="flex space-x-4 mt-4">
                 <!-- Previous Button -->
                 <button id="previous-button" 
                        class="bg-purple-600 text-white py-2 px-4 z-10 rounded-lg hover:bg-purple-700 transition">
                    Previous
                </button>
                <!-- Zoom Out Button -->
                <button id="zoom-out-button" 
                        class="bg-red-600 text-white py-2 px-4 z-10 rounded-lg hover:bg-red-700 transition">
                    Zoom Out
                </button>
                <!-- Zoom In Button -->
                <button id="zoom-in-button" 
                        class="bg-green-600 text-white py-2 px-4 z-10 rounded-lg hover:bg-green-700 transition">
                    Zoom In
                </button>
                <!-- Next Button -->
                <button id="next-button" 
                        class="bg-blue-600 text-white py-2 px-4 z-10 rounded-lg hover:bg-blue-700 transition">
                    Next
                </button>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for Popup Functionality -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    const popup = document.getElementById('popup');
    const popupImage = document.getElementById('popup-image');
    const closeButton = document.getElementById('close-popup');
    const previousButton = document.getElementById('previous-button');
    const nextButton = document.getElementById('next-button');
    const zoomInButton = document.getElementById('zoom-in-button');
    const zoomOutButton = document.getElementById('zoom-out-button');
    const images = Array.from(document.querySelectorAll('.popup-trigger')); // All images in the gallery
    let currentIndex = 0;
    let currentZoom = 1;

    // Function to open the popup
    function openPopup(index) {
        currentIndex = index;
        currentZoom = 1; // Reset zoom when opening a new image
        popupImage.style.transform = `scale(${currentZoom})`;
        popupImage.src = images[currentIndex].dataset.image;
        popup.classList.remove('hidden');
    }

    // Function to close the popup
    function closePopup() {
        popup.classList.add('hidden');
    }

    // Function to go to the next image
    function nextImage() {
        currentIndex = (currentIndex + 1) % images.length; // Loop to the start if at the end
        openPopup(currentIndex);
    }

    // Function to go to the previous image
    function previousImage() {
        currentIndex = (currentIndex - 1 + images.length) % images.length; // Loop to the end if at the start
        openPopup(currentIndex);
    }

    // Function to zoom in
    function zoomIn() {
        currentZoom += 0.1;
        popupImage.style.transform = `scale(${currentZoom})`;
    }

    // Function to zoom out
    function zoomOut() {
        if (currentZoom > 0.5) { // Minimum zoom level
            currentZoom -= 0.1;
            popupImage.style.transform = `scale(${currentZoom})`;
        }
    }

    // Add event listeners to all images
    images.forEach((img, index) => {
        img.addEventListener('click', () => openPopup(index));
    });

    // Event listener for closing the popup
    closeButton.addEventListener('click', closePopup);

    // Event listener for the "Next" button
    nextButton.addEventListener('click', nextImage);

    // Event listener for the "Previous" button
    previousButton.addEventListener('click', previousImage);

    // Event listeners for zoom buttons
    zoomInButton.addEventListener('click', zoomIn);
    zoomOutButton.addEventListener('click', zoomOut);

    // Close popup when clicking outside the image
    popup.addEventListener('click', (e) => {
        if (e.target === popup) closePopup();
    });
});
</script>
</body>
</html>