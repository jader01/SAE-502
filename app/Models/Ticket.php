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
             (title, description, project_id, client_id, priority, user_id,developer_id,status,evolution)
             VALUES (?, ?, ?, ?, ?, ?, NULL,'open', '')",
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

    /**
     * Assign the current developer to a ticket and move status to in_progress.
     *
     * @param int $ticketId Ticket ID
     * @param int $developerId Developer's user ID
     * @return void
     */
    public static function assignToDeveloper(
        int $ticketId,
        int $developerId,
    ): void {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            UPDATE tickets
            SET developer_id = ?, status = 'in_progress', updated_at = CURRENT_TIMESTAMP
            WHERE id = ? AND developer_id IS NULL
        ");
        $stmt->execute([$developerId, $ticketId]);
    }

    /**
     * Update ticket evolution and status.
     *
     * @param int $ticketId Ticket ID
     * @param string $evolution Description of fix or progress
     * @param string $status New ticket status ('in_progress' | 'closed')
     * @return void
     */
    public static function updateEvolution(
        int $ticketId,
        string $evolution,
        string $status,
    ): void {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            UPDATE tickets
            SET evolution = ?, status = ?, updated_at = CURRENT_TIMESTAMP
            WHERE id = ?
        ");
        $stmt->execute([$evolution, $status, $ticketId]);
    }

    /**
     * Record a new change (evolution) for a ticket.
     *
     * @param int $ticketId Ticket ID
     * @param int $changedBy User ID who performed the change
     * @param string $comment Comment describing the change
     * @param string|null $newStatus New status after change (optional)
     * @return void
     */
    public static function logEvolution(
        int $ticketId,
        int $changedBy,
        string $comment,
        ?string $newStatus = null,
    ): void {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            INSERT INTO ticket_evolution (ticket_id, changed_by, comment, new_status)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([$ticketId, $changedBy, $comment, $newStatus]);
    }

    /**
     * Retrieve the full evolution history for a ticket.
     *
     * @param int $ticketId
     * @return array<int, array<string, mixed>> List of changes
     */
    public static function getEvolutionHistory(int $ticketId): array
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            SELECT te.*, u.username AS changed_by_name
            FROM ticket_evolution te
            LEFT JOIN users u ON u.id = te.changed_by
            WHERE te.ticket_id = ?
            ORDER BY te.created_at ASC
        ");
        $stmt->execute([$ticketId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
