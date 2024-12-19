<script src="https://cdn.tailwindcss.com"></script>

<style>
  .hidden-nav {
    transform: translateY(-100%);
    transition: transform 0.3s ease-in-out;
  }
</style>

<div id="navbar" class="sticky top-0 py-1 lg:py-2 w-full bg-transparent z-50 dark:bg-yellow-500">
    <nav class="max-w-4xl xl:max-w-5xl mx-auto px-5 py-2.5 lg:border-none lg:py-4">
        <div class="flex items-center justify-between">
            <button>
                <div class="flex items-center space-x-2">
                    <a href="index.php">
                        <h2 class="text-black dark:text-white font-bold text-2xl">Alumni Management System</h2>
                    </a>
                </div>
            </button>
            <div class="hidden lg:block">
                <ul class="flex space-x-10 text-base font-bold text-black/60 dark:text-white">
                    <li class="hover:underline hover:underline-offset-4 hover:w-fit transition-all duration-100 ease-linear">
                        <a href="index.php">Home</a>
                    </li>
                    <li class="hover:underline hover:underline-offset-4 hover:w-fit transition-all duration-100 ease-linear">
                        <a href="auth/login.php">Alumni Donation</a>
                    </li>
                    <li class="hover:underline hover:underline-offset-4 hover:w-fit transition-all duration-100 ease-linear">
                        <a href="gallery.php">Gallery</a>
                    </li>
                    <li class="hover:underline hover:underline-offset-4 hover:w-fit transition-all duration-100 ease-linear">
                        <a href="about.php">About</a>
                    </li>
                </ul>
                
            </div>
            <div class="hidden lg:flex lg:items-center gap-x-2">
                <a href="auth/signup.php">
                    <button class="flex items-center text-black dark:text-white justify-center px-6 py-2.5 font-semibold">Sign up</button>
                </a>
                <a href="auth/login.php">
                    <button class="flex items-center justify-center rounded-md bg-[#4A3BFF] text-white px-6 py-2.5 font-semibold hover:shadow-lg hover:drop-shadow transition duration-200">Login</button>
                </a>
            </div>
            <div class="flex items-center justify-center lg:hidden">
                <button id="burger" class="focus:outline-none text-slate-200 dark:text-white">
                    <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 20 20" aria-hidden="true" class="text-2xl text-slate-800 dark:text-white focus:outline-none active:scale-110 active:text-red-500" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM9 15a1 1 0 011-1h6a1 1 0 110 2h-6a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden lg:hidden">
            <ul class="flex flex-col space-y-4 text-base font-bold text-black/60 dark:text-white mt-4">
                <li class="hover:underline hover:underline-offset-4 hover:w-fit transition-all duration-100 ease-linear">
                    <a href="index.php">Home</a>
                </li>
                <li class="hover:underline hover:underline-offset-4 hover:w-fit transition-all duration-100 ease-linear">
                    <a href="auth/login.php">Alumni Donation</a>
                </li>
                <li class="hover:underline hover:underline-offset-4 hover:w-fit transition-all duration-100 ease-linear">
                    <a href="gallery.php">Gallery</a>
                </li>
                <li class="hover:underline hover:underline-offset-4 hover:w-fit transition-all duration-100 ease-linear">
                    <a href="about.php">About</a>
                </li>
                <li>
                    <a href="auth/signup.php">
                        <button class="w-full text-black dark:text-white px-6 py-2.5 font-semibold">Sign up</button>
                    </a>
                </li>
                <li>
                    <a href="auth/login.php">
                        <button class="w-full rounded-md bg-[#4A3BFF] text-white px-6 py-2.5 font-semibold hover:shadow-lg hover:drop-shadow transition duration-200">Login</button>
                    </a>
                </li>
            </ul>
        </div>
    </nav>
</div>

<script>
    let lastScrollTop = 0;
    const navbar = document.getElementById("navbar");
    const burger = document.getElementById("burger");
    const mobileMenu = document.getElementById("mobile-menu");

    window.addEventListener("scroll", function() {
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        
        if (scrollTop > lastScrollTop) {
            // Scrolling down
            navbar.classList.add("hidden-nav");
        } else {
            // Scrolling up
            navbar.classList.remove("hidden-nav");
        }
        
        lastScrollTop = scrollTop;
    });

    // Toggle mobile menu visibility when burger icon is clicked
    burger.addEventListener("click", function() {
        mobileMenu.classList.toggle("hidden");
    });
</script>
