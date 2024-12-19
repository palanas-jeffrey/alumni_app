<?php
// Determine the current page to apply dynamic highlighting
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<div id="sidebar" class="w-64 h-screen bg-gray-800 text-white flex flex-col fixed top-0 left-0 z-50 transition-transform duration-300 transform -translate-x-full">
    <div class="flex items-center justify-between h-20 bg-gray-900 px-4">
        <h2 class="text-2xl font-semibold text-white">Admin Panel</h2>
        <button id="close-sidebar" class="text-gray-400 hover:text-white text-lg">&times;</button>
    </div>

    <!-- Menu Items -->
    <ul class="flex flex-col space-y-4 px-4 text-base font-bold text-white">
        <li class="hover:bg-gray-700 p-2 rounded-md transition-all duration-100 ease-linear">
            <a href="adminhomepage.php" class="<?= ($currentPage == 'adminhomepage.php') ? 'text-red-400' : '' ?>">Home</a>
        </li>
        <li class="hover:bg-gray-700 p-2 rounded-md transition-all duration-100 ease-linear">
            <a href="AdminMonitorDonations.php" class="<?= ($currentPage == 'AdminMonitorDonations.php') ? 'text-red-400' : '' ?>">Donations</a>
        </li>
        <li class="hover:bg-gray-700 p-2 rounded-md transition-all duration-100 ease-linear">
            <a href="approve_alumni.php" class="<?= ($currentPage == 'approve_alumni.php') ? 'text-red-400' : '' ?>">Manage Alumni Accounts</a>
        </li>
        <li class="hover:bg-gray-700 p-2 rounded-md transition-all duration-100 ease-linear">
            <a href="alumni_report.php" class="<?= ($currentPage == 'alumni_report.php') ? 'text-red-400' : '' ?>">Reports</a>
        </li>
        <li class="hover:bg-gray-700 p-2 rounded-md transition-all duration-100 ease-linear">
            <a href="manage_event.php" class="<?= ($currentPage == 'manage_event.php') ? 'bg-gray-800 text-red-400 p-2 rounded-md' : '' ?>" aria-current="<?= ($currentPage == 'manage_event.php') ? 'page' : '' ?>">
               Manage Events
            </a>
        </li>
        <li class="hover:bg-gray-700 p-2 rounded-md transition-all duration-100 ease-linear">
            <a href="gallery.php" class="<?= ($currentPage == 'gallery.php') ? 'text-red-400' : '' ?>">Gallery</a>
        </li>
        <li class="hover:bg-gray-700 p-2 rounded-md transition-all duration-100 ease-linear">
            <a href="view_tracer_forms.php" class="<?= ($currentPage == 'view_tracer_forms.php') ? 'text-red-400' : '' ?>">Tracer Forms</a>
        </li>
        <!-- New "Response of Alumnus" Menu Item -->
        <li class="hover:bg-gray-700 p-2 rounded-md transition-all duration-100 ease-linear">
            <a href="response_from_alumnus.php" class="<?= ($currentPage == 'response_from_alumnus.php') ? 'text-red-400' : '' ?>">Response of Alumnus</a>
        </li>
    </ul>

    <!-- Logout Button -->
    <div class="mt-auto px-4">
        <a href="../auth/logout.php">
            <button class="w-full flex items-center justify-center rounded-md bg-red-600 text-white px-6 py-2.5 font-semibold hover:shadow-lg transition duration-200">
                Logout
            </button>
        </a>
    </div>
</div>

<!-- Sidebar Toggle Button -->
<button id="toggle-sidebar" class="fixed top-4 left-4 z-60 text-white bg-gray-800 p-2 rounded-md hover:bg-gray-700">
    ☰
</button>

<script>
    // Sidebar toggle logic
    const sidebar = document.getElementById('sidebar');
    const toggleSidebarButton = document.getElementById('toggle-sidebar');
    const closeSidebarButton = document.getElementById('close-sidebar');

    toggleSidebarButton.addEventListener('click', function() {
        sidebar.classList.toggle('-translate-x-full'); // Toggles sidebar visibility
    });

    closeSidebarButton.addEventListener('click', function() {
        sidebar.classList.add('-translate-x-full'); // Hides the sidebar
    });
</script>
