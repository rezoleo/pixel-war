<?php

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

    // Transforme la couleur en entier en fonction de sa position dans la liste
    $colorIndex = array_search($color, $colors);

    // Utilise l'index de couleur pour faire les opérations nécessaires
    $filename = 'private/pixels.bin';
    $file = fopen($filename, 'r+b');
    // Attention à la priorité des opérations
    $offset = (int)(($line * $width + $column) / 2);
    fseek($file, $offset, SEEK_SET);

    $byte = fread($file, 1);
    // Lire un octet du fichier fait avancer le curseur, il faut donc re-seek au bon offset
    fseek($file, $offset, SEEK_SET);
    // On réutilise pack et unpack, comme ça a été fait à l'initialisation (dans init_pixels.php)
    $byte = unpack("C", $byte);
    $byte = $byte[1];

    //écriture du pixel dans le fichier en fonction du nombre de colonnes total (width) et de la colonne actuelle (column) (chaque pixel est codé sur 4 bits, donc 2 pixels par octet)
    //si le nombre de colonnes total (width) est pair l'écriture sur les bits de poids ou faible se fait uniquement en fonction de la colonne actuelle (column)

    if ($width % 2 == 0){
        // Si la colonne est paire, on modifie les 4 premiers bits, sinon les 4 derniers
        if ($column % 2 == 1) {
            $byte = ($byte & 0xF0) | $colorIndex;
        } else {
            $byte = ($byte & 0x0F) | ($colorIndex << 4);
        }

        fwrite($file, pack("C", $byte));
    }
    else {
        //tout dépend de la ligne si la ligne est paire c'est comme avant, sinon c'est l'inverse
        if ($line % 2 == 0){
            if ($column % 2 == 1) {
                $byte = ($byte & 0xF0) | $colorIndex;
            } else {
                $byte = ($byte & 0x0F) | ($colorIndex << 4);
            }

            fwrite($file, pack("C", $byte));
        }
        else{
            if ($column % 2 == 0) {
                $byte = ($byte & 0xF0) | ($colorIndex << 4);
            } else {
                $byte = ($byte & 0x0F) | $colorIndex;
            }

            fwrite($file, pack("C", $byte));
        
        }
    }

    //fin de la partie écriture du pixel dans le fichier en fonction des différents cas

    fclose($file);
    echo "success";
    exit();

} else {
    // Données manquantes
    header("HTTP/1.1 400 Bad Request");
    exit();
}