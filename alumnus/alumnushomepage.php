<?php
include 'navbar.php';
include '../database.php'; // Include your database connection

// Fetch upcoming and past events from the database
$currentDate = date('Y-m-d H:i:s');

// Query for upcoming events
$newEventsQuery = $conn->query("SELECT * FROM events WHERE schedule >= '$currentDate' ORDER BY schedule ASC");

// Query for past events
$pastEventsQuery = $conn->query("SELECT * FROM events WHERE schedule < '$currentDate' ORDER BY schedule DESC");
?>

<script src="https://cdn.tailwindcss.com"></script>

<div class="min-h-screen bg-gray-100 p-6">
    <div class="container mx-auto">

        <!-- New Events Section -->
        <div class="mb-12">
            <h2 class="text-3xl font-bold text-center text-blue-500 mb-6">Upcoming Events</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php while ($event = $newEventsQuery->fetch_assoc()): ?>
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <?php
                        // Construct the image path for new events
                        $imagePath = !empty($event['banner']) && file_exists("../admins/include/uploads/" . $event['banner'])
                            ? "../admins/include/uploads/" . $event['banner']
                            : 'path_to_placeholder.jpg'; // Path to placeholder image
                        ?>
                        <img 
                            src="<?= htmlspecialchars($imagePath) ?>" 
                            alt="Event Image" 
                            class="w-full h-48 object-cover"
                        >
                        <div class="p-4">
                            <h3 class="text-xl font-bold text-blue-600 mb-2"><?= htmlspecialchars($event['title']) ?></h3>
                            <p class="text-gray-600 mb-2"><?= htmlspecialchars($event['content']) ?></p>
                            <p class="text-gray-500 text-sm">
                                <?= date('l, F j, Y', strtotime($event['schedule'])) ?> at <?= date('g:i A', strtotime($event['schedule'])) ?>
                            </p>
                            <!-- View Button to open the modal -->
                            <button 
                                onclick="openModal('<?= htmlspecialchars($imagePath) ?>')" 
                                class="mt-4 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600"
                            >
                                View Image
                            </button>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>

        <!-- Past Events Section -->
        <div>
            <h2 class="text-3xl font-bold text-center text-gray-500 mb-6">Past Events</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php while ($event = $pastEventsQuery->fetch_assoc()): ?>
                    <div class="bg-gray-200 rounded-lg shadow-md overflow-hidden">
                        <?php
                        // Construct the image path for past events
                        $imagePath = !empty($event['banner']) && file_exists("../admins/include/uploads/" . $event['banner'])
                            ? "../admins/include/uploads/" . $event['banner']
                            : 'path_to_placeholder.jpg'; // Path to placeholder image
                        ?>
                        <img 
                            src="<?= htmlspecialchars($imagePath) ?>" 
                            alt="Event Image" 
                            class="w-full h-48 object-cover"
                        >
                        <div class="p-4">
                            <h3 class="text-xl font-bold text-blue-600 mb-2"><?= htmlspecialchars($event['title']) ?></h3>
                            <p class="text-gray-700 mb-2"><?= htmlspecialchars($event['content']) ?></p>
                            <p class="text-gray-500 text-sm">
                                <?= date('l, F j, Y', strtotime($event['schedule'])) ?> at <?= date('g:i A', strtotime($event['schedule'])) ?>
                            </p>
                            <!-- View Button to open the modal -->
                            <button 
                                onclick="openModal('<?= htmlspecialchars($imagePath) ?>')" 
                                class="mt-4 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600"
                            >
                                View Image
                            </button>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>

    </div>
</div>

<!-- Image Modal -->
<div id="image-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white p-6 rounded-lg max-w-lg w-full relative overflow-hidden">
        <!-- Close button -->
        <span class="text-gray-600 cursor-pointer text-xl font-bold absolute top-4 right-4" onclick="closeModal()">&times;</span>

        <!-- Image Container -->
        <div class="flex justify-center items-center mb-6" id="modal-image-container">
            <img id="modal-image" src="" alt="Event Image" class="max-w-full max-h-[80vh] object-contain border-4 border-gray-300 transition-all" />
        </div>

        <!-- Zoom Controls -->
        <div class="absolute bottom-6 left-1/2 transform -translate-x-1/2 flex items-center justify-between w-full px-6">
            <!-- Zoom Out button -->
            <button onclick="zoomOut()" class="text-white bg-blue-500 p-2 rounded absolute left-6">Zoom Out</button>

            <!-- Back button -->
            <button onclick="goBack()" class="text-white bg-gray-500 p-2 rounded absolute left-1/2 transform -translate-x-1/2">Back</button>

            <!-- Zoom In button -->
            <button onclick="zoomIn()" class="text-white bg-blue-500 p-2 rounded absolute right-6">Zoom In</button>
        </div>
    </div>
</div>

<script>
    // Variables for zoom functionality
    let zoomLevel = 1;
    const zoomIncrement = 0.2;
    const zoomMin = 0.5;
    const zoomMax = 3;
    
    // Open the modal and set the image
    function openModal(imageSrc) {
        const modal = document.getElementById('image-modal');
        const modalImage = document.getElementById('modal-image');
        modalImage.src = imageSrc;
        modal.classList.remove('hidden');
        zoomLevel = 1;
        modalImage.style.transform = `scale(${zoomLevel})`;
    }

    // Close the modal
    function closeModal() {
        const modal = document.getElementById('image-modal');
        modal.classList.add('hidden');
    }

    // Zoom In
    function zoomIn() {
        const modalImage = document.getElementById('modal-image');
        if (zoomLevel < zoomMax) {
            zoomLevel += zoomIncrement;
            modalImage.style.transform = `scale(${zoomLevel})`;
        }
    }

    // Zoom Out
    function zoomOut() {
        const modalImage = document.getElementById('modal-image');
        if (zoomLevel > zoomMin) {
            zoomLevel -= zoomIncrement;
            modalImage.style.transform = `scale(${zoomLevel})`;
        }
    }

    // Close the modal and go back
    function goBack() {
        closeModal();
    }
</script>
