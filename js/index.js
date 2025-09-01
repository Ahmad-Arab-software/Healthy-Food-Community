document.addEventListener("DOMContentLoaded", function() {
  // Toggle voor wachtwoord zichtbaarheid (voor register en login)
  const togglePassword = document.getElementById('togglePassword');
  const passwordField = document.getElementById('password');

  const togglePasswordLogin = document.getElementById('togglePasswordLogin');
  const passwordFieldLogin = document.getElementById('passwordLogin');

  togglePassword.addEventListener('click', function() {
    const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordField.setAttribute('type', type);
    this.classList.toggle('fa-eye-slash'); // Wisselt tussen oog en oog met streep
  });

  togglePasswordLogin.addEventListener('click', function() {
    const type = passwordFieldLogin.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordFieldLogin.setAttribute('type', type);
    this.classList.toggle('fa-eye-slash');
  });

  // Toggelen tussen registratie en login formulier
  const signUpButton = document.getElementById('signUpButton');
  const signInButton = document.getElementById('signInButton');

  const signUpForm = document.getElementById('signUp');
  const signInForm = document.getElementById('signIn');

  signUpButton.addEventListener('click', function() {
    signInForm.classList.add('hidden');
    signUpForm.classList.remove('hidden');
  });

  signInButton.addEventListener('click', function() {
    signUpForm.classList.add('hidden');
    signInForm.classList.remove('hidden');
  });
});



// Function to add ingredients (not used in the forms, but included for completeness)
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
