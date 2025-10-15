<?php
$request = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

switch ($request) {
    case "/":
        require_once __DIR__ . "/../app/Controllers/TicketController.php";
        require_once __DIR__ . "/../app/Controllers/AuthController.php";
        AuthController::requireLogin();
        new TicketController()->index();
        break;

    case "/ticket/create":
        require_once __DIR__ . "/../app/Controllers/TicketController.php";
        require_once __DIR__ . "/../app/Controllers/AuthController.php";
        AuthController::requireLogin();
        new TicketController()->create();
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
