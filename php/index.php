<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In</title>
    <link href="https://fonts.googleapis.com/css2?family=Cooper+Black&display=swap" rel="stylesheet">
    <script src="../js/index.js" defer></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../css/index.css">
    <link rel="shortcut icon" type="image/x-icon" href="../media/Logo.png" />
    <style>
        body {
            font-family: 'Cooper Black', sans-serif;
            background: linear-gradient(135deg, #213526, #178a0e);
        }
        /* Toast modal styling: rechts onderaan, fixed position */
        #copyToast {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
        }
    </style>
</head>
<body class="h-screen flex items-center justify-center bg-gray-800">

<!-- Register Form -->
<div class="container hidden flex flex-col items-center p-10 bg-white bg-opacity-20 rounded-lg shadow-lg text-center w-112  md:w-128 h-auto" id="signUp">
    <h1 class="text-4xl text-white mb-6">Register</h1>
    <form action="./register.php" method="post" class="w-full">
        <div class="relative mb-4">
            <i class="fas fa-user absolute left-3 top-3 text-gray-500"></i>
            <input type="text" name="fName" id="fName" placeholder="First Name" required autocomplete="given-name" class="w-full pl-10 p-3 bg-white bg-opacity-90 rounded-md focus:outline-none">
        </div>
        <div class="relative mb-4">
            <i class="fas fa-user absolute left-3 top-3 text-gray-500"></i>
            <input type="text" name="lName" id="lName" placeholder="Last Name" required autocomplete="family-name" class="w-full pl-10 p-3 bg-white bg-opacity-90 rounded-md focus:outline-none">
        </div>
        <div class="relative mb-4">
            <i class="fas fa-envelope absolute left-3 top-3 text-gray-500"></i>
            <input type="email" name="email" id="email" placeholder="Email" required autocomplete="email" class="w-full pl-10 p-3 bg-white bg-opacity-90 rounded-md focus:outline-none">
        </div>
        <div class="relative mb-4">
            <i class="fas fa-lock absolute left-3 top-3 text-gray-500"></i>
            <input type="password" name="password" id="password" placeholder="Password" required autocomplete="new-password" class="w-full pl-10 p-3 bg-white bg-opacity-90 rounded-md focus:outline-none">
            <i class="fas fa-eye show-password absolute right-3 top-3 text-gray-500 cursor-pointer" id="togglePassword"></i>
        </div>
        <input type="submit" class="w-full bg-green-700 text-white p-3 rounded-md cursor-pointer hover:bg-green-800 transition" value="Sign Up" name="signUp">
    </form>
    <div class="mt-4 text-white">
        <p>Already have an account?</p>
        <button id="signInButton" class="text-green-500">Sign In</button>
    </div>
</div>

