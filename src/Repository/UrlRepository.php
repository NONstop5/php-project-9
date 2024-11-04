<?php

declare(strict_types=1);

namespace App\Repository;

use PDO;

class UrlRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getList(): array
    {
        $sql = "SELECT * FROM urls";

        $stmt = $this->pdo->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insert(string $name): void
    {
        $sql = "INSERT INTO urls (name) VALUES (:name)";

        $this->pdo
            ->prepare($sql)
            ->execute(compact('name'))
        ;
    }
}
