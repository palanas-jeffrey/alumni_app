<?php
session_start();

// If the user is not logged in, redirect to login page
if (!isset($_SESSION['email'])) {
    header("Location: ../auth/login.php");
    exit();
}

// Ensure only alumnus can access this page
if ($_SESSION['user_type'] !== 'alumnus') {
    header("Location: login.php");
    exit();
}

?>
<script src="https://cdn.tailwindcss.com"></script>

<style>
  .hidden-nav {
    transform: translateY(-100%);
    transition: transform 0.3s ease-in-out;
  }
</style>

<div class="top-0 py-1 lg:py-2 w-full bg-gray-800 lg:relative z-50">
    <nav class="z-10 sticky top-0 left-0 right-0 max-w-4xl xl:max-w-5xl mx-auto px-5 py-2.5 lg:border-none lg:py-4">
        <div class="flex items-center justify-between">
        <!-- Logo Section -->
        <a href="alumni.php" class="text-white font-bold text-xl">
            Alumni Management System
        </a>

        <!-- Desktop Links -->
        <ul class="hidden lg:flex space-x-6 text-base font-medium text-white">
            <li class="hover:text-gray-300">
                <a href="alumnushomepage.php">Home</a>
            </li>
            <li class="hover:text-gray-300">
                <a href="donations.php">Alumni Donation</a>
            </li>
            <li class="hover:text-gray-300">
                <a href="gallery.php">Gallery</a>
            </li>
            <li class="hover:text-gray-300">
                <a href="about.php">About</a>
            </li>
            <li class="hover:text-gray-300">
                <a href="my_account.php">My Account</a>
            </li>
            <li class="hover:text-gray-300">
                <a href="tracer_form.php">Tracer Form</a>
            </li>
        </ul>

        <!-- Logout Button -->
        <div class="hidden lg:flex">
            <a href="../auth/logout.php">
                <button class="bg-red-500 hover:bg-red-600 text-white font-semibold px-4 py-2 rounded">
                    Logout
                </button>
            </a>
        </div>

        <!-- Mobile Menu Toggle -->
        <button id="burger" class="lg:hidden text-white text-2xl focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
            </svg>
        </button>
    </nav>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="hidden lg:hidden bg-gray-800 text-white">
        <ul class="flex flex-col space-y-4 py-4 text-base font-medium text-center">
            <li>
                <a href="alumnushomepage.php" class="hover:text-gray-300">Home</a>
            </li>
            <li>
                <a href="donations.php" class="hover:text-gray-300">Alumni Donation</a>
            </li>
            <li>
                <a href="gallery.php" class="hover:text-gray-300">Gallery</a>
            </li>
            <li>
                <a href="about.php" class="hover:text-gray-300">About</a>
            </li>
            <li>
                <a href="my_account.php" class="hover:text-gray-300">My Account</a>
            </li>
            <li>
                <a href="tracer_form.php" class="hover:text-gray-300">Tracer Form</a>
            </li>
            <li>
                <a href="../auth/logout.php">
                    <button class="w-full bg-red-500 hover:bg-red-600 text-white font-semibold px-4 py-2 rounded">
                        Logout
                    </button>
                </a>
            </li>
        </ul>
    </div>
</div>

<script>
    const burger = document.getElementById("burger");
    const mobileMenu = document.getElementById("mobile-menu");

    // Toggle mobile menu visibility when the burger icon is clicked
    burger.addEventListener("click", function () {
        mobileMenu.classList.toggle("hidden");
    });
</script>
