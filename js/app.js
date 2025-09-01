// Handle collapse menu toggle
function handleClick() {
  const collapseMenu = document.getElementById("collapseMenu");
  if (collapseMenu.style.display === "block") {
    collapseMenu.style.display = "none";
  } else {
    collapseMenu.style.display = "block";
  }
}

document.addEventListener("DOMContentLoaded", () => {
  const recipeContainer = document.getElementById("featured-recipes");
  const toggleOpen = document.getElementById("toggleOpen");
  const toggleClose = document.getElementById("toggleClose");


  toggleOpen.addEventListener("click", handleClick);
  toggleClose.addEventListener("click", handleClick);

  // Fetch and display random recipes on page load
  getRandomRecipes();

  // Function to fetch recipes from TheMealDB API
  async function getRecipes(query) {
    try {
      const response = await fetch(
          `https://www.themealdb.com/api/json/v1/1/search.php?s=${query}`
      );
      const data = await response.json();
      displayRecipes(data.meals);
    } catch (error) {
      console.error("Error fetching recipes:", error);
    }
  }

  // Function to display recipes as cards
  function displayRecipes(recipes) {
    if (!recipeContainer) return;
    recipeContainer.innerHTML = "";
    if (recipes) {
      recipes.forEach((recipe) => {
        const recipeCard = document.createElement("div");
        recipeCard.classList.add(
            "bg-[#eef2e3]",
            "p-6",
            "rounded-lg",
            "shadow-lg",
            "transform",
            "transition-transform",
            "hover:scale-105"
        );
        recipeCard.innerHTML = `
          <img src="${recipe.strMealThumb}" alt="${recipe.strMeal}" class="w-full h-48 object-cover rounded-md mb-4">
          <h2 class="text-xl font-bold text-gray-800">${recipe.strMeal}</h2>
          <button class="mt-4 bg-[#178a0e] text-white py-2 px-4 rounded hover:bg-green-700" onclick="openRecipeDetails('${recipe.idMeal}')">View Recipe</button>
        `;
        recipeContainer.appendChild(recipeCard);
      });
    } else {
      recipeContainer.innerHTML = "<p>No recipes found.</p>";
    }
  }


  // Function to extract YouTube video ID
  function extractYouTubeId(url) {
    const match = url.match(
        /(?:https?:\/\/)?(?:www\.)?youtube\.com\/(?:embed\/|watch\?v=|v\/|.+\/)?([^"&?\/\s]{11})/
    );
    return match ? match[1] : null;
  }

  // Function to get ingredients
  function getIngredients(recipe) {
    let ingredients = [];
    for (let i = 1; i <= 20; i++) {
      if (
          recipe[`strIngredient${i}`] &&
          recipe[`strIngredient${i}`].trim() !== ""
      ) {
        ingredients.push(
            `${recipe[`strIngredient${i}`]} - ${recipe[`strMeasure${i}`]}`
        );
      }
    }
    return ingredients;
  }

  // Function to fetch random recipes
  async function getRandomRecipes() {
    try {
      const response = await fetch(
          "https://www.themealdb.com/api/json/v1/1/search.php?s="
      );
      const data = await response.json();
      if (data.meals) {
        const recipes = data.meals;
        const randomRecipes = getRandomElements(recipes, 3);
        displayRecipes(randomRecipes);
      } else {
        console.error("No recipes found.");
      }
    } catch (error) {
      console.error("Error fetching random recipes:", error);
    }
  }

  // Function to get random elements from an array
  function getRandomElements(array, count) {
    const shuffled = array.sort(() => 0.5 - Math.random());
    return shuffled.slice(0, count);
  }
});
function smoothScroll(target, duration) {
  let targetPosition = target.getBoundingClientRect().top;
  let startPosition = window.pageYOffset;
  let startTime = null;

  function animationScroll(currentTime) {
    if (startTime === null) startTime = currentTime;
    let timeElapsed = currentTime - startTime;
    let run = ease(timeElapsed, startPosition, targetPosition, duration);
    window.scrollTo(0, run);
    if (timeElapsed < duration) requestAnimationFrame(animationScroll);
  }

  // Easing function for smooth animation
  function ease(t, b, c, d) {
    t /= d / 2;
    if (t < 1) return (c / 2) * t * t + b;
    t--;
    return (-c / 2) * (t * (t - 2) - 1) + b;
  }

  requestAnimationFrame(animationScroll);
}

document
    .getElementById("scroll-button")
    ?.addEventListener("click", function () {
      const section = document.getElementById("what-you-can-cook");
      smoothScroll(section, 2000); // 2000ms = 2 seconds for a slow smooth scroll
    });

document.addEventListener("DOMContentLoaded", () => {
  const recipeCards = document.querySelectorAll(".recipe-card");

  const observerOptions = {
    threshold: 0.1, // Wanneer 10% van de kaart zichtbaar is, wordt de animatie geactiveerd
  };

  const observer = new IntersectionObserver((entries, observer) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.classList.add("fade-in-visible");
        observer.unobserve(entry.target); // Stop met observeren zodra de animatie is toegepast
      }
    });
  }, observerOptions);

  recipeCards.forEach((card) => {
    card.classList.add("fade-in"); // Voeg de basis fade-in class toe aan alle kaarten
    observer.observe(card); // Begin met observeren
  });
});