<!--made by Ahmad Arab-->
<?php
session_start();
include("./php/connect.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Healthy Food Community</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <link rel="stylesheet" href="./css/index.css">
  <link rel="stylesheet" href="./css/test.css">
  <link rel="shortcut icon" type="image/x-icon" href="./media/Logo.png" />
  <script src="./js/app.js" defer></script>
  <script src="./js/cocktails.js" defer></script>
  <!--    <script src="./js/gerechten.js"  defer></script>-->
  <script src="./js/recipePopUp.js" defer></script>
  <script src="./js/smooth_scroll.js" defer></script>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="./js/contact.js" defer></script>
</head>

<body class="bg-[#213526] text-white">
  <!-- Navbar -->

  <header class='border-b bg-white font-sans min-h-[60px] px-10 py-3 relative tracking-wide z-1'>
    <div class='flex flex-wrap items-center max-lg:gap-y-6 max-sm:gap-x-4'>
      <a href="./home.php"><img src="./media/Logo.png" alt="logo" class='w-36' />
      </a>
      <p class="text-gray-600">
        <?php
        if (isset($_SESSION['email'])) {
          $email = $_SESSION['email'];
          $query = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");

          if ($row = mysqli_fetch_array($query)) {
            echo "Hallo, " . htmlspecialchars($row['firstName']);
          } else {
            header('Location: ./php/index.php');
          }
        } else {
          header('Location: ./php/index.php');
        }
        ?>
      </p>
      <div id="collapseMenu"
        class="max-lg:hidden lg:!flex lg:items-center max-lg:before:fixed max-lg:before:bg-black max-lg:before:opacity-40 max-lg:before:inset-0 max-lg:before:z-50">
          <?php echo $_SESSION['user_id'] ?>
        <button id="toggleClose" class='lg:hidden fixed top-2 right-4 z-[100] rounded-full bg-white p-3'>
          <svg xmlns="http://www.w3.org/2000/svg" class="w-4 fill-black" viewBox="0 0 320.591 320.591">
            <path d="M30.391 318.583a30.37 30.37 0 0 1-21.56-7.288c-11.774-11.844-11.774-30.973 0-42.817L266.643 10.665c12.246-11.459 31.462-10.822 42.921 1.424 10.362 11.074 10.966 28.095 1.414 39.875L51.647 311.295a30.366 30.366 0 0 1-21.256 7.288z"
              data-original="#000000"></path>
            <path d="M287.9 318.583a30.37 30.37 0 0 1-21.257-8.806L8.83 51.963C-2.078 39.225-.595 20.055 12.143 9.146c11.369-9.736 28.136-9.736 39.504 0l259.331 257.813c12.243 11.462 12.876 30.679 1.414 42.922-.456.487-.927.958-1.414 1.414a30.368 30.368 0 0 1-23.078 7.288z"
              data-original="#000000"></path>
          </svg>
        </button>

        <ul
          class='lg:flex lg:gap-x-10 lg:absolute lg:left-1/2 lg:-translate-x-1/2 max-lg:space-y-3 max-lg:fixed max-lg:bg-white max-lg:w-2/3 max-lg:min-w-[300px] max-lg:top-0 max-lg:left-0 max-lg:px-10 max-lg:py-4 max-lg:h-full max-lg:shadow-md max-lg:overflow-auto z-50'>
          <li class='mb-6 hidden max-lg:block'>
            <a href="./home.php"><img src="./media/Logo.png" alt="logo" class='w-36' />
            </a>
          </li>
          <li class='max-lg:border-b max-lg:py-3'>
            <a href='./home.php' class='hover:text-[#178a0e] text-gray-600 font-bold text-[15px] block'>Home</a>
          </li>
          <li class='max-lg:border-b max-lg:py-3'>
            <a href='./php/gerechten.php' class='hover:text-[#178a0e] text-gray-600 font-bold text-[15px] block'>All Recipes</a>
          </li>
          <li class='max-lg:border-b max-lg:py-3'>
            <a href='./php/my_recipes.php' class='hover:text-[#178a0e] text-gray-600 font-bold text-[15px] block'>My Recipes</a>
          </li>
          <li class='max-lg:border-b max-lg:py-3'>
            <a href='./php/YourRecepie.php' class='hover:text-[#178a0e] text-gray-600 font-bold text-[15px] block'>Add Recipes</a>
          </li>
        </ul>
      </div>


      <div class='flex items-center ml-auto space-x-8'>
        <span class="relative">
          <path d="M45.5 4A18.53 18.53 0 0 0 32 9.86 18.5 18.5 0 0 0 0 22.5C0 40.92 29.71 59 31 59.71a2 2 0 0 0 2.06 0C34.29 59 64 40.92 64 22.5A18.52 18.52 0 0 0 45.5 4ZM32 55.64C26.83 52.34 4 36.92 4 22.5a14.5 14.5 0 0 1 26.36-8.33 2 2 0 0 0 3.27 0A14.5 14.5 0 0 1 60 22.5c0 14.41-22.83 29.83-28 33.14Z"
            data-original="#000000" />
          </svg>
        </span>

        <ul>
          <a href="./php/favo_recipe.php">
            <i class="fas fa-heart text-red-500 text-2xl"></i>
          </a>
        </ul>
        <ul>
          <li id="scroll-to-contact" style="cursor: pointer;">
            <i class="fa-solid fa-envelope text-[#178a0e] text-2xl"></i>
          </li>
        </ul>

        <form action="./php/logout.php">
          <button id="logoutButton" class="flex items-center space-x-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="20px" class="cursor-pointer fill-[#000] hover:fill-[#178a0e]"
              viewBox="0 0 24 24">
              <path d="M16 13v-2h-5V8l-5 4 5 4v-3h5z" />
              <path d="M3 3h12v2H3v14h12v2H3c-1.1 0-2-.9-2-2V5c0-1.1.9-2 2-2z" />
            </svg>
          </button>
        </form>

        <button id="toggleOpen" class='lg:hidden'>
          <svg class="w-7 h-7" fill="#000" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd"
              d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
              clip-rule="evenodd"></path>
          </svg>
        </button>
      </div>
    </div>

    <div
      class='bg-[#213526] border border-transparent focus-within:border-[#178a0e] focus-within:bg-transparent flex px-6 rounded-full h-10 lg:w-2/4 mt-3 mx-auto max-lg:mt-6'>
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 192.904 192.904" width="16px"
        class="fill-gray-600 mr-3 rotate-90">
        <path
          d="m190.707 180.101-47.078-47.077c11.702-14.072 18.752-32.142 18.752-51.831C162.381 36.423 125.959 0 81.191 0 36.422 0 0 36.423 0 81.193c0 44.767 36.422 81.187 81.191 81.187 19.688 0 37.759-7.049 51.831-18.751l47.079 47.078a7.474 7.474 0 0 0 5.303 2.197 7.498 7.498 0 0 0 5.303-12.803zM15 81.193C15 44.694 44.693 15 81.191 15c36.497 0 66.189 29.694 66.189 66.193 0 36.496-29.692 66.187-66.189 66.187C44.693 147.38 15 117.689 15 81.193z">
        </path>
      </svg>
      <input id="search" type='text' placeholder='Search...'
        class="w-full outline-none bg-transparent text-gray-600 font-semibold text-[15px]" />
      <button id="search-btn" class='hidden'>Search</button> <!-- Voeg een verborgen knop toe voor de event listener -->
    </div>
    <div id="suggestion-box"
      class="absolute top-full left-0 right-0 mt-1 text-black bg-white border border-gray-300 rounded-lg shadow-lg hidden z-60 max-h-64 overflow-y-auto">
    </div>

  </header>
  <div class="container mx-auto p-7 pt-32">
    <div class="bg-[#eef2e3] pl-6 pr-3 rounded-lg shadow-lg flex flex-col md:flex-row justify-between items-center ">
        <div class="text-gray-800 text-center md:text-left mb-6 md:mb-0 md:w-1/2 px-4 md:px-0">
            <!-- Reduced mb on small screens with mb-6 and removed px-4, added px-4 for small screen text spacing -->
            <h1 class="text-2xl md:text-3xl lg:text-4xl font-bold mb-8 md:mb-12 lg:mb-16 leading-relaxed">
                <!-- Adjusted text size for responsiveness: text-2xl for small, md:text-3xl for medium, lg:text-4xl for large -->
                <!-- Reduced mb for smaller screens and added leading-relaxed for better text spacing -->
                Craft Wholesome Meals or Share Your Delicious Healthy Recipes with Our Community!
            </h1>
            <p class="mb-8 md:mb-12 lg:mb-16 text-base md:text-lg leading-relaxed">
                <!-- Reduced mb for smaller screens and added leading-relaxed for better text spacing -->
                <!-- Adjusted text size to text-base for small and md:text-lg for medium and larger screens -->
                Join us to explore and exchange unique recipes that inspire healthy living. Together, let's make every meal a delightful experience! We encourage creativity in the kitchen, and your contribution can help others discover new favorites.
            </p>
            <p class="mb-10 md:mb-16 lg:mb-20 text-base md:text-lg leading-relaxed">
                <!-- Reduced mb for smaller screens and added leading-relaxed for better text spacing -->
                <!-- Adjusted text size to text-base for small and md:text-lg for medium and larger screens -->
                Whether you're a seasoned chef or just starting out, there's a place for you here. Share your stories, tips, and techniques while learning from fellow cooking enthusiasts.
            </p>
            <div class="flex flex-col sm:flex-row justify-center sm:justify-start items-center sm:items-start space-y-4 sm:space-y-0 sm:space-x-4">
                <!-- Adjusted button layout to stack vertically on small screens and horizontally on medium+ -->
                <!-- justify-center on mobile to center buttons, sm:justify-start for left align on larger -->
                <button id="scroll-button" class="bg-transparent mb-0 text-[#178a0e] border border-[#178a0e] hover:border-[#213526] py-2 px-5 rounded-lg shadow-lg hover:text-[#213526] text-lg">
                    <!-- Reduced py and px for smaller buttons -->
                    Try Our Healthy Food
                </button>
                <button id="scroll-button2" class="bg-[#178a0e] text-white py-2 px-5 rounded-lg shadow-lg hover:bg-green-700 text-lg">
                    <!-- Reduced py and px for smaller buttons -->
                    Make Your Own!
                </button>
            </div>
        </div>
      <div class="gerechten flex justify-center items-center relative mt-4 md:mt-0">
        <img class="img-2" src="./media/bowl-2.png" alt="Bowl" />
        <img class="img-1" src="./media/ingredient-2.png" alt="Ingredient" />
      </div>
    </div>
  </div>
  <section class="container mx-auto p-6">
    <h2 class="text-3xl text-center font-bold mb-8">What You Can Cook</h2>

    <div id="featured-recipes" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
      <!-- Dinner Recipe Cards will be inserted here -->
    </div>
  </section>

  <!-- Create Your Own Food Section -->
  <section class="  container  mx-auto p-6">
    <section id="recipe-details-popup"
      class="z-50 fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center hidden">
      <div
        class="bg-white text-black p-6 rounded-lg w-11/12 max-h-screen overflow-y-auto relative lg:w-3/4">
        <button id="close-recipe-popup" class="absolute top-4 right-4 text-gray-600">
          <i class="fas fa-times"></i>
        </button>
        <button id="like-recipe-btn" class="absolute mb-2 top-4 left-4 text-red-500 text-2xl">
          <i class="fa-solid fa-heart text-red-500 text-2xl"></i>
        </button>
        <div id="recipe-details" class="mb-4"></div>
        <div id="recipe-video"></div>
      </div>
    </section>
    <h2 id="create-your-own" class="text-3xl text-center font-bold mb-8">Create Your Own Food</h2>
    <div class="bg-[#eef2e3] p-12 rounded-lg shadow-xl flex flex-col md:flex-row justify-between items-center gap-10">
      <div class="text-gray-800 text-center md:text-left max-w-lg">
        <h1 class="text-4xl font-bold mb-12">Share Your Delicious Creations!</h1>
        <p class="text-lg mb-16">Do you have a secret family recipe, a creative twist on a classic dish, or a unique
          dessert everyone raves about? Don't keep it to yourself!<br><br> Join our growing community of passionate home chefs
          and food lovers, and share your culinary creations for the world to enjoy.</p>

        <a href="./php/YourRecepie.php"
          class="bg-[#178a0e] text-white  py-4 px-8 rounded-full shadow-md hover:bg-green-700 transition duration-300 ease-in-out transform hover:scale-105">
          Add Your Recipe
        </a>
      </div>
      <div class="flex justify-center items-center">
        <img src="https://images1.westend61.de/0001046660pw/portrait-of-happy-woman-cooking-in-kitchen-PDF01730.jpg"
          alt="Add Recipe" class="rounded-lg shadow-md w-full max-w-2xl">
      </div>
    </div>

    <h1 class="text-4xl font-bold mb-2 text-center mt-16">Contact Us !</h1>
    <div id="contact"
      class=" bg-[#EEF2E3] max-w-7xl mx-auto h-auto px-4 sm:px-6 lg:px-8 py-20 rounded-2xl shadow-lg flex flex-col-reverse lg:flex-row items-center justify-between relative mt-24">

      <!-- Rechterzijde: Contactformulier -->
      <div class="flex-1 lg:mr-4 mb-4 lg:mb-0">
        <h2 class="text-2xl sm:text-3xl font-bold mb-3 text-center lg:text-left text-gray-800">
          Send us a message
        </h2>
        <form id="contact-form" class="flex flex-col gap-3">
          <input type="hidden" name="ip_address" id="ip_address" />
          <input type="text" name="name" placeholder="Name"
            class=" border border-transparent outline outline-2 outline-[#1f2e13] bg-transparent rounded-lg p-2 placeholder-gray-600 text-gray-800"
            required />
          <input type="email" name="email" placeholder="E-mail"
            class="border border-transparent outline outline-2 outline-[#1f2e13] bg-transparent rounded-lg p-2 placeholder-gray-600 text-gray-800"
            required />
          <textarea name="message" placeholder="Message"
            class="border border-transparent outline outline-2 outline-[#1f2e13] bg-transparent rounded-lg p-2 placeholder-gray-600 text-gray-800"
            rows="4" required></textarea>
          <!--          <button type="submit"-->
          <!--            class="hover:bg-green-900 px-4 py-2 bg-[#178a0e] text-white rounded-lg border-b-4 border-[#1f2e13] hover:underline font-bold">-->
          <!--            Send-->
          <!--          </button>-->
        </form>
        <span id="form-result" class="text-black mt-3"></span>
      </div>

      <!-- Linkerzijde: Contactinformatie -->
      <div class="flex-1 text-left lg:ml-4">
        <h1 class="text-3xl sm:text-5xl md:text-6xl lg:text-7xl xl:text-[70px] font-cooper text-[#1f2e13] mb-3">
          Support
        </h1>
        <p class="text-md mb-3 text-black">
          Help is available whenever you need it, 24 hours a day, 7 days a week.<br> Explore various ways to get
          the
          answers you need and contact us for assistance with any issues on the recipe website.<br> Whether it's
          bugs
          of other errors, we're here to help!
        </p>
      </div>
    </div>

  </section>
  <script>
    function smoothScroll(target, duration) {
      const targetPosition = target.getBoundingClientRect().top + window.pageYOffset;
      const startPosition = window.pageYOffset;
      const distance = targetPosition - startPosition;
      let startTime = null;

      function animation(currentTime) {
        if (startTime === null) startTime = currentTime;
        const timeElapsed = currentTime - startTime;
        const scroll = ease(timeElapsed, startPosition, distance, duration);
        window.scrollTo(0, scroll);
        if (timeElapsed < duration) requestAnimationFrame(animation);
      }

      function ease(t, b, c, d) {
        t /= d / 2;
        if (t < 1) return c / 2 * t * t + b;
        t--;
        return -c / 2 * (t * (t - 2) - 1) + b;
      }

      requestAnimationFrame(animation);
    }

    // Event listener for the first button
    document.getElementById('scroll-button').addEventListener('click', function() {
      const target = document.getElementById('featured-recipes');
      smoothScroll(target, 2000); // Adjust the duration here (in milliseconds)
    });

    // Event listener for the second button
    document.getElementById('scroll-button2').addEventListener('click', function() {
      const target = document.getElementById('create-your-own');
      smoothScroll(target, 2000); // Adjust the duration here (in milliseconds)
    });

    // Event listener for the envelope icon to scroll to contact section
    document.getElementById('scroll-to-contact').addEventListener('click', function() {
      const target = document.getElementById('contact');
      smoothScroll(target, 2000); // Adjust the duration here (in milliseconds)
    });

    // Loader functionality
    let loader = document.getElementById("loader");
    let artboard = document.getElementById("artboard");
    window.addEventListener("load", () => {
      loader.style.display = "none";
      artboard.style.display = "block";
    });
  </script>

</body>

</html>