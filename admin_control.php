<?php
session_start();

// Vérifier si l'utilisateur est authentifié
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: admin_login.html");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Control Pixel War</title>
    <link rel="stylesheet" href="stylesheets/main.css">
</head>
<body>
    <header>
        <nav class="menu">
            <div class="menu-links">
                <a class="lien logo-link" id="case_logo">
                    <img src="images/rezoleo_logo.png" alt="logo" id="logo" style="width : 80px">
                </a>
                <a class="lien" href="index.html" id="case_1">
                    <p>Accueil</p>
                </a>
                <a class="lien" href="pixel_war.html" id="case_2">
                    <p>Pixel War</p>
                </a>
                <div class="text-container">
                    <p>Voici la page control</p>
                </div>
            </div>
                </nav>
            </header>
            <div class="main-body">
                <p>This is the main text of my webpage.</p>
            </div>
        </body>
</html>