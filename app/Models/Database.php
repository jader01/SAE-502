<?php
class Database
{
    /**
     * Singleton PDO connection instance.
     *
     * @var ?PDO
     */
    private static ?PDO $connection = null;

    /**
     * Return a shared PDO connection instance.
     *
     * Initializes a SQLite connection on first call using the config file.
     *
     * @return PDO Active PDO connection
     */
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
