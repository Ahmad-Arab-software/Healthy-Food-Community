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

function displaySuggestions(recipes) {
    const suggestionBox = document.getElementById("suggestion-box");
    if (recipes.length > 0) {
        suggestionBox.innerHTML = "";
        recipes
            .map(
                (meal) => {
                    const item = document.createElement("div");
                    const classes = 'suggestion-item p-2 border-b border-gray-200 cursor-pointer'.split(' ')
                    classes.map((c) => item.classList.add(c))

                    const img = document.createElement("img");
                    const imgClasses = 'w-16 h-16 object-cover inline-block mr-2'.split(' ')
                    img.src = meal.strMealThumb
                    imgClasses.map((c) => img.classList.add(c))
                    item.appendChild(img)

                    const span = document.createElement("span");
                    span.innerHTML = meal.strMeal
                    item.appendChild(span)
                    item.addEventListener('click', () => displayRecipeDetails(meal))
                    suggestionBox.appendChild(item)
                }
    )
    .join("");
        suggestionBox.classList.remove("hidden");
    } else {
        suggestionBox.classList.add("hidden");
    }
}


async function likeRecipe(mealId) {
    const currentLocation = window.location.pathname
    console.log(window.location)
    // Create a URL-encoded string
    const url = currentLocation.includes('/php/') ? './like_recipe.php' : './php/like_recipe.php';
    const params = new URLSearchParams();
    params.append('meal_id', mealId);

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

        const data = await response.json(); // Assume the response is JSON
        console.log('Success:', data);
        alert('Recipe liked successfully.');
    } catch (error) {
        console.error('Error:', error);
    }
}

async function dislikeRecipe(mealId) {
    const currentLocation = window.location.pathname
    console.log(window.location)
    // Create a URL-encoded string
    const url = currentLocation.includes('/php/') ? './remove_favorite.php' : './php/remove_favorite.php';
    const params = new URLSearchParams();
    params.append('meal_id', mealId);

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

        location.reload()
    } catch (error) {
        console.error('Error:', error);
    }
}

function displayRecipeDetails(recipe, enableDislike = false) {
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
    const instructions = recipe.strInstructions ?? "";
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

    document
        .getElementById("recipe-details-popup")
        .classList.remove("hidden");

    enablePopupButtons(recipe)
}

searchInput.addEventListener("input", () => {
    const query = searchInput.value;
    console.log(query)
    if (query) {
        getRecipesSuggestions(query).then((recipes) => {
            displaySuggestions(recipes);
        });
    } else {
        suggestionBox.classList.add("hidden");
    }
});

function enablePopupButtons(recipe) {
    const likeBtn = document.getElementById("like-recipe-btn");
    likeBtn?.addEventListener("click", () => likeRecipe(recipe.idMeal));

    const dislikeBtn = document.getElementById("dislike-recipe-btn");
    dislikeBtn?.addEventListener('click', () => dislikeRecipe(recipe.idMeal))

    const closeRecipePopup = document.getElementById("close-recipe-popup");
    const recipeContainer = document.getElementById("featured-recipes");
    const recipeDetailsSection = document.getElementById("recipe-details-popup");
    closeRecipePopup.addEventListener("click", () => {
        recipeDetailsSection?.classList.add("hidden");
        recipeContainer?.classList.remove("hidden");
        document.body.classList.remove('overflow-hidden');
    });
}

function openRecipeDetails(id, enableDislike = false) {
    fetch(`https://www.themealdb.com/api/json/v1/1/lookup.php?i=${id}`)
        .then((response) => response.json())
        .then((data) => {
            const recipe = data.meals[0]
            displayRecipeDetails(recipe, enableDislike);
        })
        .catch((error) => console.error("Error fetching recipe details:", error));
}