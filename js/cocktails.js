async function getFeaturedItems() {
  try {
    // Fetch 1 cocktail
    const cocktailsResponse = await fetch(
      "https://www.thecocktaildb.com/api/json/v1/1/random.php"
    );
    const cocktailsData = await cocktailsResponse.json();
    const featuredCocktail = [cocktailsData.drinks[0]]; // Get 1 random cocktail

    // Display cocktail
    displayFeaturedItems(featuredCocktail);
  } catch (error) {
    console.error("Error fetching featured items:", error);
  }
}
function displayFeaturedItems(cocktail) {
  const cocktailContainer = document.getElementById("cocktail-container");

  // Clear previous content
  cocktailContainer.innerHTML = "";

  // Display cocktail
  cocktail.forEach((cocktail) => {
    const cocktailCard = document.createElement("div");
    cocktailCard.innerHTML = `
      <h2>${cocktail.strDrink}</h2>
      <img src="${cocktail.strDrinkThumb}" alt="${cocktail.strDrink}">
      <button onclick="getCocktailDetails('${cocktail.idDrink}')">View Cocktail</button>
    `;
    cocktailContainer.appendChild(cocktailCard);
  });
}
