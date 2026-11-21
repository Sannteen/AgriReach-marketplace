<?php
// Start a session so users can stay logged in between pages.
if (session_status() !== PHP_SESSION_ACTIVE) {
    ini_set('session.use_strict_mode', '1');
    ini_set('session.use_only_cookies', '1');
    session_set_cookie_params([
        'httponly' => true,
        'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        'samesite' => 'Lax',
        'path' => '/',
    ]);
    session_start();
}

// Escape database values before displaying them inside HTML.
function e($value): string {
    return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

// Format product prices using Jamaican dollars.
function priceJmd($amount): string {
    return 'J$' . number_format((float)$amount, 2);
}

// Connect to the MySQL database using the settings supplied by Docker.
function db(): PDO
{
    static $pdo = null;

    // Reuse the database connection if one already exists.
    if ($pdo !== null) {
        return $pdo;
    }

    // Read the database settings from the PHP web container.
    $host = getenv('DB_HOST') ?: 'db';
    $database = getenv('DB_NAME') ?: 'agrireach';
    $username = getenv('DB_USER') ?: 'agrireach_user';
    $password = getenv('DB_PASSWORD');

    // Stop if Docker did not provide the database password.
    if ($password === false || $password === '') {
        error_log('AgriReach: DB_PASSWORD is missing.');
        http_response_code(503);
        exit('Database configuration is missing.');
    }

    // Build the connection address for MySQL.
    $dsn = "mysql:host=$host;dbname=$database;charset=utf8mb4";

    try {
        // Connect to MySQL using the supplied credentials.
        $pdo = new PDO($dsn, $username, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);

    } catch (PDOException $exception) {

        // Record the database error without displaying it to visitors.
        error_log(
            'AgriReach database connection error: ' .
            $exception->getMessage()
        );

        // Display a safe error message if the connection fails.
        http_response_code(503);
        exit('Database temporarily unavailable.');
    }

    // Return the successful database connection.
    return $pdo;
}

// Create a random token to protect forms from cross-site request forgery.
function csrfToken(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Reject form submissions that do not contain the correct security token.
function checkCsrf(): void {
    if (!hash_equals(csrfToken(), (string)($_POST['csrf_token'] ?? ''))) {
        http_response_code(403);
        exit('Invalid form token. Refresh the page and try again.');
    }
}

// Get the current user's account information from the session.
function currentUser(): ?array {
    return $_SESSION['user'] ?? null;
}

// Redirect visitors to login if they have not signed in.
function requireLogin(): array {
    if (!currentUser()) {
        header('Location: /login.php');
        exit;
    }
    return currentUser();
}

// Restrict product management pages to farmer accounts.
function requireFarmer(): array {
    $user = requireLogin();
    if ($user['role'] !== 'farmer') {
        http_response_code(403);
        exit('Only farmer accounts can manage produce listings.');
    }
    return $user;
}

// Display a one-time confirmation message after redirects.
function flash(?string $message = null): ?string {
    if ($message !== null) {
        $_SESSION['flash'] = $message;
        return null;
    }
    $stored = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $stored;
}

// Choose a simple produce illustration when a product has no uploaded image.
function produceEmoji(string $category): string {
    return match ($category) {
        'Fruits' => '🍍',
        'Ground Provisions' => '🍠',
        default => '🥬',
    };
}
