# TokenGuard

Simple SHA-256 bearer token guard middleware for PHP.

## Requirements

- PHP >= 7.4

## Installation

```bash
composer require xdeviousx/token-guard
```

## Usage

```php
use App\Lib\TokenGuard;

// Pass the expected SHA-256 hash of your secret token
$guard = new TokenGuard('5e884898da28047d916453fb47e4ba6b0f07b4c3c8d5e9a7a3c9d2b5f8e4a1c');

// Call at the top of any protected script or route
$guard->enforce();

// Request continues here if token is valid
```

## Generating the Hash

```bash
php -r "echo hash('sha256', 'your-secret-token');"
```

## How It Works

- Reads the `Authorization: Bearer <token>` header from the incoming request
- Computes `SHA-256` of the presented token
- Compares against the expected hash using `hash_equals()` (timing-safe)
- Returns `401` with JSON error on mismatch or missing token

Supports Apache (`$_SERVER['HTTP_AUTHORIZATION']`), Nginx (`REDIRECT_HTTP_AUTHORIZATION`), and `getallheaders()`.
