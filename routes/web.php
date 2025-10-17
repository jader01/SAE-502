<?php
$request = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
switch ($request) {
    case "/":
        if (isset($_SESSION["user"]) && isset($_SESSION["user"]["role"])) {
            switch ($_SESSION["user"]["role"]) {
                case "developpeur":
                    header("Location: /ticket/list");
                    exit();
                case "rapporteur":
                    header("Location: /ticket/create");
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

    case "/ticket/create":
        require_once __DIR__ . "/../app/Controllers/TicketController.php";

        new TicketController()->createForRapporteur();
        break;

    case "/admin/statistics":
        require_once __DIR__ . "/../app/Controllers/AdminController.php";
        new AdminController()->statisticsText();
        break;

    case "/ticket/take":
        require_once __DIR__ . "/../app/Controllers/TicketController.php";
        new TicketController()->takeTicket();
        break;

    case "/ticket/update":
        require_once __DIR__ . "/../app/Controllers/TicketController.php";
        new TicketController()->updateTicket();
        break;

    case "/ticket/list":
        require_once __DIR__ . "/../app/Controllers/TicketController.php";
        new TicketController()->listForDeveloper();
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

    case "/projects":
        require_once __DIR__ . "/../app/Controllers/AuthController.php";
        AuthController::requireLogin();

        require_once __DIR__ . "/../app/Controllers/TicketController.php";
        new TicketController()->fetchProjects();
        exit();

    case "/login":
        require_once __DIR__ . "/../app/Controllers/AuthController.php";
        new AuthController()->login();
        break;
    case "/register":
        require_once __DIR__ . "/../app/Controllers/AuthController.php";
        new AuthController()->register();
        break;
    case "/logout":
        require_once __DIR__ . "/../app/Controllers/AuthController.php";
        new AuthController()->logout();
        break;

    default:
        http_response_code(404);
        echo "404 Not Found";
}
