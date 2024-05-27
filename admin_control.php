<?php
session_start();

// Vérifier si l'utilisateur est authentifié
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: admin_login.html");
    exit();
}

$filename = 'private/taille.txt';
$fileContent = file_get_contents($filename);
list($width, $height) = explode(',', $fileContent);
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
        <div class="error-size">
            <p><?php
            if (isset($_GET['error'])) {
                echo "Wrong size";
            }
            ?></p>
        </div>
        <div>
            <form class="form_admin" action="augment_pixel_war_size.php" method="post">
                <div class="current_pixel_war_size">
                    <p>Current size: <?php echo $height; ?> x <?php echo $width; ?> : Put even numbers only</p>
                </div>
                <div class="numbers_input">
                    <input type="number" name="new_line" placeholder="New line size" step="1" min="1">
                    <input type="number" name="new_column" placeholder="New column size" step="1" min="1">
                </div>
                <button id="uploadModificationButton">Augment Pixel War Size</button>
            </form>
        </div>
        <form class="changing-pixel-war-state" action="admin_pixel_war_deactivate.php">
            <button id="changePixelWarStateButton">Deactivate Pixel War</button>
        </form>
    </div>
    <script src="scripts/refresh_admin.js"></script>
    <script src="scripts/disable_pixel_war_admin.js"></script>
</body>
</html>
