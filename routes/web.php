<?php
$request = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

require_once __DIR__ . "/../app/Controllers/AuthController.php";
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$publicRoutes = ["/login", "/register"];
if (!in_array($request, $publicRoutes) && empty($_SESSION["user"])) {
    header("Location: /login");
    exit();
}

switch ($request) {
    case "/":
        if ($_SESSION["user"]["role"] === "developpeur") {
            header("Location: /ticket/list");
            exit();
        }
        if ($_SESSION["user"]["role"] === "rapporteur") {
            header("Location: /ticket/create");
            exit();
        }
        if ($_SESSION["user"]["role"] === "admin") {
            header("Location: /admin/dashboard");
            exit();
        }
        // fallback
        echo "Rôle inconnu.";
        break;

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

    case "/projects":
        require_once __DIR__ . "/../app/Controllers/TicketController.php";
        new TicketController()->fetchProjects();
        break;

    case "/ticket/create":
        require_once __DIR__ . "/../app/Controllers/TicketController.php";
        new TicketController()->createForRapporteur();
        break;

    case "/ticket/list":
        require_once __DIR__ . "/../app/Controllers/TicketController.php";
        new TicketController()->listForDeveloper();
        break;

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
