<?php
// Generate APP_KEY untuk Laravel
$key = 'base64:' . base64_encode(random_bytes(32));
echo $key;
?>
