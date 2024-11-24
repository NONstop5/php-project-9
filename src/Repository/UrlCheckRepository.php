<?php

declare(strict_types=1);

namespace App\Repository;

use Carbon\Carbon;
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
            order by uc.id desc
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(compact('id'));

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(int $urlId, array $urlCheckData): void
    {
        $sql = "
            INSERT INTO url_checks
            (url_id, status_code, created_at)
            VALUES
            (:urlId, :statusCode, :createdAt)
        ";

        $this->pdo
            ->prepare($sql)
            ->execute(
                [
                    'urlId' => $urlId,
                    'statusCode' => $urlCheckData['statusCode'],
                    'createdAt' => Carbon::now()->toDateTimeString(),
                ]
            );
        ;
    }
}
