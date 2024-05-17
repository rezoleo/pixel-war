<?php

include('./private/config.php');

// Function to verify if the IP is allowed to write a pixel, data stored in an SQLite database
function authenticateIP($ip, $delay) {

    $dbPath = 'private/ip_timestamp.sqlite';

    // Connect to the SQLite database
    $db = new SQLite3($dbPath);

    // Check if the IP exists in the database
    $stmt = $db->prepare('SELECT timestamp FROM ip_timestamp WHERE ip = :ip');
    $stmt->bindValue(':ip', $ip, SQLITE3_TEXT);
    $result = $stmt->execute();

    $foundIP = false;
    $currentTime = time();

    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $foundIP = true;
        $timestamp = $row['timestamp'];

        // If IP is found, check the timestamp
        if (($currentTime - $timestamp) > $delay) {
            // Update timestamp and return true
            $stmt = $db->prepare('UPDATE ip_timestamp SET timestamp = :timestamp WHERE ip = :ip');
            $stmt->bindValue(':timestamp', $currentTime, SQLITE3_INTEGER);
            $stmt->bindValue(':ip', $ip, SQLITE3_TEXT);
            $stmt->execute();

            return true;
        } else {
            // IP found, but not allowed yet
            echo "Too many requests";
            return false;
        }
    }

    // IP not found, add to database and return true
    if (!$foundIP) {
        $stmt = $db->prepare('INSERT INTO ip_timestamp (ip, timestamp) VALUES (:ip, :timestamp)');
        $stmt->bindValue(':ip', $ip, SQLITE3_TEXT);
        $stmt->bindValue(':timestamp', $currentTime, SQLITE3_INTEGER);
        $stmt->execute();

        return true;
    }

    return false;
}

function getIp(){
    if(!empty($_SERVER['HTTP_CLIENT_IP'])){
      $ip = $_SERVER['HTTP_CLIENT_IP'];
    }
    elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
      $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else{
      $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
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

    if (!is_int($column) || $column < 0 || $column >= $width) {
        $isValid = false;
    }

    if (!is_int($line) || $line < 0 || $line >= $height) {
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

    if (!authenticateIP(getIp(), $delay)) {
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
    echo "failed";
    header("HTTP/1.1 400 Bad Request");
    exit();
}