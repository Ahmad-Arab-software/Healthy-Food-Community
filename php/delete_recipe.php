<?php
include './connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $recipe_id = $_POST['recipe_id'];

        // Verwijder eerst de ingrediënten die bij dit recept horen
        $stmt = $conn->prepare("DELETE FROM ingredients WHERE recipe_id = ?");
        $stmt->bind_param("i", $recipe_id);
        $stmt->execute();

        $stmt = $conn->prepare("SELECT image_path FROM recipes WHERE id = ?");
        $stmt->bind_param("i", $recipe_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $recipe = $result->fetch_assoc();
            $image_path = $recipe['image_path'];
            if ($image_path && !unlink($image_path)) {
                echo "Error deleting file";
            }
            // You can now use $image_path as needed
        } else {
            // Handle case where no recipe is found
            echo "No recipe found with this ID.";
            exit();
        }



        // Verwijder vervolgens het recept zelf
        $stmt = $conn->prepare("DELETE FROM recipes WHERE id = ?");
        $stmt->bind_param("i", $recipe_id);
        $stmt->execute();

        $stmt->close();

        header('Location: ./my_recipes.php?deleted=1');
        exit();
    } catch (exception $e) {
        echo $e->getMessage();
    }
}
?>
