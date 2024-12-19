<script src="https://cdn.tailwindcss.com"></script>

<style>
  .hidden-nav {
    transform: translateY(-100%);
    transition: transform 0.3s ease-in-out;
  }

  .mobile-menu-hidden {
    display: none;
  }

  .mobile-menu-visible {
    display: block;
  }
</style>

<div id="navbar" class="sticky top-0 py-1 lg:py-2 w-full bg-transparent z-50 dark:bg-gray-900">
  <nav class="max-w-4xl xl:max-w-5xl mx-auto px-5 py-2.5 lg:border-none lg:py-4">
    <div class="flex items-center justify-between">
      <button>
        <div class="flex items-center space-x-2">
          <a href="../index.php">
            <h2 class="text-black dark:text-white font-bold text-2xl">Alumni Management System</h2>
          </a>
        </div>
      </button>
      <div class="hidden lg:block">
        <ul class="flex space-x-10 text-base font-bold text-black/60 dark:text-white">
          <li class="hover:underline hover:underline-offset-4 hover:w-fit transition-all duration-100 ease-linear">
            <a href="../index.php">Home</a>
          </li>
          <li class="hover:underline hover:underline-offset-4 hover:w-fit transition-all duration-100 ease-linear">
            <a href="login.php">Alumni Donation</a>
          </li>
          <li class="hover:underline hover:underline-offset-4 hover:w-fit transition-all duration-100 ease-linear">
            <a href="../gallery.php">Gallery</a>
          </li>
          <li class="hover:underline hover:underline-offset-4 hover:w-fit transition-all duration-100 ease-linear">
            <a href="../about.php">About</a>
          </li>
        </ul>
      </div>
      <!-- Burger Icon for small screens -->
      <div class="flex items-center justify-center lg:hidden">
        <button id="burger-btn" class="focus:outline-none text-slate-200 dark:text-white">
          <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 20 20" aria-hidden="true"
            class="text-2xl text-slate-800 dark:text-white focus:outline-none active:scale-110 active:text-red-500"
            height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd"
              d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM9 15a1 1 0 011-1h6a1 1 0 110 2h-6a1 1 0 01-1-1z"
              clip-rule="evenodd"></path>
          </svg>
        </button>
      </div>
    </div>
  </nav>
</div>

<!-- Mobile Menu -->
<div id="mobile-menu" class="mobile-menu-hidden bg-gray-100 dark:bg-gray-800 lg:hidden">
  <ul class="flex flex-col space-y-4 text-base font-bold text-black/60 dark:text-white px-5 py-5">
    <li>
      <a href="../index.php">Home</a>
    </li>
    <li>
      <a href="login.php">Alumni Donation</a>
    </li>
    <li>
      <a href="../gallery.php">Gallery</a>
    </li>
    <li>
      <a href="../about.php">About</a>
    </li>
  </ul>
</div>

<script>
  // Navbar hide on scroll down and show on scroll up
  let lastScrollTop = 0;
  const navbar = document.getElementById("navbar");

  window.addEventListener("scroll", function () {
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

  // Toggle mobile menu on burger icon click
  const burgerBtn = document.getElementById("burger-btn");
  const mobileMenu = document.getElementById("mobile-menu");

  burgerBtn.addEventListener("click", function () {
    if (mobileMenu.classList.contains("mobile-menu-hidden")) {
      mobileMenu.classList.remove("mobile-menu-hidden");
      mobileMenu.classList.add("mobile-menu-visible");
    } else {
      mobileMenu.classList.remove("mobile-menu-visible");
      mobileMenu.classList.add("mobile-menu-hidden");
    }
  });
</script>
