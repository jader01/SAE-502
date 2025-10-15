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

    /**
     * Create a new ticket in the database.
     *
     * @param string $title       The title of the ticket
     * @param string $description The description of the ticket
     *
     * @return void
     */
    public static function create($title, $description): void
    {
        $db = Database::getConnection();
        $stmt = $db->prepare(
            "INSERT INTO tickets (title, description) VALUES (?, ?)",
        );
        $stmt->execute([$title, $description]);
    }
}
