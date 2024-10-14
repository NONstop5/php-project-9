<?php

declare(strict_types=1);

namespace App;

use Exception;
use PDO;

final class Connection
{
    private const DATABASE_DEFAULT_PORT = 5432;

    /**
     * @throws Exception
     */
    public static function create(string $databaseUrl): PDO
    {
        $databaseParams = parse_url($databaseUrl);

        $host = $databaseParams['host'] ?? '';
        $port = $databaseParams['port'] ?? self::DATABASE_DEFAULT_PORT;
        $user = $databaseParams['user'] ?? '';
        $password = $databaseParams['pass'] ?? '';
        $dbName = ltrim($databaseParams['path'] ?? '', '/');

        $dsn = sprintf(
            "pgsql:host=%s;port=%d;dbname=%s;user=%s;password=%s",
            $host,
            $port,
            $dbName,
            $user,
            $password
        );

        return new PDO($dsn, $user, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    }
}
