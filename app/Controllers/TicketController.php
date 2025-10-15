<?php
require_once __DIR__ . "/../Models/Ticket.php";

class TicketController
{
    /**
     * return all tickets
     */
    public function index(): void
    {
        $tickets = Ticket::all();
        include __DIR__ . "/../Views/tickets/list.php";
    }

    public function create(): void
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $title = $_POST["title"];
            $description = $_POST["description"];
            Ticket::create($title, $description);
            header("Location: /");
            exit();
        }

        include __DIR__ . "/../Views/tickets/form.php";
    }
}
