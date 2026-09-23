<?php

try {
    $token = bin2hex(random_bytes(32));
    $hash = hash('sha256', $token);
} catch (\Throwable $e) {
    http_response_code(500);
    header('Content-Type: text/html');
    echo "<!DOCTYPE html><html><head><title>Error</title></head><body><p>Error: Failed to generate token.</p></body></html>";
    exit;
}
printf ("Token: %s\n",$token);
printf (" Hash: %s\n",$hash);
exit();
