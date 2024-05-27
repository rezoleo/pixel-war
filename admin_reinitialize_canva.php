<?php
session_start();

// Vérifier si l'utilisateur est authentifié
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: admin_login.html");
    exit();
}

// Check if line and column parameters are set
if (isset($_POST['line']) && isset($_POST['column'])) {

    $height = filter_var($_POST['line'], FILTER_VALIDATE_INT);
    $width = filter_var($_POST['column'], FILTER_VALIDATE_INT);

    // Check if the values are valid integers and greater than zero
    if ($height === false || $width === false || $height <= 0 || $width <= 0) {
        header("Location: admin_control.php?error_reinitialize=true");
        exit();
    }

    // *** part about saving the current state of the canvas *** //
    $directory = 'private/';
    $baseFilename = 'pixels_save_';
    $extension = '.bin';

    // Initialize the counter to 1
    $counter = 1;

    // Construct the initial filename
    $filename = $directory . $baseFilename . $counter . $extension;

    // Loop to find the next available filename
    while (file_exists($filename)) {
        $counter++;
        $filename = $directory . $baseFilename . $counter . $extension;
    }

    // Copy the main file to the new backup filename
    if (file_exists($directory . 'pixels.bin')) {
        copy($directory . 'pixels.bin', $filename);
    } else {
        header("Location: admin_control.php?error_reinitialize=true");
        exit();
    }


    // *** part about reinitializing the canva *** //

    // Calculate the number of bytes needed
    $bytes = (int) ($width * $height / 2);

    // Open the file for writing
    $file = fopen($directory . 'pixels.bin', 'wb');
    if ($file === false) {
        header("Location: admin_control.php?error_reinitialize=true");
        exit();
    }

    $bytesString = str_repeat(pack('C', 0), $bytes);

    fwrite($file, $bytesString);

    // Close the file
    fclose($file);

    // Update the height and width values in the taille.txt file
    $file = fopen($directory . 'taille.txt', 'w');
    if ($file === false) {
        header("Location: admin_control.php?error_reinitialize=true");
        exit();
    }

    $data = $width . ',' . $height;

    fwrite($file, $data);

    // Close the file
    fclose($file);

    header("Location: admin_control.php");
    exit();
} else {
    header("Location: admin_control.php?error_reinitialize=true");
    exit();
}
