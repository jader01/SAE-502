<?php
require_once __DIR__ . "/../Models/Ticket.php";
require_once __DIR__ . "/../Controllers/AuthController.php";

class TicketController
{
    /**
     * List of all tickets for devs
     *
     * require developpeur role
     */
    public function listForDeveloper(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (
            !in_array($_SESSION["user"]["role"], ["developpeur", "admin"], true)
        ) {
            http_response_code(403);
            echo "Accès refusé : réservé aux développeurs.";
            exit();
        }

        $tickets = Ticket::all();
        include __DIR__ . "/../Views/tickets/list_developer.php";
    }

    /**
     * Display the creation form
     *
     * require rapporteur role
     * On POST: Create new ticket
     * On GET: display the form
     */
    public function createForRapporteur(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (
            !in_array($_SESSION["user"]["role"], ["rapporteur", "admin"], true)
        ) {
            http_response_code(403);
            echo "Accès refusé : réservé aux rapporteurs.";
            exit();
        }

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $title = $_POST["title"];
            $description = $_POST["description"];
            $client_id = (int) $_POST["client_id"];
            $project_id = (int) $_POST["project_id"];
            $priority = $_POST["priority"] ?? "p2";
            $user_id = $_SESSION["user"]["id"];

            Ticket::create(
                $title,
                $description,
                $project_id,
                $client_id,
                $priority,
                $user_id,
            );
            header("Location: /ticket/create?success=1");
            exit();
        }
        $clientId = isset($_GET["client_id"]) ? (int) $_GET["client_id"] : 0;
        $clients = Ticket::getClients();
        $projects = $clientId > 0 ? Ticket::getProjectsByClient($clientId) : [];
        include __DIR__ . "/../Views/tickets/create_rapporteur.php";
    }
    /**
     * Return a JSON of projects linked to given client
     *
     * URL: /projects?client_id={id}
     */
    public function fetchProjects(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        header("Content-Type: application/json");

        if (empty($_SESSION["user"])) {
            http_response_code(401);
            echo json_encode(["error" => "Non authentifié"]);
            exit();
        }

        if (
            !in_array($_SESSION["user"]["role"], ["rapporteur", "admin"], true)
        ) {
            http_response_code(403);
            echo json_encode(["error" => "Accès refusé"]);
            exit();
        }

        $clientId = isset($_GET["client_id"]) ? (int) $_GET["client_id"] : 0;
        $projects = Ticket::getProjectsByClient($clientId);
        echo json_encode($projects);
        exit();
    }

    public function takeTicket(): void
    {
        session_start();
        if (
            !in_array($_SESSION["user"]["role"], ["developpeur", "admin"], true)
        ) {
            http_response_code(403);
            exit("Accès réservé aux développeurs.");
        }

        $ticketId = (int) ($_GET["id"] ?? 0);
        if ($ticketId > 0) {
            Ticket::assignToDeveloper($ticketId, $_SESSION["user"]["id"]);
        }
        header("Location: /ticket/list");
        exit();
    }

    public function updateTicket(): void
    {
        session_start();
        if (
            !in_array($_SESSION["user"]["role"], ["developpeur", "admin"], true)
        ) {
            http_response_code(403);
            exit("Accès réservé aux développeurs.");
        }

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $ticketId = (int) $_POST["ticket_id"];
            $evolution = $_POST["evolution"];
            $status = $_POST["status"];
            Ticket::updateEvolution($ticketId, $evolution, $status);

            Ticket::logEvolution(
                $ticketId,
                $_SESSION["user"]["id"],
                $evolution,
                $status,
            );
        }

        header("Location: /ticket/list");
        exit();
    }

    public function showTicket(): void
    {
        session_start();
        $ticketId = (int) ($_GET["id"] ?? 0);
        if ($ticketId <= 0) {
            http_response_code(404);
            exit("Ticket introuvable.");
        }

        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM tickets WHERE id = ?");
        $stmt->execute([$ticketId]);
        $ticket = $stmt->fetch(PDO::FETCH_ASSOC);
        $history = Ticket::getEvolutionHistory($ticketId);

        include __DIR__ . "/../Views/tickets/show.php";
    }
}
