function addIngredient() {
    const ingredientDiv = document.createElement('div');
    ingredientDiv.innerHTML = `
      <input type="text" name="ingredient_name[]" placeholder="Ingredient Name" required>
      <input type="number" name="ingredient_amount[]" placeholder="Amount" required>
      <input type="text" name="ingredient_unit[]" placeholder="Unit" required>
      <button type="button" onclick="this.parentElement.remove()">🗑️</button>
    `;
    document.getElementById('ingredients-list').appendChild(ingredientDiv);
}