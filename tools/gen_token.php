<?php

try {
    $token = bin2hex(random_bytes(32));
    $hash = hash('sha256', $token);
} catch (\Throwable $e) {
    fwrite(STDERR, "Error: Failed to generate token — " . $e->getMessage() . "\n");
    exit(1);
}

printf("%7s: %s\n", 'Token', $token);
printf("%7s: %s\n", 'SHA256', $hash);
