<?php

session_start();

// Vérifier si l'utilisateur est authentifié
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: admin_login.html");
    exit();
}

$dimensions = file_get_contents('private/taille.txt');
list($width, $height) = explode(',', $dimensions);

// Calculate the number of bytes needed
if (isset($_POST['new_line']) && isset($_POST['new_column'])) {

    $newWidth = $_POST['new_column'];
    $newHeight = $_POST['new_line'];

    if (!is_numeric($newWidth) || !is_numeric($newHeight)) {
        header("Location: admin_control.php?error=true");
        exit();
    }

    $newHeight = (int) $newHeight;
    $newWidth = (int) $newWidth;

    if ($newWidth % 2 !== 0 || $newHeight % 2 !== 0) {
        header("Location: admin_control.php?error=true");
        exit();
    }

    if ($newHeight < $height) {
        header("Location: admin_control.php?error=true");
        exit();
    }
    if ($newWidth < $width) {
        header("Location: admin_control.php?error=true");
        exit();
    }

    $filename = 'private/pixels.bin';

    $file = fopen($filename, 'r+b');
    $offset = 0;
    for ($i = 1; $i <= $height; $i++) {
        // goal is to add 0 bytes to the right of the line to do so we need to move to the end of the line, read the rest of the data, move back to the end of the line, write the data with the 0 bytes at the end and then write the rest of the data

        $offset += $width / 2; // Move the offset to the end of the line
        fseek($file, $offset, SEEK_SET);
        $data_left = fread($file, filesize($filename));
        fseek($file, $offset, SEEK_SET);
        $bytes = (int) (($newWidth - $width) / 2);
        $offset += $bytes; // Move the offset to the end of the new line
        $padding = str_repeat(pack('C', 0), $bytes);
        fwrite($file, $padding);
        fwrite($file, $data_left);
    }

    fclose($file);

    $bytes = (int) ($newWidth * ($newHeight - $height)/2);
    $file = fopen($filename, 'a+b');
    $padding = str_repeat(pack('C', 0), $bytes);
    
    fwrite($file, $padding);
    
    fclose($file); // Close the file resource

    $file = fopen('private/taille.txt', 'w');
    fwrite($file, $newWidth . ',' . $newHeight);
    fclose($file);
    header("Location: admin_control.php");
    exit();
}
else {
    header("Location: admin_control.php?error=true");
    exit();
}
