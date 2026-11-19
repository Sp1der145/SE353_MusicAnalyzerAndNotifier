<?php

function getDb(): PDO
{
    static $pdo = null;

    if ($pdo === null) {
        $config = require __DIR__ . '/db_config.php';

        $dsn = sprintf(
            'mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4',
            $config['host'],
            $config['port'],
            $config['dbname']
        );

        $pdo = new PDO($dsn, $config['user'], $config['password'], [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    }

    return $pdo;
}

// Adds the email if it isn't already stored. Returns true on success.
function saveUserEmail(string $email): bool
{
    $email = strtolower(trim($email));

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false;
    }

    $stmt = getDb()->prepare('INSERT IGNORE INTO users (email) VALUES (?)');
    $stmt->execute([$email]);

    return true;
}

function getAllUserEmails(): array
{
    return getDb()
        ->query('SELECT email FROM users ORDER BY created_at')
        ->fetchAll(PDO::FETCH_COLUMN);
}
