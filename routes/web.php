<?php
// Basic router
$request = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
switch ($request) {
    // Redirect users based on role
    case "/":
        if (isset($_SESSION["user"]) && isset($_SESSION["user"]["role"])) {
            switch ($_SESSION["user"]["role"]) {
                case "developpeur":
                    header("Location: /ticket/list");
                    exit();
                case "rapporteur":
                    header("Location: /ticket/list");
                    exit();
                case "admin":
                    header("Location: /admin/dashboard");
                    exit();
                default:
                    echo "Rôle inconnu.";
                    exit();
            }
        } else {
            header("Location: /login");
            exit();
        }

    // Admin area
    case "/admin":
    case "/admin/dashboard":
        require_once __DIR__ . "/../app/Controllers/AdminController.php";
        new AdminController()->dashboard();
        break;

    case "/admin/clients":
        require_once __DIR__ . "/../app/Controllers/AdminController.php";
        new AdminController()->handleClients();
        break;

    case "/admin/projects":
        require_once __DIR__ . "/../app/Controllers/AdminController.php";
        new AdminController()->handleProjects();
        break;

    case "/admin/users":
        require_once __DIR__ . "/../app/Controllers/AdminController.php";
        new AdminController()->handleUsers();
        break;

    case "/admin/statistics":
        require_once __DIR__ . "/../app/Controllers/AdminController.php";
        new AdminController()->statistics();
        break;

    // Ticket management
    case "/ticket/create":
        require_once __DIR__ . "/../app/Controllers/TicketController.php";

        new TicketController()->create();
        break;

    case "/ticket/take":
        require_once __DIR__ . "/../app/Controllers/TicketController.php";
        new TicketController()->takeTicket();
        break;

    case "/ticket/update":
        require_once __DIR__ . "/../app/Controllers/TicketController.php";
        new TicketController()->updateTicket();
        break;

    case "/ticket/edit":
        require_once __DIR__ . "/../app/Controllers/AuthController.php";
        AuthController::requireLogin();

        require_once __DIR__ . "/../app/Controllers/TicketController.php";
        new TicketController()->editTicket();
        break;

    case "/ticket/list":
        require_once __DIR__ . "/../app/Controllers/TicketController.php";
        new TicketController()->list();
        break;

    case "/ticket/show":
        require_once __DIR__ . "/../app/Controllers/AuthController.php";
        AuthController::requireLogin();
        require_once __DIR__ . "/../app/Controllers/TicketController.php";
        new TicketController()->showTicket();
        break;

    case "/ticket/delete":
        require_once __DIR__ . "/../app/Controllers/AuthController.php";
        AuthController::requireLogin();
        require_once __DIR__ . "/../app/Controllers/TicketController.php";
        new TicketController()->deleteTicket();
        break;

    // AJAX: Load projects by client
    case "/projects":
        require_once __DIR__ . "/../app/Controllers/AuthController.php";
        AuthController::requireLogin();

        require_once __DIR__ . "/../app/Controllers/TicketController.php";
        new TicketController()->fetchProjects();
        exit();

    // Authentication
    case "/login":
        require_once __DIR__ . "/../app/Controllers/AuthController.php";
        new AuthController()->login();
        break;

    case "/logout":
        require_once __DIR__ . "/../app/Controllers/AuthController.php";
        new AuthController()->logout();
        break;

    // Fallback
    default:
        http_response_code(404);
        echo "404 Not Found";
}
