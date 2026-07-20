<?php

// Refuse CLI execution
if (PHP_SAPI === 'cli') {
    http_response_code(403);
    header('Content-Type: text/plain');
    exit('Forbidden: This script must not be run from the command line.' . "\n");
}

// Refuse non-HTTPS
$isHttps = (
    (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
    || (!empty($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443)
    || (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')
);

if (!$isHttps) {
    http_response_code(403);
    header('Content-Type: text/html');
    echo "<!DOCTYPE html><html><head><title>Forbidden</title></head><body><p>Forbidden: HTTPS required.</p></body></html>";
    exit;
}

try {
    $token = bin2hex(random_bytes(32));
    $hash = hash('sha256', $token);
} catch (\Throwable $e) {
    http_response_code(500);
    header('Content-Type: text/html');
    echo "<!DOCTYPE html><html><head><title>Error</title></head><body><p>Error: Failed to generate token.</p></body></html>";
    exit;
}

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Token Generated</title>
</head>
<body>
<table>
    <tr><td>Token</td><td><?= htmlspecialchars($token) ?></td></tr>
    <tr><td>SHA256</td><td><?= htmlspecialchars($hash) ?></td></tr>
</table>
</body>
</html>
