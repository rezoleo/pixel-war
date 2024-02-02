<?php

include('./private/config.php');

session_start();

// Vérifier si l'utilisateur est authentifié
if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    header("HTTP/1.1 400 Bad Request");
    echo "Pas authentifié";
    exit();
}

// Function to check and update IP timestamp in the CSV file
//return true if the IP is allowed to write a pixel, false otherwise and update the timestamp in the CSV file
function authenticateIP($ip, $delay) {
    $csvFile = 'private/ip_timestamp.csv';

    // Read existing CSV data
    $csvData = [];
    if (($handle = fopen($csvFile, 'r')) !== false) {
        //50 is a generous maximum length for a line in the CSV file
        while (($data = fgetcsv($handle, 50, ',')) !== false) {
            $csvData[] = $data;
        }
        fclose($handle);
    }

    // Check if the IP exists in the CSV data
    $foundIP = false;
    foreach ($csvData as $index => $row) {
        list($existingIP, $timestamp) = $row;

        // If IP is found, check the timestamp
        if ($existingIP === $ip) {
            $foundIP = true;

            // Check if the time difference is greater than the delay
            $currentTime = time();
            if (($currentTime - $timestamp) > $delay) {
                // Update timestamp and return true
                $csvData[$index][1] = $currentTime;

                // Write the modified data back to the CSV file
                if (($handle = fopen($csvFile, 'w')) !== false) {
                    foreach ($csvData as $row) {
                        fputcsv($handle, $row);
                    }
                    fclose($handle);
                }

                return true;
            } else {
                // IP found, but not allowed yet
                echo "Too many requests";
                return false;
            }
        }
    }

    // IP not found, add to CSV and return true
    if (!$foundIP) {
        $newData = [$ip, time()];

        // Append the new data to the CSV file
        if (($handle = fopen($csvFile, 'a')) !== false) {
            fputcsv($handle, $newData);
            fclose($handle);
        }

        return true;
    }

    return false;
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

    if (!authenticateIP($_SERVER['REMOTE_ADDR'], $delay)) {
        // Too many requests
        header("HTTP/1.1 429 Too Many Requests");
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