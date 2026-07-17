<?php

$token = bin2hex(random_bytes(32));
$hash = hash('sha256', $token);

printf("%7s: %s\n", 'Token', $token);
printf("%7s: %s\n", 'SHA256', $hash);
