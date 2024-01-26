<?php
$filename = 'private/pixels.bin';

// Read binary data from the file
$binaryData = file_get_contents($filename);

// Output binary data as base64 (or any other suitable format)
echo base64_encode($binaryData);
