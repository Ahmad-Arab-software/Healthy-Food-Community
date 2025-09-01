<?php
session_start();
require_once './connect.php'; // Zorg dat deze connectie werkt
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Admin login constant
$admin_email = 'admin@admin.com';

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // === HANDLE LOGIN ===
    if (isset($_POST['signIn'])) {
        $email = trim($_POST['email']);
        $password = $_POST['password'];

        if (empty($email) || empty($password)) {
            $_SESSION['error'] = "Email en wachtwoord zijn verplicht.";
            header("Location: ../home.php");
            exit();
        }

        // Zoek gebruiker op email
        $stmt = $conn->prepare("SELECT id, email, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                if ($email === $admin_email) {
                    $_SESSION['admin_logged_in'] = true;
                    $_SESSION['email'] = $email;
                    header("Location: admin.php");
                } else {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['email'] = $user['email'];
                    header("Location: ../home.php");
                }
                exit();
            }
        }

        // Fallback bij fout
        $_SESSION['error'] = "Ongeldige inloggegevens.";
        header("Location: ../home.php");
        exit();
    }

    // === HANDLE REGISTRATIE ===
    if (isset($_POST['signUp'])) {
        $firstName = trim($_POST['fName']);
        $lastName = trim($_POST['lName']);
        $email = trim($_POST['email']);
        $rawPassword = $_POST['password'];

        // Basis validatie
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = "Ongeldig e-mailadres.";
            header("Location: ../home.php");
            exit();
        }

        if (strlen($rawPassword) < 8) {
            $_SESSION['error'] = "Wachtwoord moet minimaal 8 tekens bevatten.";
            header("Location: ../home.php");
            exit();
        }

        // Bestaat het emailadres al?
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            $hashedPassword = password_hash($rawPassword, PASSWORD_BCRYPT);
            $stmt = $conn->prepare(
                "INSERT INTO users (firstName, lastName, email, password) VALUES (?, ?, ?, ?)"
            );
            $stmt->bind_param("ssss", $firstName, $lastName, $email, $hashedPassword);

            if ($stmt->execute()) {
                header("Location: ../index.php?success=registered");
                exit();
            } else {
                $_SESSION['error'] = "Registratie mislukt. Probeer het opnieuw.";
                header("Location: ../home.php");
                exit();
            }
        } else {
            $_SESSION['error'] = "E-mailadres is al in gebruik.";
            header("Location: ../home.php");
            exit();
        }
    }
}

// === Toon foutmeldingen indien aanwezig ===
if (isset($_SESSION['error'])) {
    echo '<div class="error">' . htmlspecialchars($_SESSION['error']) . '</div>';
    unset($_SESSION['error']);
}
