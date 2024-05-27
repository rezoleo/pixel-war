<?php

$file = 'private/is_pixel_war_active.txt';

// Read the file contents
$contents = file_get_contents($file);

// Echo the boolean value
echo $contents;
