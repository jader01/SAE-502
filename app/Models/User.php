<?php
require_once __DIR__ . "/Database.php";

class User
{
    public static function findByUsername(string $username): ?array
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ?: null;
    }

    public static function create(
        string $username,
        string $password,
        string $role,
    ): void {
        $db = Database::getConnection();
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $db->prepare(
            "INSERT INTO users (username, password, role) VALUES (?, ?, ?)",
        );
        $stmt->execute([$username, $hash, $role]);
    }
}
