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
