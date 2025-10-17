<?php
require_once __DIR__ . "/../Models/User.php";

class AuthController
{
    /**
     * Handle user login.
     *
     * On GET: displays the login form.
     * On POST: verifies credentials and initializes session.
     *
     * @return void
     */
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

    /**
     * Log the current user out.
     *
     * Clears session data and redirects to the login page.
     *
     * @return void
     */
    public function logout(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_destroy();
        header("Location: /login");
        exit();
    }

    /**
     * Require an authenticated session.
     *
     * If not logged in, redirects to the login page.
     *
     * @return void
     */
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
