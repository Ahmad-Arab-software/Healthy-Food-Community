document.addEventListener("DOMContentLoaded", function () {
  // Fetch recipes for each section (Breakfast, Lunch, Dinner)
  fetchRecipesByCategory("Breakfast", "breakfast-recipes");
  fetchRandomRecipes("Lunch", "lunch-recipes");
  fetchRandomRecipes("Dinner", "dinner-recipes");

  // Event listener for search button
  const searchBtn = document.getElementById("search-btn");
  const searchInput = document.getElementById("search");
  const suggestionBox = document.getElementById("suggestion-box");

  searchBtn.addEventListener("click", () => {
    const query = searchInput.value;
    if (query) {
      getRecipes(query);
    }
  });

  // Event listener for search input field
  searchInput.addEventListener("input", async () => {
    const query = searchInput.value;
    if (query) {
      const recipes = await getRecipesSuggestions(query);
      displaySuggestions(recipes);
    } else {
      suggestionBox.classList.add("hidden");
    }
  });

  // Close the popup
  const closeRecipePopup = document.getElementById("close-recipe-popup");
  closeRecipePopup.addEventListener("click", () => {
    document.getElementById("recipe-details-popup").classList.add("hidden");
  });
});

// Function to fetch and display recipes based on category
async function fetchRecipesByCategory(category, containerId) {
  try {
    const breakfastResponse = await fetch(
      `https://www.themealdb.com/api/json/v1/1/filter.php?c=${category}`
    );
    const breakfastData = await breakfastResponse.json();

    let recipes = breakfastData.meals || [];
    if (category === "Breakfast") {
      const cakeRecipes = await fetchCategoryRecipes("Cake");
      recipes = [...new Set([...recipes, ...cakeRecipes])]; // Combine and deduplicate
    }

    if (recipes.length === 0) {
      // Backup: Fetch random recipes if no recipes found
      recipes = await fetchRandomRecipesBackup();
    }

    displayRecipes(recipes, containerId, 3); // Display only 3 recipes
  } catch (error) {
    console.error("Error fetching recipes:", error);
  }
}

// Function to fetch recipes for a specific category
async function fetchCategoryRecipes(category) {
  try {
    const response = await fetch(
      `https://www.themealdb.com/api/json/v1/1/filter.php?c=${category}`
    );
    const data = await response.json();
    return data.meals || [];
  } catch (error) {
    console.error("Error fetching category recipes:", error);
    return [];
  }
}

// Function to fetch random recipes for Lunch and Dinner
async function fetchRandomRecipes(category, containerId) {
  try {
    const response = await fetch(
      `https://www.themealdb.com/api/json/v1/1/search.php?s=`
    );
    const data = await response.json();
    if (data.meals) {
      const randomRecipes = getRandomElements(data.meals, 3); // Get 3 random recipes
      displayRecipes(randomRecipes, containerId, 3);
    }
  } catch (error) {
    console.error("Error fetching recipes:", error);
  }
}

// Function to get a specified number of random elements from an array
function getRandomElements(array, count) {
  const shuffled = array.sort(() => 0.5 - Math.random());
  return shuffled.slice(0, count);
}

// Function to fetch backup recipes if no recipes are found for a category
async function fetchRandomRecipesBackup() {
  try {
    const response = await fetch(
      `https://www.themealdb.com/api/json/v1/1/search.php?s=`
    );
    const data = await response.json();
    if (data.meals) {
      return getRandomElements(data.meals, 3); // Get 3 random recipes
    }
    return [];
  } catch (error) {
    console.error("Error fetching backup recipes:", error);
    return [];
  }
}

