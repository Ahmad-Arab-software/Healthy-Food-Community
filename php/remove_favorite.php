<?php
session_start();
include("./connect.php");

// Controleer of de gebruiker is ingelogd
if (!isset($_SESSION['user_id'])) {
    header("Location: ./index.php");
    exit();
}

$user_id = $_SESSION['user_id']; // Haal de user_id van de ingelogde gebruiker op

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Haal het meal_id op van de POST-data
    $meal_id = $_POST['meal_id'];

    // Verwijder het recept uit de likes tabel
    $query = "DELETE FROM likes WHERE user_id = ? AND id_meal = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $user_id, $meal_id);

    if ($stmt->execute()) {
        // Redirect terug naar de favoriete recepten pagina
        exit();
    } else {
        // Foutafhandeling
        echo "Error removing recipe.";
    }
}
?>
