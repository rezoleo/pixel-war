<?php

session_start();

// Vérifier si l'utilisateur est authentifié
if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    header("HTTP/1.1 400 Bad Request");
    exit();
}

$dimensions = file_get_contents('private/taille.txt');
list($width, $height) = explode(',', $dimensions);

$data = json_decode(file_get_contents('php://input'), true);

// Vérifier si les données sont présentes
if (isset($data['x']) && isset($data['y']) && isset($data['color'])) {

    //x est la position suivant l'axe x (correspond donc à la colonne) et y est la position suivant l'axe y (correspond donc à la ligne)
    $line = $data['y'];
    $column = $data['x'];
    $color = $data['color'];

    // Vérifier si les données sont valides
    $colors = [
        "#ffffff", "#e4e4e4", "#888888", "#222222",
        "#ffa7d1", "#e50000", "#e59500", "#a06a42",
        "#e5d900", "#94e044", "#02be01", "#00d3dd",
        "#0083c7", "#0000ea", "#cd6eea", "#820080"
    ];

    $isValid = true;

    if (!is_int($column) || $column < 0 || $column > $height) {
        $isValid = false;
    }

    if (!is_int($line) || $line < 0 || $line > $width) {
        $isValid = false;
    }

    if (!in_array($color, $colors)) {
        $isValid = false;
    }

    if (!$isValid) {
        // Invalid data
        header("HTTP/1.1 400 Bad Request");
        exit();
    }

    // Transforme la couleur en entier en fonction de sa position dans la liste et l'utilise pour écrire le pixel dans le fichier
    $colorIndex = array_search($color, $colors);
    
    $filename = 'private/pixels.bin';

    //calcul de l'offset
    $offset = (int)(($line * $width + $column) / 2);

    $file = fopen($filename, 'r+b');
    fseek($file, $offset, SEEK_SET);

    $byte = fread($file, 1);
    // Lire un octet du fichier fait avancer le curseur, il faut donc re-seek au bon offset
    fseek($file, $offset, SEEK_SET);
    // On réutilise pack et unpack, comme ça a été fait à l'initialisation (dans init_pixels.php)
    $byte = unpack("C", $byte);
    $byte = $byte[1];

    //ecriture du pixel en fonction de sa parité en

    if (($line * $width + $column) % 2 == 1) {
        $byte = ($byte & 0xF0) | $colorIndex;
    } else {
        $byte = ($byte & 0x0F) | ($colorIndex << 4);
    }
    fwrite($file, pack("C", $byte));
    
    fclose($file);
    echo "success";
    exit();

} else {
    // Données manquantes
    header("HTTP/1.1 400 Bad Request");
    exit();
}