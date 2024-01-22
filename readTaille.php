<?php
// Specify the path to your taille.txt file
$filename = 'private/taille.txt';

// Read the content of taille.txt
$fileContent = file_get_contents($filename);

// Output the content
echo $fileContent;
?>