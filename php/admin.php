<?php
session_start();
include './connect.php'; // Zorg ervoor dat dit de juiste databaseconnectie bevat

// Controleer of de admin is ingelogd
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: ./index.php');
    exit();
}

// Haal gebruikersgegevens op
$result = $conn->query("SELECT * FROM users"); // Verander 'users' naar je tabelnaam

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['delete'])) {
        // Verwijder gebruiker
        $id = $_POST['id'];
        $conn->query("DELETE FROM users WHERE id = $id");
    } else {
        // Update gebruiker
        $id = $_POST['id'];
        $email = $_POST['email'];
        $firstName = $_POST['firstName'];
        $lastName = $_POST['lastName'];
        // Zorg ervoor dat je wachtwoord goed afhandelt
        $password = $_POST['password'] ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;

        $updateQuery = "UPDATE users SET email='$email', firstName='$firstName', lastName='$lastName'";
        if ($password) {
            $updateQuery .= ", password='$password'";
        }
        $updateQuery .= " WHERE id=$id";
        $conn->query($updateQuery);
    }
}

// Weergeven van gebruikersgegevens
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cooper+Black&display=swap" rel="stylesheet">
    <link rel="shortcut icon" type="image/x-icon" href="../media/Logo.png" />

    <style>
        body {
            font-family: 'Cooper Black', sans-serif;
            background: linear-gradient(135deg, #213526, #178a0e);
        }
    </style>
    <script>
        function togglePasswordVisibility(passwordFieldId, iconId) {
            const passwordField = document.getElementById(passwordFieldId);
            const icon = document.getElementById(iconId);
            if (passwordField.type === "password") {
                passwordField.type = "text";
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = "password";
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
</head>
<body class="bg-gray-800 text-white">

<div class="container mx-auto my-10 p-5 bg-gray-900 rounded-lg shadow-lg">
    <h1 class="text-3xl text-center mb-6">Admin Dashboard</h1>

    <!-- Logout Button -->
    <div class="text-center mb-6">
        <form action="logout.php" method="post">
            <button type="submit" class="bg-red-500 text-white py-2 px-4 rounded hover:bg-red-600 transition">LOG OUT</button>
        </form>
    </div>

    <table class="min-w-full bg-white text-gray-700">
        <thead>
        <tr class="bg-gray-800 text-white">
            <th class="py-3 px-4">ID</th>
            <th class="py-3 px-4">E-mail</th>
            <th class="py-3 px-4">Voornaam</th>
            <th class="py-3 px-4">Achternaam</th>
            <th class="py-3 px-4">Acties</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr class="hover:bg-gray-200">
                <form method="post">
                    <td class="border px-4 py-2"><?php echo $row['id']; ?></td>
                    <td class="border px-4 py-2">
                        <input type="email" name="email" value="<?php echo $row['email']; ?>" required class="p-2 border rounded w-full">
                    </td>
                    <td class="border px-4 py-2">
                        <input type="text" name="firstName" value="<?php echo $row['firstName']; ?>" required class="p-2 border rounded w-full">
                    </td>
                    <td class="border px-4 py-2">
                        <input type="text" name="lastName" value="<?php echo $row['lastName']; ?>" required class="p-2 border rounded w-full">
                    </td>
                    <td class="border px-4 py-2">
                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                        <div class="flex items-center">
                            <input type="password" id="password-<?php echo $row['id']; ?>" name="password" placeholder="Wachtwoord (optioneel)" class="p-2 border rounded w-full mb-2">
                            <button type="button" class="ml-2" onclick="togglePasswordVisibility('password-<?php echo $row['id']; ?>', 'eye-<?php echo $row['id']; ?>')">
                                <i id="eye-<?php echo $row['id']; ?>" class="fas fa-eye"></i>
                            </button>
                        </div>
                        <button type="submit" class="bg-blue-500 text-white py-1 px-3 rounded hover:bg-blue-600 transition">Bewerken</button>
                        <button type="submit" name="delete" class="bg-red-500 text-white py-1 px-3 rounded hover:bg-red-600 transition">Verwijderen</button>
                    </td>
                </form>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- Include Font Awesome for eye icon -->
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>
</html>
