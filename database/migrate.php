<?php
require_once __DIR__ . "/../app/Models/Database.php";
$db = Database::getConnection();

$migrationsPath = __DIR__ . "/migrations";
$appliedFile = __DIR__ . "/.applied_migrations";
$applied = file_exists($appliedFile)
    ? file($appliedFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES)
    : [];

$files = glob("$migrationsPath/*.sql");
sort($files);

foreach ($files as $file) {
    $name = basename($file);
    if (in_array($name, $applied)) {
        continue;
    }

    echo "Applying $name...\n";
    $db->exec(file_get_contents($file));

    file_put_contents($appliedFile, $name . PHP_EOL, FILE_APPEND);
}

echo "Migrations complete.\n";
