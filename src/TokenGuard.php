<?php

namespace App\Lib;

class TokenGuard
{
    private $expectedHash;

    public function __construct(string $expectedHash)
    {
        $this->expectedHash = strtolower(trim($expectedHash));
    }

    public function enforce(): void
    {
        $token = $this->getBearerToken();

        if (!$token) {
            $this->deny('Missing bearer token');
        }

        $presentedHash = hash('sha256', $token);

        if (!hash_equals($this->expectedHash, $presentedHash)) {
            $this->deny('Invalid token');
        }
    }

    private function getBearerToken(): ?string
    {
        $header = $this->getAuthorizationHeader();

        if (!$header) {
            return null;
        }

        if (preg_match('/Bearer\s+(\S+)/i', $header, $matches)) {
            return $matches[1];
        }

        return null;
    }

    private function getAuthorizationHeader(): ?string
    {
        if (!empty($_SERVER['HTTP_AUTHORIZATION'])) {
            return trim($_SERVER['HTTP_AUTHORIZATION']);
        }

        if (!empty($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
            return trim($_SERVER['REDIRECT_HTTP_AUTHORIZATION']);
        }

        if (function_exists('getallheaders')) {
            $headers = getallheaders();
            foreach ($headers as $key => $value) {
                if (strtolower($key) === 'authorization') {
                    return trim($value);
                }
            }
        }

        return null;
    }

    private function deny(string $message): void
    {
        http_response_code(401);
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'error' => $message,
        ], JSON_PRETTY_PRINT);
        exit;
    }
}
