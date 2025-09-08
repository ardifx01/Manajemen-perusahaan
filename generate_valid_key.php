<?php
// Generate valid 32-byte key for Laravel
$key = random_bytes(32);
$base64Key = base64_encode($key);
echo "base64:$base64Key";
?>
