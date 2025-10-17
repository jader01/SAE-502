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

    /**
     * Retrieve all users.
     *
     * @return array<int, array<string, mixed>>
     */
    public static function getAll(): array
    {
        $db = Database::getConnection();
        $stmt = $db->query(
            "SELECT id, username, role, created_at FROM users ORDER BY id ASC",
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Create a new user.
     *
     * @param string $username
     * @param string $password
     * @param string $role ('admin', 'developpeur', 'rapporteur')
     * @return void
     */
    public static function createUser(
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

    /**
     * Update an existing user.
     *
     * @param int $id
     * @param string $username
     * @param string|null $password
     * @param string $role
     * @return void
     */
    public static function updateUser(
        int $id,
        string $username,
        ?string $password,
        string $role,
    ): void {
        $db = Database::getConnection();
        if ($password) {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $db->prepare(
                "UPDATE users SET username = ?, password = ?, role = ? WHERE id = ?",
            );
            $stmt->execute([$username, $hash, $role, $id]);
        } else {
            $stmt = $db->prepare(
                "UPDATE users SET username = ?, role = ? WHERE id = ?",
            );
            $stmt->execute([$username, $role, $id]);
        }
    }

    /**
     * Delete a user by ID.
     *
     * @param int $id
     * @return void
     */
    public static function deleteUser(int $id): void
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$id]);
    }
}
