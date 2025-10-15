<?php
class Database
{
    private static $connection;

    public static function getConnection(): PDO
    {
        if (!self::$connection) {
            $config = include __DIR__ . "/../../config/config.php";
            $dbPath = $config["db_path"];

            self::$connection = new PDO("sqlite:" . $dbPath);
            self::$connection->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION,
            );
        }
        return self::$connection;
    }
}
