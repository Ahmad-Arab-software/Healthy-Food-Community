<?php
include('./connect.php');
session_start(); // Start de sessie om toegang te hebben tot de ingelogde gebruiker

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check of de gebruiker is ingelogd
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(["status" => "error", "message" => "Je moet ingelogd zijn om een recept te liken."]);
        exit; // Stop verdere uitvoering
    }

    // Controleer of het recipe_id is meegestuurd via een POST-verzoek
    if (isset($_POST['meal_id'])) {
        try {
            $meal_id = $_POST['meal_id'];
            $user_id = $_SESSION['user_id']; // Haal de gebruiker ID uit de sessie

            // Controleer of de gebruiker het recept al geliked heeft
            $stmt = $conn->prepare("SELECT * FROM likes WHERE user_id = ? AND id_meal = ?");
            $stmt->bind_param("is", $user_id, $meal_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows == 0) {
                // Recept is nog niet geliked, voeg een nieuwe like toe
                $insert_stmt = $conn->prepare("INSERT INTO likes (user_id, id_meal) VALUES (?, ?)");
                $insert_stmt->bind_param("is", $user_id, $meal_id);

                if ($insert_stmt->execute()) {
                    echo json_encode(["status" => "success", "message" => "Recept geliked!"]);
                } else {
                    echo json_encode(["status" => "error", "message" => "Er is iets misgegaan bij het liken van het recept."]);
                }

                $insert_stmt->close();
            } else {
                // Recept is al geliked door de gebruiker
                echo json_encode(["status" => "info", "message" => "Je hebt dit recept al geliked."]);
            }

            $stmt->close();
        } catch (Exception $e) {
            echo json_encode(["status" => "error", "message" => $e->getMessage()]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Geen recept geselecteerd om te liken."]);
    }

    $conn->close();
}
?>
