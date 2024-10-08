<?php

declare(strict_types=1);

namespace App;

use Exception;
use PDO;
use RuntimeException;

/**
 * Создание класса Connection
 */
final class Connection
{
    private static ?Connection $conn = null;

    /**
     * @throws Exception
     */
    public function connect(): PDO
    {
        $params = parse_ini_file(__DIR__ . '/../database.ini');
        //$databaseUrl = parse_url('postgresql://root:9xACsxSiWNVPzqfhCBQFOTZRaJS97MLZ@dpg-cr1j18dds78s739t0r20-a.frankfurt-postgres.render.com/project9_kmgu');

        if ($params === false) {
            throw new RuntimeException("Error reading database configuration file");
        }

        $conStr = sprintf(
            "pgsql:host=%s;port=%d;dbname=%s;user=%s;password=%s",
            $params['host'],
            $params['port'],
            $params['database'],
            $params['user'],
            $params['password']
        );

        $pdo = new PDO($conStr);
        dd($pdo);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $pdo;
    }

    public static function get()
    {
        if (null === static::$conn) {
            static::$conn = new self();
        }

        return static::$conn;
    }

    protected function __construct()
    {
    }
}
