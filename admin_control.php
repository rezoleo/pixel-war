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
                <a class="lien" href="index.html" id="case_1">
                    <p>Accueil</p>
                </a>
                <a class="lien" href="pixel_war.html" id="case_2">
                    <p>Pixel War</p>
                </a>
                <a class="lien" href="viewing.html" id="case_2">
                    <p>Viewing</p>
                </a>
                <a class="lien" href="admin_login.html" id="case_2">
                    <U><p>Admin</p></U>
                </a>
                <a class="lien logo-link" id="case_logo">
                    <img src="images/rezoleo_logo.png" alt="logo" id="logo" style="width : 80px">
                </a>
            </div>
        </nav>
    </header>
    <div class="pixel_war_body_admin">
        <div class="admin_writing_pixels">
            <div style="height: 300px;">
                <canvas id="pixel_war"></canvas>
            </div>
            <div class="buttons_writing_pixels">
                <button id="refreshButton">Refresh</button>
                <button id="uploadModificationButton">Upload Modification</button>
            </div>
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
