<?php
include './connect.php';
session_start(); // Start the session to access user ID

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

try {
    // Verwerking van formulier indien het is ingediend
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Recept Titel en Afbeelding
        $title = trim($_POST['title']);
        $imagePath = '';

        // Afbeelding uploaden
        if (!empty($_FILES['image']['name'])) {
            $targetDir = 'uploads/';
            $targetFile = $targetDir . basename($_FILES['image']['name']);

            // Controleer of er fouten zijn bij het uploaden
            if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
                throw new Exception("Upload error: " . $_FILES['image']['error']);
            }

            // Bestandsypecontrole
            $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

            if (!in_array($fileType, $allowedTypes)) {
                throw new Exception("Ongeldig bestandstype: " . $fileType);
            }

            // Verplaats het bestand naar de doelmap
            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                $imagePath = $targetFile;
            } else {
                throw new Exception("Fout bij het uploaden van de afbeelding.");
            }
        }

        // Voeg het recept toe aan de database, inclusief user_id
        $userId = $_SESSION['user_id']; // Fetch the user ID from session
        $stmt = $conn->prepare("INSERT INTO recipes (title, image_path, user_id) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $title, $imagePath, $userId);
        if (!$stmt->execute()) {
            throw new Exception("Fout bij het toevoegen van het recept: " . $stmt->error);
        }
        $recipeId = $stmt->insert_id;

        // Ingrediënten opslaan
        if (isset($_POST['ingredient_name'])) {
            $ingredientNames = $_POST['ingredient_name'];
            $ingredientAmounts = $_POST['ingredient_amount'];
            $ingredientUnits = $_POST['ingredient_unit'];

            for ($i = 0; $i < count($ingredientNames); $i++) {
                $name = trim($ingredientNames[$i]);
                $amount = floatval($ingredientAmounts[$i]);
                $unit = trim($ingredientUnits[$i]);

                $stmt = $conn->prepare("INSERT INTO ingredients (recipe_id, name, amount, unit) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("isds", $recipeId, $name, $amount, $unit);
                if (!$stmt->execute()) {
                    throw new Exception("Fout bij het toevoegen van de ingrediënten: " . $stmt->error);
                }
            }
        }
    }

    // Verwijder een recept of ingrediënt als dat gevraagd is
    if (isset($_GET['delete_recipe'])) {
        $recipeId = intval($_GET['delete_recipe']);
        $stmt = $conn->prepare("DELETE FROM recipes WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ii", $recipeId, $userId); // Ensure only the user can delete their recipe
        if (!$stmt->execute()) {
            throw new Exception("Fout bij het verwijderen van het recept: " . $stmt->error);
        }
    } elseif (isset($_GET['delete_ingredient'])) {
        $ingredientId = intval($_GET['delete_ingredient']);
        $stmt = $conn->prepare("DELETE FROM ingredients WHERE id = ?");
        $stmt->bind_param("i", $ingredientId);
        if (!$stmt->execute()) {
            throw new Exception("Fout bij het verwijderen van het ingrediënt: " . $stmt->error);
        }
    }

    // Haal recepten en ingrediënten op om weer te geven
    $recipes = $conn->query("SELECT * FROM recipes WHERE user_id = " . intval($userId));
} catch (Exception $e) {
    // Handle errors appropriately (e.g., log them, redirect, etc.)
    // You can log the error instead of echoing it:
    error_log("Er is een fout opgetreden: " . $e->getMessage());
}
