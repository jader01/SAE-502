<?php
require_once __DIR__ . "/Database.php";

class Ticket
{
    /**
     * Retrieve all tickets ordered by creation date.
     *
     * @return array<int, array<string, mixed>> List of all tickets
     */
    public static function all()
    {
        $db = Database::getConnection();
        $stmt = $db->query("SELECT * FROM tickets ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Retrieve all clients.
     *
     * @return array<int, array<string, mixed>> List of clients
     */
    public static function getClients(): array
    {
        $db = Database::getConnection();
        $stmt = $db->query("SELECT id, name FROM clients ORDER BY name");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Retrieve all projects.
     *
     * @return array<int, array<string, mixed>> List of projects
     */
    public static function getProjects(): array
    {
        $db = Database::getConnection();
        $stmt = $db->query("SELECT id, name FROM projects ORDER BY name");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Create a new ticket record.
     *
     * @param string   $title       Ticket title
     * @param string   $description Ticket description
     * @param int      $project_id  Linked project ID
     * @param int      $client_id   Linked client ID
     * @param string   $priority    Ticket priority (p1, p2, p3)
     * @param int|null $user_id     Rapporteur user ID
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
     * Retrieve projects belonging to a specific client.
     *
     * @param int $clientId Client ID
     * @return array<int, array<string, mixed>> List of projects for the client
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
     * Assign a developer to a ticket and mark it as in progress.
     *
     * @param int $ticketId   Ticket ID
     * @param int $developerId Developer user ID
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
     * Update ticket evolution and/or status.
     *
     * @param int    $ticketId  Ticket ID
     * @param string $evolution Developer evolution comment
     * @param string $status    New ticket status
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
     * Log a new evolution entry for a ticket.
     *
     * @param int         $ticketId   Ticket ID
     * @param int         $changedBy  User ID who made the change
     * @param string      $comment    Description of the change
     * @param string|null $newStatus  Optional new ticket status
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
     * Retrieve evolution history for a given ticket.
     *
     * @param int $ticketId Ticket ID
     * @return array<int, array<string, mixed>> List of evolution entries
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

    /**
     * Retrieve tickets visible to a specific user based on role.
     *
     * @param string $role   User role
     * @param int    $userId User ID
     * @return array<int, array<string, mixed>> List of tickets
     */
    public static function getTicketsForUser(string $role, int $userId): array
    {
        $db = Database::getConnection();

        $baseQuery = <<<SQL
            SELECT
                t.*,
                c.name AS client_name,
                p.name AS project_name,
                u.username AS rapporteur_name,
                d.username AS developer_name
            FROM tickets t
            JOIN clients  c ON t.client_id    = c.id
            JOIN projects p ON t.project_id   = p.id
            LEFT JOIN users u ON t.user_id    = u.id
            LEFT JOIN users d ON t.developer_id = d.id
        SQL;

        switch ($role) {
            case "rapporteur":
                $sql =
                    $baseQuery .
                    '
                    WHERE t.user_id = ?
                    ORDER BY t.created_at DESC
                ';
                $stmt = $db->prepare($sql);
                $stmt->execute([$userId]);
                break;

            case "developpeur":
                $stmt = $db->prepare("
                        $baseQuery
                        WHERE (t.developer_id IS NULL OR t.developer_id = ?)
                          AND t.status != 'closed'
                        ORDER BY t.created_at DESC
                    ");
                $stmt->execute([$userId]);
                break;

            case "admin":
                $sql = $baseQuery . " ORDER BY t.created_at DESC";
                $stmt = $db->query($sql);
                break;

            default:
                return [];
        }

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Delete a ticket by its ID.
     *
     * @param int $ticketId Ticket ID
     * @return void
     */
    public static function deleteTicket(int $ticketId): void
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("DELETE FROM tickets WHERE id = ?");
        $stmt->execute([$ticketId]);
    }

    /**
     * Compute basic ticket statistics.
     * Includes totals per status, rapporteur, developer, and time range.
     *
     * @return array<string, mixed> Summary statistics
     */
    public static function basicStats(): array
    {
        $db = Database::getConnection();

        $statusStmt = $db->query("
            SELECT status, COUNT(*) as total
            FROM tickets
            GROUP BY status
        ");
        $status = $statusStmt->fetchAll(PDO::FETCH_KEY_PAIR);

        $rapStmt = $db->query("
            SELECT u.username, COUNT(t.id) AS total
            FROM tickets t
            JOIN users u ON u.id = t.user_id
            GROUP BY t.user_id
            ORDER BY total DESC
        ");
        $rapporteurs = $rapStmt->fetchAll(PDO::FETCH_ASSOC);

        $devStmt = $db->query("
            SELECT u.username, COUNT(t.id) AS total
            FROM tickets t
            JOIN users u ON u.id = t.developer_id
            WHERE t.developer_id IS NOT NULL
            GROUP BY t.developer_id
            ORDER BY total DESC
        ");
        $developers = $devStmt->fetchAll(PDO::FETCH_ASSOC);

        $day = $db
            ->query(
                "SELECT DATE(created_at) as date, COUNT(*) as total FROM tickets GROUP BY DATE(created_at) ORDER BY DATE(created_at) DESC LIMIT 30",
            )
            ->fetchAll(PDO::FETCH_ASSOC);
        $month = $db
            ->query(
                "SELECT strftime('%m', created_at) as month, COUNT(*) as total FROM tickets WHERE strftime('%Y', created_at)=strftime('%Y','now') GROUP BY month ORDER BY month",
            )
            ->fetchAll(PDO::FETCH_ASSOC);
        $year = $db
            ->query(
                "SELECT strftime('%Y', created_at) as year, COUNT(*) as total FROM tickets GROUP BY year ORDER BY year DESC",
            )
            ->fetchAll(PDO::FETCH_ASSOC);

        return [
            "status" => $status,
            "rapporteurs" => $rapporteurs,
            "developers" => $developers,
            "day" => $day,
            "month" => $month,
            "year" => $year,
        ];
    }
}
