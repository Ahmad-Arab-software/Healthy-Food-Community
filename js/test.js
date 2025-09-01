// Handle collapse menu toggle
function handleClick() {
    const collapseMenu = document.getElementById("collapseMenu");
    if (collapseMenu.style.display === "block") {
        collapseMenu.style.display = "none";
    } else {
        collapseMenu.style.display = "block";
    }
}

async function likeRecipe(id_meal) {
    const url = './php/like_recipe.php'; // Vervang dit door je API-eindpunt

    // Maak een URL-gecodeerde string
    const params = new URLSearchParams();
    params.append('meal_id', id_meal);

    try {
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: params.toString(),
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json(); // Veronderstel dat de respons JSON is
        console.log('Success:', data);
        alert('Recipe liked successfully.');
    } catch (error) {
        console.error('Error:', error);
    }
}


function displayRecipeDetails(recipe) {
    document.body.classList.add("overflow-hidden");
    let ingredients = "";
    for (let i = 1; i <= 20; i++) {
        const ingredient = recipe[`strIngredient${i}`];
        const measure = recipe[`strMeasure${i}`];
        if (ingredient) {
            ingredients += `<p class="m-0 flex gap-1 items-center"><span class="block rounded-[100%] bg-black border-black w-2 h-2"></span> ${measure} ${ingredient}</p>`;
        }
    }

    // Limit the instructions to the first 100 characters
    const instructionsLimit = 100;
    const instructions = recipe.strInstructions;
    const isLongInstructions = instructions.length > instructionsLimit;
    const shortInstructions = isLongInstructions ? instructions.slice(0, instructionsLimit) + '...' : instructions;

    document.getElementById("recipe-details").innerHTML = `
    <h2 class="text-2xl font-bold mt-4">${recipe.strMeal}</h2>
    <img src="${recipe.strMealThumb}" alt="${recipe.strMeal}" class="w-full h-64 object-cover rounded-md mb-4">
    <p class="text-lg font-semibold">Category: ${recipe.strCategory}</p>
    <p class="text-lg font-semibold">Cuisine: ${recipe.strArea}</p>
    <h3 class="text-xl font-bold mt-4">Ingredients:</h3>
    <div class="w-full grid grid-cols-2 lg:grid-cols-4 gap-2">${ingredients}</div>
    <h3 class="text-xl font-bold mt-4">Instructions:</h3>
    <p id="recipe-instructions">${shortInstructions}</p>
    ${isLongInstructions ? `<button id="toggle-instructions" class="mt-2 text-[#178a0e]">Read More</button>` : ''}
  `;

    const likeBtn = document.getElementById("like-recipe-btn");
    likeBtn.addEventListener("click", () => likeRecipe(recipe.idMeal));

    // Handle Read More / Read Less button click
    if (isLongInstructions) {
        document.getElementById("toggle-instructions").addEventListener("click", function() {
            const instructionsElement = document.getElementById("recipe-instructions");
            if (instructionsElement.innerHTML === shortInstructions) {
                instructionsElement.innerHTML = instructions; // Show full instructions
                this.innerHTML = 'Read Less'; // Change button text to Read Less
            } else {
                instructionsElement.innerHTML = shortInstructions; // Show short instructions
                this.innerHTML = 'Read More'; // Change button text back to Read More
            }
        });
    }

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


function openRecipeDetails(id) {
    fetch(`https://www.themealdb.com/api/json/v1/1/lookup.php?i=${id}`)
        .then((response) => response.json())
        .then((data) => {
            displayRecipeDetails(data.meals[0]);
            document
                .getElementById("recipe-details-popup")
                .classList.remove("hidden");
            const likeBtn = document.getElementById("like-recipe-btn");
            likeBtn.addEventListener("click", () => likeRecipe(recipe.idMeal));
        })
        .catch((error) => console.error("Error fetching recipe details:", error));
}

document.addEventListener("DOMContentLoaded", () => {
    const searchBtn = document.getElementById("search-btn");
    const searchInput = document.getElementById("search");
    const suggestions = document.getElementById("suggestion-box");
    const recipeContainer = document.getElementById("featured-recipes");
    const recipeDetailsSection = document.getElementById("recipe-details-popup");
    const recipeDetails = document.getElementById("recipe-details");
    const recipeVideo = document.getElementById("recipe-video");
    const closeRecipePopup = document.getElementById("close-recipe-popup");
    const toggleOpen = document.getElementById("toggleOpen");
    const toggleClose = document.getElementById("toggleClose");

    // Event listeners
    if (searchBtn) {
        searchBtn.addEventListener("click", () => {
            const query = searchInput.value;
            if (query) {
                getRecipes(query);
            }
        });
    }

    searchInput.addEventListener("input", async () => {
        const query = searchInput.value;
        if (query.length > 2) {
            const recipes = await getRecipesSuggestions(query);
            displaySuggestions(recipes);
        } else {
            suggestions.innerHTML = "";
            suggestions.classList.add("hidden");
        }
    });

    closeRecipePopup?.addEventListener("click", () => {
        recipeDetailsSection.classList.add("hidden");
        recipeContainer.classList.remove("hidden");
        document.body.classList.remove('overflow-hidden');
    });

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

    // Function to display recipe suggestions
    function displaySuggestions(suggestionsData) {
        suggestions.innerHTML = "";
        if (suggestionsData) {
            suggestionsData.forEach((suggestion) => {
                const suggestionItem = document.createElement("div");
                suggestionItem.classList.add(
                    "p-2",
                    "flex",
                    "items-center",
                    "cursor-pointer",
                    "hover:bg-gray-100"
                );
                suggestionItem.innerHTML = `
          <img src="${suggestion.strMealThumb}" alt="${suggestion.strMeal}" class="w-16 h-16 object-cover rounded-md mr-2">
          <span>${suggestion.strMeal}</span>
        `;

                // On click, show recipe details immediately
                suggestionItem.addEventListener("click", () => {
                    searchInput.value = suggestion.strMeal;
                    getRecipeDetails(suggestion.idMeal);
                    suggestions.innerHTML = "";
                    suggestions.classList.add("hidden");
                });

                suggestions.appendChild(suggestionItem);
            });

            suggestions.classList.remove("hidden");

            // Click outside to hide suggestions
            document.addEventListener("click", (event) => {
                if (
                    !suggestions.contains(event.target) &&
                    !searchInput.contains(event.target)
                ) {
                    suggestions.innerHTML = "";
                    suggestions.classList.add("hidden");
                }
            });
        } else {
            suggestions.classList.add("hidden");
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

    // Attach the function to the global window object
    window.getRecipeDetails = async function (id) {
        try {
            const response = await fetch(
                `https://www.themealdb.com/api/json/v1/1/lookup.php?i=${id}`
            );
            const data = await response.json();
            displayRecipeDetails(data.meals[0]);
        } catch (error) {
            console.error("Error fetching recipe details:", error);
        }
    };
    function getRecipeDetails(mealId) {
        // Fetch recipe details via API or other means
        const apiUrl = `https://www.themealdb.com/api/json/v1/1/lookup.php?i=${mealId}`;

        fetch(apiUrl)
            .then(response => response.json())
            .then(data => {
                const meal = data.meals[0];
                document.getElementById("recipe-details").innerHTML = `
                <h2 class="text-2xl font-bold">${meal.strMeal}</h2>
                <img src="${meal.strMealThumb}" alt="${meal.strMeal}" class="mb-4 rounded">
                <p>${meal.strInstructions}</p>
            `;
                document.getElementById("meal_id").value = meal.idMeal; // Set the meal ID for removal
                document.getElementById("recipe-details-popup").classList.remove("hidden");
            })
            .catch(error => console.error('Error fetching recipe details:', error));
    }

    function likeRecipe(recipeId) {
        const urlEncodedData = new URLSearchParams({
            meal_id: recipeId,
        } ).toString();

        fetch('php/like_recipe.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: urlEncodedData// Stuur het recept-ID naar de server
        })
            .then(response => response.text())
            .then(data => {
                console.log(data); // Feedback aan de gebruiker
                alert('Recept geliked!'); // Eenvoudige feedback
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Er is iets misgegaan. Probeer het opnieuw.');
            });
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


