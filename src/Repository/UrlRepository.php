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

    public function getUrlById(int $id): array
    {
        $sql = "
            select
                id,
                name,
                to_char(created_at, 'YYYY-MM-DD HH24:MI:SS TZ') as created_at
            from urls
            where id = :id
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(compact('id'));

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUrlByName(string $name): array
    {
        $sql = "
            select
                id,
                name,
                to_char(created_at, 'YYYY-MM-DD HH24:MI:SS TZ') as created_at
            from urls
            where name = :name
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(compact('name'));

        if ($stmt->rowCount() === 0) {
            return [];
        }

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUrlsCheckInfoList(): array
    {
        $sql = "
            select
                u.id,
                u.name as url,
                uc.created_at as url_check_date,
                uc.status_code as url_check_status_code
            from urls u
            left join url_checks uc on u.id = uc.url_id
            order by u.id desc
        ";

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
