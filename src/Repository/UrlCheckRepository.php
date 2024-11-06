<?php

declare(strict_types=1);

namespace App\Repository;

use PDO;

class UrlCheckRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getUrlChecks(int $id): array
    {
        $sql = "
            select
                uc.*
            from urls u
            left join url_checks uc on u.id = uc.url_id
            where u.id = :id
            order by u.id desc
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(compact('id'));

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
