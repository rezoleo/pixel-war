<?php

$dimensions = file_get_contents('private/taille.txt');
list($width, $height) = explode(',', $dimensions);

// Calculate the number of bytes needed
if (isset($_POST['new_line'])) {
    $newHeight = $_POST['new_line'];
    if ($newHeight <= $height) {
        echo "wrong size\n";
        header("HTTP/1.1 400 Bad Request");
        header("Location: admin_control.php");
        exit();
    }
    echo "new height: $newHeight\n";
    $bytes = (int) ($width * ($newHeight - $height)/2);
    $file = fopen('private/pixels.bin', 'a+b');

    // Write 0 bytes to the file
    for ($i = 0; $i < $bytes; $i++) {
        fwrite($file, pack('C', 0));
    }
    fclose($file); // Close the file resource

    $file = fopen('private/taille.txt', 'w');
    fwrite($file, $width . ',' . $newHeight);
    fclose($file);
    echo "success\n";
    header("HTTP/1.1 200 OK");
    header("Location: admin_control.php");
    exit();
}
else {
    echo "bad request\n";
    header("HTTP/1.1 400 Bad Request");
    header("Location: admin_control.php");
    exit();
}
