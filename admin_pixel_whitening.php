<?php
session_start();

// Vérifier si l'utilisateur est authentifié
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: admin_login.html");
    exit();
}

function verifyData($columMin, $lineMin, $columnMax, $lineMax, $width, $height) {
    if (!is_int($columMin) || $columMin < 0 || $columMin >= $width) {
        return false;
    }

    if (!is_int($lineMin) || $lineMin < 0 || $lineMin >= $height) {
        return false;
    }

    if (!is_int($columnMax) || $columnMax < 0 || $columnMax >= $width) {
        return false;
    }

    if (!is_int($lineMax) || $lineMax < 0 || $lineMax >= $height) {
        return false;
    }

    return true;
}

$dimensions = file_get_contents('private/taille.txt');
list($width, $height) = explode(',', $dimensions);

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['xMin']) && isset($data['yMin']) && isset($data['xMax']) && isset($data['yMax'])) {

    $columMin = $data['xMin'];
    $lineMin = $data['yMin'];
    $columnMax = $data['xMax'];
    $lineMax = $data['yMax'];

    if (!verifyData($columMin, $lineMin, $columnMax, $lineMax, $width, $height)) {
        // Invalid data
        echo "failed";
        header("HTTP/1.1 400 Bad Request");
        exit();
    }

    $filename = 'private/pixels.bin';
    $offset = (int)(($lineMin * $width + $columMin) / 2);
    $bytes = (int)(($columnMax - $columMin) / 2) + 1;
    $bytesString = str_repeat(pack('C', 0), $bytes);

    $file = fopen($filename, 'r+b');
    fseek($file, $offset, SEEK_SET);

    for ($i = $lineMin; $i <= $lineMax; $i++) {
        fwrite($file, $bytesString);
        $offset += $width / 2;
        fseek($file, $offset, SEEK_SET);
    }
    
    fclose($file);
    echo "success";
    exit();
}
else {
    // Data missing
    echo "failed";
    header("HTTP/1.1 400 Bad Request");
    exit();
}