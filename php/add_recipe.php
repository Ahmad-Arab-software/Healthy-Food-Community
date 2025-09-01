<?php
session_start();
include("./connect.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Zorg ervoor dat de gebruiker is ingelogd
        if (!isset($_SESSION['email'])) {
            header('Location: ./index.php');
            exit();
        }

        // Verkrijg gebruikersinformatie
        $email = $_SESSION['email'];
        $query = mysqli_query($conn, "SELECT id FROM users WHERE email='$email'");
        $user = mysqli_fetch_assoc($query);
        $user_id = $user['id'];

        // Receptgegevens ophalen
        $title = mysqli_real_escape_string($conn, $_POST['title']);
        $description = mysqli_real_escape_string($conn, $_POST['description']);

        // Afbeelding uploaden
        $image = $_FILES['image']['name'];
        $target_dir = "./uploads/";
        $target_file = $target_dir . basename($image);
        move_uploaded_file($_FILES['image']['tmp_name'], $target_file);

        // Recept invoegen
        $insert_recipe = "INSERT INTO recipes (title, description, image_path, user_id) VALUES ('$title', '$description', '$target_file', '$user_id')";
        if (mysqli_query($conn, $insert_recipe)) {
            $recipe_id = mysqli_insert_id($conn); // Verkrijg het ID van het nieuw toegevoegde recept

            // Ingrediënten verwerken
            $ingredient_names = $_POST['ingredient_name'];
            $quantities = $_POST['quantity'];
            foreach ($ingredient_names as $index => $ingredient_name) {
                $ingredient_name = mysqli_real_escape_string($conn, $ingredient_name);
                $quantity = mysqli_real_escape_string($conn, $quantities[$index]);
                $insert_ingredient = "INSERT INTO ingredients (recipe_id, ingredient_name, quantity) VALUES ('$recipe_id', '$ingredient_name', '$quantity')";
                mysqli_query($conn, $insert_ingredient);
            }

            // Succesmelding en redirect
            header('Location: ./my_recipes.php');
            exit();
        } else {
            echo "Error: " . mysqli_error($conn);
        }

    } catch (exception $e) {
        echo $e->getMessage();

    }
}
?>
