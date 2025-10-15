<?php
$request = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

switch ($request) {
    case "/":
        require_once __DIR__ . "/../app/Controllers/TicketController.php";
        new TicketController()->index();
        break;

    case "/ticket/create":
        require_once __DIR__ . "/../app/Controllers/TicketController.php";
        new TicketController()->create();
        break;

    default:
        http_response_code(404);
        echo "404 Not Found"; // temporary debug
}
