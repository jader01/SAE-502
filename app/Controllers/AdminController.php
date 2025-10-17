<?php
require_once __DIR__ . "/../Models/Ticket.php";
require_once __DIR__ . "/../Models/User.php";
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

    public function handleUsers(): void
    {
        $this->ensureAdmin(); // from your existing AdminController

        $db = Database::getConnection();

        // Handle deletion
        if (isset($_GET["delete"])) {
            $id = (int) $_GET["delete"];
            if ($id !== $_SESSION["user"]["id"]) {
                // prevent self-delete
                User::deleteUser($id);
            }
            header("Location: /admin/users");
            exit();
        }

        // Handle creation or update
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $username = trim($_POST["username"]);
            $password = $_POST["password"] ?? "";
            $role = $_POST["role"];

            if (isset($_POST["id"]) && $_POST["id"] !== "") {
                // Update existing user
                $userId = (int) $_POST["id"];
                User::updateUser(
                    $userId,
                    $username,
                    $password ? $password : null,
                    $role,
                );
            } else {
                // Create new user
                User::createUser($username, $password, $role);
            }

            header("Location: /admin/users");
            exit();
        }

        // Fetch all users for display
        $users = User::getAll();
        include __DIR__ . "/../Views/admin/users.php";
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

    public function statisticsText(): void
    {
        $this->ensureAdmin();

        $stats = Ticket::basicStats();

        include __DIR__ . "/../Views/admin/statistics_text.php";
    }
}
