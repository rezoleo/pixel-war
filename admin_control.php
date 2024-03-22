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
                    <p>Voici la page de contrôle</p>
                </div>
            </div>
        </nav>
    </header>
    <div class="pixel_war_body_admin">
        <canvas id="pixel_war"></canvas>
        <div class="admin_writing_pixels">
            <!-- enlever le form pour remplacer par une pixel war clickable pour drag et faire des rectangles blancs -->
            <form class="form_white_square" id="cleanPixelWarForm" action="pixel_whitening.php" method="post">
                <input type="text" name="position_square" placeholder="(?,?)">
                <input type="number" name="square_size">
                <button id="uploadModificationButton">Upload Modification</button>
            </form>
        </div>
        <div>
            <form class="form" action="augment_pixel_war_size.php" method="post">
                <input type="number" name="new_line" placeholder="New line size">
                <button id="uploadModificationButton">Augment Pixel War Size</button>
            </form>
        </div>
    </div>
    <script src="scripts/refresh_admin.js"></script>
</body>
</html>
