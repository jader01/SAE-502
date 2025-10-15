<?php
require_once __DIR__ . "/../Models/User.php";

class AuthController
{
    public function login(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $username = trim($_POST["username"]);
            $password = $_POST["password"];

            $user = User::findByUsername($username);

            if ($user && password_verify($password, $user["password"])) {
                $_SESSION["user"] = [
                    "id" => $user["id"],
                    "username" => $user["username"],
                    "role" => $user["role"],
                ];
                header("Location: /");
                exit();
            }

            $error = "Nom d’utilisateur ou mot de passe incorrect.";
        }

        include __DIR__ . "/../Views/auth/login.php";
    }

    public function register(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $username = trim($_POST["username"]);
            $password = $_POST["password"];
            $role = $_POST["role"] ?? "rapporteur";

            if (User::findByUsername($username)) {
                $error = "Ce nom d'utilisateur existe déjà.";
            } elseif (strlen($password) < 4) {
                $error = "Le mot de passe est trop court.";
            } else {
                User::create($username, $password, $role);
                header("Location: /login");
                exit();
            }
        }
        include __DIR__ . "/../Views/auth/register.php";
    }

    public function logout(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_destroy();
        header("Location: /login");
        exit();
    }

    public static function requireLogin(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (empty($_SESSION["user"])) {
            header("Location: /login");
            exit();
        }
    }
}
