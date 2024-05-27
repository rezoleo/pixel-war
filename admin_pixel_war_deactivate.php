<?php
session_start();

// Vérifier si l'utilisateur est authentifié
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: admin_login.html");
    exit();
}

// Read the data from the file
$file = 'private/is_pixel_war_active.txt';
$data = file_get_contents($file);

// Invert the data
$data = trim($data);
if ($data === 'true') {
    $data = 'false';
} else {
    $data = 'true';
}

// Write the inverted data back to the file
file_put_contents($file, $data);

header("Location: admin_control.php");
exit();