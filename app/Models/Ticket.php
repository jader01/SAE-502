<?php
require_once __DIR__ . "/Database.php";

class Ticket
{
    /**
     * Retrieve all tickets from the database.
     *
     * @return array<int, array<string, mixed>> List of tickets
     */
    public static function all()
    {
        $db = Database::getConnection();
        $stmt = $db->query("SELECT * FROM tickets ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getClients(): array
    {
        $db = Database::getConnection();
        $stmt = $db->query("SELECT id, name FROM clients ORDER BY name");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get distinct projects
    public static function getProjects(): array
    {
        $db = Database::getConnection();
        $stmt = $db->query("SELECT id, name FROM projects ORDER BY name");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Create a new ticket in the database.
     *
     * @param string $title       Title of the ticket
     * @param string $description Description of the ticket
     * @param int    $project_id  project ID
     * @param int    $client_id   client ID
     * @param string $priority    Ticket priority (p1,p2,p3)
     * @param int|null $user_id   Rapporteur  ID
     *
     * @return void
     */
    public static function create(
        string $title,
        string $description,
        int $project_id,
        int $client_id,
        string $priority = "moyenne",
        ?int $user_id = null,
    ): void {
        $db = Database::getConnection();
        $stmt = $db->prepare(
            "INSERT INTO tickets
             (title, description, project_id, client_id, priority, user_id)
             VALUES (?, ?, ?, ?, ?, ?)",
        );
        $stmt->execute([
            $title,
            $description,
            $project_id,
            $client_id,
            $priority,
            $user_id,
        ]);
    }

    /**
     * Get all projects from client
     *
     * @param int $clientId client ID
     *
     * @return array<int, array<string, mixed>> List of projects
     */
    public static function getProjectsByClient(int $clientId): array
    {
        $db = Database::getConnection();
        $stmt = $db->prepare(
            "SELECT id, name FROM projects WHERE client_id = ? ORDER BY name",
        );
        $stmt->execute([$clientId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