<!-- Login Form -->
<div class="container flex flex-col items-center p-10 bg-white bg-opacity-20 rounded-lg shadow-lg text-center w-112 md:w-128 h-auto" id="signIn">
    <h1 class="text-4xl text-white mb-6">Log In</h1>
    <form action="./register.php" method="post" class="w-full">
        <div class="relative mb-4">
            <i class="fas fa-envelope absolute left-3 top-3 text-gray-500"></i>
            <input type="email" name="email" id="emailLogin" placeholder="Email" required autocomplete="email" class="w-full pl-10 p-3 bg-white bg-opacity-90 rounded-md focus:outline-none">
        </div>
        <div class="relative mb-4">
            <i class="fas fa-lock absolute left-3 top-3 text-gray-500"></i>
            <input type="password" name="password" id="passwordLogin" placeholder="Password" required autocomplete="current-password" class="w-full pl-10 p-3 bg-white bg-opacity-90 rounded-md focus:outline-none">
            <i class="fas fa-eye show-password absolute right-3 top-3 text-gray-500 cursor-pointer" id="togglePasswordLogin"></i>
        </div>
        <input type="submit" class="w-full bg-green-700 text-white p-3 rounded-md cursor-pointer hover:bg-green-800 transition" value="Sign In" name="signIn">
    </form>
    <div class="mt-6 text-white ">
        <p>Don't have an account yet?</p>
        <button id="signUpButton" class="text-green-500">Sign Up</button>
    </div>

    <!-- Login Information Section -->
    <div class="mt-8 text-white w-full">
        <h2 class="text-lg mb-4">Login Information</h2>
        <div class="flex flex-col md:flex-row justify-around items-stretch gap-4">
            <!-- Admin login -->
            <div class="p-4 border border-white border-opacity-50 rounded-md flex flex-col items-center">
                <i class="fas fa-user-shield text-2xl mb-2"></i>
                <h3 class="font-semibold mb-2">Admin login</h3>
                <div class="w-full mb-2 flex items-center justify-between">
                    <p class="text-sm">Gebruikersnaam: <span class="font-normal" id="admin-email">admin@admin.com</span></p>
                    <button
                            type="button"
                            class="copy-btn ml-2 bg-primary/10 hover:bg-primary/20 text-primary p-1.5 rounded-md transition-all"
                            data-copy="admin@admin.com"
                            aria-label="Kopieer admin e-mail"
                            title="Kopieer naar klembord">
                        <i class="fas fa-copy"></i>
                    </button>
                </div>
                <div class="w-full flex items-center justify-between">
                    <p class="text-sm">Wachtwoord: <span class="font-normal" id="admin-password">admin123</span></p>
                    <button
                            type="button"
                            class="copy-btn ml-2 bg-primary/10 hover:bg-primary/20 text-primary p-1.5 rounded-md transition-all"
                            data-copy="admin123"
                            aria-label="Kopieer admin wachtwoord"
                            title="Kopieer naar klembord">
                        <i class="fas fa-copy"></i>
                    </button>
                </div>
            </div>

            <!-- Standaard gebruiker login -->
            <div class="p-4 border border-white border-opacity-50 rounded-md flex flex-col items-center">
                <i class="fas fa-user text-2xl mb-2"></i>
                <h3 class="font-semibold mb-2">Standaard gebruiker login</h3>
                <div class="w-full mb-2 flex items-center justify-between">
                    <p class="text-sm">Gebruikersnaam: <span class="font-normal" id="user-email">Gebruiker@gmail.com</span></p>
                    <button
                            type="button"
                            class="copy-btn ml-2 bg-primary/10 hover:bg-primary/20 text-primary p-1.5 rounded-md transition-all"
                            data-copy="Gebruiker@gmail.com"
                            aria-label="Kopieer gebruiker e-mail"
                            title="Kopieer naar klembord">
                        <i class="fas fa-copy"></i>
                    </button>
                </div>
                <div class="w-full flex items-center justify-between">
                    <p class="text-sm">Wachtwoord: <span class="font-normal" id="user-password">Gebruiker123</span></p>
                    <button
                            type="button"
                            class="copy-btn ml-2 bg-primary/10 hover:bg-primary/20 text-primary p-1.5 rounded-md transition-all"
                            data-copy="Gebruiker123"
                            aria-label="Kopieer gebruiker wachtwoord"
                            title="Kopieer naar klembord">
                        <i class="fas fa-copy"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Toast Modal -->
        <div id="copyToast" class="hidden bg-opacity-80 p-3 rounded shadow flex items-center space-x-2">
            <i class="fas fa-copy text-primary"></i>
            <span id="copyMessage" class="text-xl font-medium text-primary"></span>
        </div>
    </div>
</div>
<script>
    /**
     * Kopieert de meegegeven tekst naar het klembord en toont een toast notificatie.
     * @param {string} text - De tekst die gekopieerd moet worden.
     * @param {string} type - Het type tekst (bijvoorbeeld "E-mailadres" of "Wachtwoord").
     */
    function copyToClipboard(text, type) {
        navigator.clipboard.writeText(text).then(function() {
            // Haal de toast en message elementen op
            const toast = document.getElementById('copyToast');
            const copyMessage = document.getElementById('copyMessage');

            // Stel het bericht in op basis van wat er gekopieerd is
            copyMessage.textContent = `${type} gekopieerd naar klembord!`;

            // Toon de toast
            toast.classList.remove('hidden');

            // Verberg de toast na 2 seconden
            setTimeout(function() {
                toast.classList.add('hidden');
            }, 2000);
        }).catch(function(error) {
            console.error('Kopiëren mislukt:', error);
        });
    }

    // Voeg event listeners toe aan alle knoppen met de class 'copy-btn'
    document.querySelectorAll('.copy-btn').forEach(button => {
        button.addEventListener('click', function() {
            const textToCopy = this.getAttribute('data-copy');
            const isEmail = textToCopy.includes('@');
            const type = isEmail ? 'E-mailadres' : 'Wachtwoord';

            // Visuele feedback op klik
            this.classList.add('bg-primary/30');
            setTimeout(() => {
                this.classList.remove('bg-primary/30');
            }, 300);

            copyToClipboard(textToCopy, type);
        });
    });
</script>
</body>
</html>