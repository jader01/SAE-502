<?php
require_once __DIR__ . "/../Models/Ticket.php";
require_once __DIR__ . "/../Models/User.php";
require_once __DIR__ . "/../Models/Database.php";

class AdminController
{
    /**
     * Ensure current user has admin role.
     * Exits with 403 if not.
     */
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

    /**
     * Display the admin dashboard with global counts.
     *
     * @return void
     */
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

    /**
     * List, add, or delete clients.
     *
     * Handles GET for listing and deletion; POST for new client insertion.
     *
     * @return void
     */
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

    /**
     * Manage users (list, add, edit, delete).
     *
     * Prevents self‑deletion for the current admin.
     *
     * @return void
     */
    public function handleUsers(): void
    {
        $this->ensureAdmin(); // from your existing AdminController

        $db = Database::getConnection();

        if (isset($_GET["delete"])) {
            $id = (int) $_GET["delete"];
            if ($id !== $_SESSION["user"]["id"]) {
                // prevent self-delete
                User::deleteUser($id);
            }
            header("Location: /admin/users");
            exit();
        }

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
                User::createUser($username, $password, $role);
            }

            header("Location: /admin/users");
            exit();
        }

        $users = User::getAll();
        include __DIR__ . "/../Views/admin/users.php";
    }

    /**
     * Manage projects (list, add, delete).
     *
     * Handles project CRUD tied to clients.
     *
     * @return void
     */
    public function handleProjects(): void
    {
        $this->ensureAdmin();
        $db = Database::getConnection();

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
            ->query(
                "
                SELECT p.*, c.name AS client_name
                FROM projects p
                JOIN clients c ON p.client_id = c.id
                ORDER BY p.id DESC
            ",
            )
            ->fetchAll(PDO::FETCH_ASSOC);
        include __DIR__ . "/../Views/admin/projects.php";
    }

    /**
     * Display basic ticket statistics for admin users.
     *
     * @return void
     */
    public function statistics(): void
    {
        $this->ensureAdmin();

        $stats = Ticket::basicStats();

        include __DIR__ . "/../Views/admin/statistics.php";
    }
}
