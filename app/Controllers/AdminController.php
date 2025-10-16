<?php
require_once __DIR__ . "/../Models/Ticket.php";
require_once __DIR__ . "/../Models/Database.php";

class AdminController
{
    private function ensureAdmin(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (empty($_SESSION["user"]) || $_SESSION["user"]["role"] !== "admin") {
            http_response_code(403);
            exit("Accès réservé aux administrateurs.");
        }
    }

    public function dashboard(): void
    {
        $this->ensureAdmin();
        $db = Database::getConnection();
        $stats = [
            "tickets" => $db
                ->query("SELECT COUNT(*) FROM tickets")
                ->fetchColumn(),
            "clients" => $db
                ->query("SELECT COUNT(*) FROM clients")
                ->fetchColumn(),
            "projects" => $db
                ->query("SELECT COUNT(*) FROM projects")
                ->fetchColumn(),
        ];
        include __DIR__ . "/../Views/admin/dashboard.php";
    }

    /** Manage (list/add/delete) clients */
    public function handleClients(): void
    {
        $this->ensureAdmin();
        $db = Database::getConnection();

        // Add client
        if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["name"])) {
            $stmt = $db->prepare(
                "INSERT INTO clients (name, contact_email, contact_phone) VALUES (?, ?, ?)",
            );
            $stmt->execute([
                $_POST["name"],
                $_POST["contact_email"],
                $_POST["contact_phone"],
            ]);
            header("Location: /admin/clients");
            exit();
        }

        // Delete client
        if (isset($_GET["delete"])) {
            $id = (int) $_GET["delete"];
            $db->prepare("DELETE FROM clients WHERE id = ?")->execute([$id]);
            header("Location: /admin/clients");
            exit();
        }

        $clients = $db
            ->query("SELECT * FROM clients ORDER BY id DESC")
            ->fetchAll(PDO::FETCH_ASSOC);
        include __DIR__ . "/../Views/admin/clients.php";
    }

    /** Manage (list/add/delete) projects */
    public function handleProjects(): void
    {
        $this->ensureAdmin();
        $db = Database::getConnection();

        // Add project
        if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["name"])) {
            $stmt = $db->prepare(
                "INSERT INTO projects (name, description, client_id) VALUES (?, ?, ?)",
            );
            $stmt->execute([
                $_POST["name"],
                $_POST["description"],
                $_POST["client_id"],
            ]);
            header("Location: /admin/projects");
            exit();
        }

        // Delete project
        if (isset($_GET["delete"])) {
            $id = (int) $_GET["delete"];
            $db->prepare("DELETE FROM projects WHERE id = ?")->execute([$id]);
            header("Location: /admin/projects");
            exit();
        }

        $clients = $db
            ->query("SELECT id, name FROM clients")
            ->fetchAll(PDO::FETCH_ASSOC);
        $projects = $db
            ->query("SELECT * FROM projects ORDER BY id DESC")
            ->fetchAll(PDO::FETCH_ASSOC);
        include __DIR__ . "/../Views/admin/projects.php";
    }
}