// Function to display recipes in a specified container with fade-in effect
function displayRecipes(recipes, containerId, limit) {
  const recipesContainer = document.getElementById(containerId);
  const uniqueRecipes = [
    ...new Map(recipes.map((item) => [item.idMeal, item])).values(),
  ]; // Deduplicate

  // Clear the container first
  recipesContainer.innerHTML = "";

  // Create and append recipe cards
  uniqueRecipes.slice(0, limit).forEach((meal, index) => {
    const card = document.createElement("div");
    card.classList.add(
      "card",
      "bg-[#eef2e3]",
      "p-6",
      "rounded-lg",
      "shadow-lg",
      "hover:shadow-xl",
      "transition-shadow",
      "flex",
      "flex-col",
      "items-center",
      "opacity-0", // Start hidden
      "transform",
      "translate-y-10", // Start off-screen
      "transition-opacity",
      "transition-transform",
      "duration-700" // Transition styles
    );
    card.innerHTML = `
      <img src="${meal.strMealThumb}" alt="${meal.strMeal}" class="w-full h-48 object-cover rounded-md mb-4">
      <h2 class="text-xl font-bold mb-2 text-gray-800 text-center">${meal.strMeal}</h2>
      <button class="bg-[#178a0e] text-white py-2 px-4 rounded hover:bg-green-700" onclick="openRecipeDetails('${meal.idMeal}')">View Recipe</button>
    `;

    // Add the card to the container
    recipesContainer.appendChild(card);

    // Trigger the fade-in effect with a delay
    setTimeout(() => {
      card.classList.remove("opacity-0", "translate-y-10");
      card.classList.add("opacity-100", "translate-y-0");
    }, index * 100); // Stagger the animation by 100ms per card
  });
}

// Function to open recipe details
function openRecipeDetails(id, enable_remove = false) {
  fetch(`https://www.themealdb.com/api/json/v1/1/lookup.php?i=${id}`)
    .then((response) => response.json())
    .then((data) => {
      displayRecipeDetails(data.meals[0]);
      document
        .getElementById("recipe-details-popup")
        .classList.remove("hidden");
    })
    .catch((error) => console.error("Error fetching recipe details:", error));
}

// Function to display recipe details in popup
function displayRecipeDetails(recipe, enableDislike = false) {
  let ingredients = "";
  for (let i = 1; i <= 20; i++) {
    const ingredient = recipe[`strIngredient${i}`];
    const measure = recipe[`strMeasure${i}`];
    if (ingredient) {
      ingredients += `<li>${measure} ${ingredient}</li>`;
    }
  }

  document.getElementById("recipe-details").innerHTML = `
    <h2 class="text-2xl font-bold mb-4">${recipe.strMeal}</h2>
    <img src="${recipe.strMealThumb}" alt="${recipe.strMeal}" class="w-full h-64 object-cover rounded-md mb-4">
    <p class="text-lg font-semibold">Category: ${recipe.strCategory}</p>
    <p class="text-lg font-semibold">Cuisine: ${recipe.strArea}</p>
    <h3 class="text-xl font-bold mt-4">Ingredients:</h3>
    <ul class="list-disc pl-5">${ingredients}</ul>
    <h3 class="text-xl font-bold mt-4">Instructions:</h3>
    <p>${recipe.strInstructions}</p>
  `;

  const recipeVideo = document.getElementById("recipe-video");
  if (recipe.strYoutube) {
    recipeVideo.innerHTML = `
      <h3 class="text-xl font-bold mt-4">Recipe Video:</h3>
      <iframe width="100%" height="315" src="https://www.youtube.com/embed/${
        recipe.strYoutube.split("v=")[1]
      }" frameborder="0" allowfullscreen></iframe>
    `;
  } else {
    recipeVideo.innerHTML = "";
  }
}

// Function to fetch recipe suggestions
async function getRecipesSuggestions(query) {
  try {
    const response = await fetch(
      `https://www.themealdb.com/api/json/v1/1/search.php?s=${query}`
    );
    const data = await response.json();
    return data.meals || [];
  } catch (error) {
    console.error("Error fetching recipe suggestions:", error);
    return [];
  }
}

// Function to display suggestions
function displaySuggestions(recipes) {
  const suggestionBox = document.getElementById("suggestion-box");
  if (recipes.length > 0) {
    suggestionBox.innerHTML = recipes
      .map(
        (meal) => `
      <div class="suggestion-item p-2 border-b border-gray-200 cursor-pointer" onclick="getRecipeDetails('${meal.idMeal}')">
        <img src="${meal.strMealThumb}" alt="${meal.strMeal}" class="w-16 h-16 object-cover inline-block mr-2">
        <span>${meal.strMeal}</span>
      </div>
    `
      )
      .join("");
    suggestionBox.classList.remove("hidden");
  } else {
    suggestionBox.classList.add("hidden");
  }
}

// Function to get detailed recipe information based on id
function getRecipeDetails(id) {
  fetch(`https://www.themealdb.com/api/json/v1/1/lookup.php?i=${id}`)
    .then((response) => response.json())
    .then((data) => {
      displayRecipeDetails(data.meals[0]);
      document
        .getElementById("recipe-details-popup")
        .classList.remove("hidden");
    })
    .catch((error) => console.error("Error fetching recipe details:", error));
}
