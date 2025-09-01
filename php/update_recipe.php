<?php
session_start();
include './connect.php'; // Zorg ervoor dat de databaseverbinding is ingesteld

// Controleer of de gebruiker is ingelogd
if (!isset($_SESSION['user_id'])) {
    echo "Je moet ingelogd zijn om recepten bij te werken.";
    exit();
}

// Gegevens uit het formulier ophalen
try {
    $recipe_id = intval($_POST['recipe_id']);
    $title = $_POST['title'];
    $description = $_POST['description'];
    $user_id = $_SESSION['user_id']; // Haal de user_id uit de sessie

// SQL-update query
    $stmt = $conn->prepare("UPDATE recipes SET title = ?, description = ? WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ssii", $title, $description, $recipe_id, $user_id);
    $stmt->execute();

    $stmt = $conn->prepare("DELETE FROM ingredients WHERE recipe_id = ?");
    $stmt->bind_param("i", $recipe_id);
    $stmt->execute();

// Ingrediënten verwerken
    $ingredient_names = $_POST['ingredient_name'];
    $quantities = $_POST['quantity'];

    foreach ($ingredient_names as $index => $ingredient_name) {
        $ingredient_name = mysqli_real_escape_string($conn, $ingredient_name);
        $quantity = mysqli_real_escape_string($conn, $quantities[$index]);
        $insert_ingredient = "INSERT INTO ingredients (recipe_id, ingredient_name, quantity) VALUES ('$recipe_id', '$ingredient_name', '$quantity')";
        $stmt =  $conn->prepare($insert_ingredient);
        $stmt->execute();
    }

    echo "Recept succesvol bijgewerkt!";
    header("Location: ./my_recipes.php");
    exit();
} catch (Exception $e) {
    echo $e->getMessage();
}
