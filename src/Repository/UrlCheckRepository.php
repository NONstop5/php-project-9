<?php

declare(strict_types=1);

namespace App\Repository;

use Carbon\Carbon;
use InvalidArgumentException;
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

    public function getUrlsLastChecks(): array
    {
        $sql = "
            SELECT
                DISTINCT url_id,
                MAX(created_at) as url_check_date,
                status_code as url_check_status_code
            FROM url_checks
            GROUP BY url_id, status_code
            ORDER BY url_id DESC, MAX(created_at) DESC
        ";

        $stmt = $this->pdo->query($sql);

        if ($stmt === false) {
            throw new InvalidArgumentException();
        }

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(int $urlId, array $urlCheckData): void
    {
        $sql = "
            INSERT INTO url_checks
            (url_id, status_code, h1, title, description, created_at)
            VALUES
            (:urlId, :statusCode, :h1, :title, :description, :createdAt)
        ";

        $this->pdo
            ->prepare($sql)
            ->execute(
                [
                    'urlId' => $urlId,
                    'statusCode' => $urlCheckData['statusCode'],
                    'h1' => $urlCheckData['h1'],
                    'title' => $urlCheckData['title'],
                    'description' => $urlCheckData['description'],
                    'createdAt' => Carbon::now()->toDateTimeString(),
                ]
            );
        ;
    }
}
