<?php
require_once __DIR__ . "/../Models/Ticket.php";
require_once __DIR__ . "/../Controllers/AuthController.php";

class TicketController
{
    /**
     * List all accessible tickets depending on user role.
     *
     * Roles allowed: developpeur, rapporteur, admin.
     *
     * @return void
     */
    public function list(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (
            !in_array(
                $_SESSION["user"]["role"],
                ["developpeur", "admin", "rapporteur"],
                true,
            )
        ) {
            http_response_code(403);
            echo "Accès refusé";
            exit();
        }

        $tickets = Ticket::getTicketsForUser(
            $_SESSION["user"]["role"],
            $_SESSION["user"]["id"],
        );
        include __DIR__ . "/../Views/tickets/list.php";
    }

    /**
     * Display and process ticket creation form.
     *
     * Roles allowed: rapporteur, admin.
     * On GET: show form.
     * On POST: create new ticket.
     *
     * @return void
     */
    public function create(): void
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
            header("Location: /ticket/list");
            exit();
        }
        $clientId = isset($_GET["client_id"]) ? (int) $_GET["client_id"] : 0;
        $clients = Ticket::getClients();
        $projects = $clientId > 0 ? Ticket::getProjectsByClient($clientId) : [];
        include __DIR__ . "/../Views/tickets/create.php";
    }

    /**
     * Delete a ticket.
     *
     * Admin: Can delete any.
     * Rapporteur: Can delete own unassigned or closed tickets.
     *
     * @return void
     */
    public function deleteTicket(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (empty($_SESSION["user"])) {
            http_response_code(403);
            exit("Non authentifié.");
        }

        $ticketId = (int) ($_GET["id"] ?? 0);
        if ($ticketId <= 0) {
            http_response_code(400);
            exit("ID de ticket invalide.");
        }

        $role = $_SESSION["user"]["role"];
        $userId = $_SESSION["user"]["id"];

        $db = Database::getConnection();
        $stmt = $db->prepare(
            "SELECT id, user_id, status FROM tickets WHERE id = ?",
        );
        $stmt->execute([$ticketId]);
        $ticket = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$ticket) {
            http_response_code(404);
            exit("Ticket introuvable.");
        }

        if ($role === "admin") {
            Ticket::deleteTicket($ticketId);
        } elseif ($role === "rapporteur") {
            $isOwner = $ticket["user_id"] == $userId;
            $isClosed = $ticket["status"] === "closed";
            $isUnassigned = empty($ticket["developer_id"]);

            if ($isOwner && ($isClosed || $isUnassigned)) {
                Ticket::deleteTicket($ticketId);
            } else {
                http_response_code(403);
                exit(
                    "Accès refusé : vous ne pouvez supprimer que vos tickets non assignés ou fermés."
                );
            }
        } else {
            http_response_code(403);
            exit(
                "Accès refusé : seuls les administrateurs et rapporteurs peuvent supprimer des tickets."
            );
        }

        header("Location: /ticket/list");
        exit();
    }

    /**
     * Fetch all projects linked to a given client (AJAX endpoint).
     *
     * URL: /projects?client_id={id}
     * Roles allowed: rapporteur, admin.
     *
     * @return void
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

    /**
     * Allow a developer to assign themselves to a ticket.
     *
     * Roles allowed: developpeur, admin.
     *
     * @return void
     */
    public function takeTicket(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
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

    /**
     * Update a ticket's evolution or status.
     *
     * Roles allowed: developpeur, admin.
     * Rejects modification of closed tickets.
     *
     * @return void
     */
    public function updateTicket(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
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

            $current = Database::getConnection()->prepare(
                "SELECT status FROM tickets WHERE id = ?",
            );
            $current->execute([$ticketId]);
            $ticket = $current->fetch(PDO::FETCH_ASSOC);

            if (!$ticket) {
                http_response_code(404);
                exit("Ticket introuvable.");
            }

            if ($ticket["status"] === "closed") {
                http_response_code(403);
                exit("Impossible de modifier un ticket fermé.");
            }

            Ticket::updateEvolution($ticketId, $evolution, $status);

            Ticket::logEvolution(
                $ticketId,
                $_SESSION["user"]["id"],
                $evolution,
                $status,
            );

            header("Location: /ticket/list");
            exit();
        }

        header("Location: /ticket/list");
        exit();
    }

    /**
     * Edit ticket details (admin only).
     *
     * Shows edit form on GET; updates ticket on POST.
     *
     * @return void
     */
    public function editTicket(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!in_array($_SESSION["user"]["role"], ["admin"], true)) {
            http_response_code(403);
            exit("Accès refusé.");
        }

        $ticketId = isset($_GET["id"]) ? (int) $_GET["id"] : 0;
        if ($ticketId <= 0) {
            http_response_code(400);
            exit("ID de ticket invalide.");
        }

        $db = Database::getConnection();

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $title = trim($_POST["title"]);
            $description = trim($_POST["description"]);
            $priority = $_POST["priority"];
            $status = $_POST["status"];
            $evolution = $_POST["evolution"];

            $stmt = $db->prepare("
                UPDATE tickets
                SET title = ?, description = ?, priority = ?, status = ?, evolution = ?,
                    developer_id = ?, user_id = ?, updated_at = CURRENT_TIMESTAMP
                WHERE id = ?
            ");
            $stmt->execute([
                $title,
                $description,
                $priority,
                $status,
                $evolution,
                $_POST["developer_id"] ?: null,
                $_POST["rapporteur_id"] ?: null,
                $ticketId,
            ]);

            header("Location: /ticket/list");
            exit();
        }

        $stmt = $db->prepare("SELECT * FROM tickets WHERE id = ?");
        $stmt->execute([$ticketId]);
        $ticket = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$ticket) {
            http_response_code(404);
            exit("Ticket introuvable.");
        }
        $devStmt = $db->query(
            "SELECT id, username FROM users WHERE role = 'developpeur'",
        );
        $developers = $devStmt->fetchAll(PDO::FETCH_ASSOC);

        $rapStmt = $db->query(
            "SELECT id, username FROM users WHERE role = 'rapporteur'",
        );
        $rapporteurs = $rapStmt->fetchAll(PDO::FETCH_ASSOC);
        include __DIR__ . "/../Views/tickets/edit.php";
    }

    /**
     * Display a ticket’s details and its evolution history.
     *
     * @return void
     */
    public function showTicket(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
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
