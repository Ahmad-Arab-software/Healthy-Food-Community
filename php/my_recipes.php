<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include './connect.php';

// Controleer of de gebruiker is ingelogd
if (!isset($_SESSION['user_id'])) {
    header('Location: ./index.php');
    exit();
}

// Haal het ID van de ingelogde gebruiker op
$user_id = $_SESSION['user_id'];

// Query om recepten voor de ingelogde gebruiker op te halen
$stmt = $conn->prepare("SELECT * FROM recipes WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My recipes</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../css/index.css">
    <link rel="stylesheet" href="../css/test.css">
    <link rel="shortcut icon" type="image/x-icon" href="../media/Logo.png" />
    <script src="../js/app.js" defer></script>
    <script src="../js/recipePopUp.js" defer></script>
    <script src="../js/cocktails.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#213526] text-white">

<header class='border-b bg-white font-sans min-h-[60px] px-10 py-3 relative tracking-wide z-1'>
    <div class='flex flex-wrap items-center max-lg:gap-y-6 max-sm:gap-x-4'>
        <a href="../home.php"><img src="../media/Logo.png" alt="logo" class='w-36' />
        </a>
        <p class="text-gray-600">
            <?php
            if (isset($_SESSION['email'])) {
                $email = $_SESSION['email'];
                $query = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");

                if ($row = mysqli_fetch_array($query)) {
                    echo "Hallo, " . htmlspecialchars($row['firstName']);
                } else {
                    header('Location: ./index.php');
                }
            } else {
                header('Location: ./index.php');
            }
            ?>
        </p>
        <div id="collapseMenu"
             class="max-lg:hidden lg:!flex lg:items-center max-lg:before:fixed max-lg:before:bg-black max-lg:before:opacity-40 max-lg:before:inset-0 max-lg:before: z-1">
            <button id="toggleClose" class='lg:hidden fixed top-2 right-4 z-[100] rounded-full bg-white p-3'>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 fill-black" viewBox="0 0 320.591 320.591">
                    <path d="M30.391 318.583a30.37 30.37 0 0 1-21.56-7.288c-11.774-11.844-11.774-30.973 0-42.817L266.643 10.665c12.246-11.459 31.462-10.822 42.921 1.424 10.362 11.074 10.966 28.095 1.414 39.875L51.647 311.295a30.366 30.366 0 0 1-21.256 7.288z"
                          data-original="#000000"></path>
                    <path d="M287.9 318.583a30.37 30.37 0 0 1-21.257-8.806L8.83 51.963C-2.078 39.225-.595 20.055 12.143 9.146c11.369-9.736 28.136-9.736 39.504 0l259.331 257.813c12.243 11.462 12.876 30.679 1.414 42.922-.456.487-.927.958-1.414 1.414a30.368 30.368 0 0 1-23.078 7.288z"
                          data-original="#000000"></path>
                </svg>
            </button>

            <ul class='lg:flex lg:gap-x-10 lg:absolute lg:left-1/2 lg:-translate-x-1/2 max-lg:space-y-3 max-lg:fixed max-lg:bg-white max-lg:w-2/3 max-lg:min-w-[300px] max-lg:top-0 max-lg:left-0 max-lg:px-10 max-lg:py-4 max-lg:h-full max-lg:shadow-md max-lg:overflow-auto z-50'>
                <li class='mb-6 hidden max-lg:block'>
                    <a href="../home.php"><img src="../media/Logo.png" alt="logo" class='w-36' />
                    </a>
                <li class='max-lg:border-b max-lg:py-3'>
                    <a href='../home.php' class='hover:text-[#178a0e] text-gray-600 font-bold text-[15px] block'>Home</a>
                </li>
                <li class='max-lg:border-b max-lg:py-3'>
                    <a href='./gerechten.php' class='hover:text-[#178a0e] text-gray-600 font-bold text-[15px] block'>All Recipes</a>
                </li>
                <li class='max-lg:border-b max-lg:py-3'>
                    <a href='./my_recipes.php' class='hover:text-[#178a0e] text-gray-600 font-bold text-[15px] block'>My Recipes</a>
                </li>
                <li class='max-lg:border-b max-lg:py-3'>
                    <a href='./YourRecepie.php' class='hover:text-[#178a0e] text-gray-600 font-bold text-[15px] block'>Add Recipes</a>
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
                <a href="./favo_recipe.php">
                    <i class="fas fa-heart text-red-500 text-2xl" ></i>
                </a>
            </ul>
            <form action="./logout.php">
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
<!-- Recipe Details Popup -->
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
</section>


<div class="container mx-auto mt-12">
    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <?php
            $ingredients_query = "SELECT * FROM ingredients WHERE recipe_id = " . $row["id"];
            $stmt = $conn->prepare($ingredients_query);
            $stmt->execute();
            $ingredients = $stmt->get_result();
            ?>
            <div class="max-w-md mx-auto bg-white rounded-xl shadow-md overflow-hidden md:max-w-2xl mb-8">
                <div class="flex flex-col">
                    <div class="flex justify-center">
                        <img class="h-64 w-full object-cover" src="<?= htmlspecialchars($row['image_path']); ?>" alt="Recipe Image">
                    </div>
                    <div class="p-6 sm:p-8"> <!-- Responsive padding toegevoegd -->
                        <h2 class="text-black font-bold mt-2 text-2xl sm:text-3xl"> <!-- Responsive text grootte -->
                            <?= htmlspecialchars($row['title']); ?>
                        </h2>
                        <ul class="text-black mt-4">
                            <li class="font-semibold font-bold mt-2 text-xl sm:text-2xl">
                                <i class="fas fa-utensils"></i> Ingredients:
                            </li>
                            <?php if ($ingredients->num_rows == 0): ?>
                                <p class="text-gray-500">No recipes found</p>
                            <?php endif ?>

                            <?php while ($ingredient = $ingredients->fetch_assoc()): ?>
                                <li class="flex items-center text-lg sm:text-xl"> <!-- Responsive text grootte -->
                                    <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                    <?= htmlspecialchars($ingredient["ingredient_name"]) . " " . htmlspecialchars($ingredient["quantity"]); ?>
                                </li>
                            <?php endwhile; ?>
                        </ul>
                        <h1 class="text-black font-bold mt-2 text-lg sm:text-xl">Description</h1> <!-- Responsive text grootte -->
                        <p class="text-black mt-4 text-lg sm:text-xl"> <!-- Responsive text grootte -->
                            <?= htmlspecialchars($row['description']); ?>
                        </p>
                        <div class="flex space-x-2 mt-4">
                            <!-- Edit Recipe Button -->
                            <form action="./edit_recipe.php" method="get">
                                <input type="hidden" name="id" value="<?= $row['id']; ?>" />
                                <button type="submit" class="flex items-center px-6 py-2 sm:px-8 sm:py-3 bg-blue-500 text-white rounded hover:bg-blue-600 text-lg sm:text-xl">
                                    <i class="fas fa-edit mr-2"></i> Edit Recipe
                                </button>
                            </form>

                            <!-- Delete Recipe Button -->
                            <form action="./delete_recipe.php" method="post">
                                <input type="hidden" name="recipe_id" value="<?= $row['id']; ?>">
                                <button type="submit" class="flex items-center px-6 py-2 sm:px-8 sm:py-3 bg-red-500 text-white rounded hover:bg-red-600 text-lg sm:text-xl">
                                    <i class="fas fa-trash-alt mr-2"></i> Delete Recipe
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p class="text-center text-gray-500 mt-8">No recipes found.</p>
    <?php endif; ?>
</div>


</body>
</html>
