<?php

declare(strict_types=1);

namespace App\Repository;

use Carbon\Carbon;
use InvalidArgumentException;
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
                created_at
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
                last_check.created_at as url_check_date,
                last_check.status_code as url_check_status_code
            from urls u
            left join lateral (
                select url_id, created_at, status_code
                from url_checks
                where url_id = u.id
                order by id desc
                limit 1
            ) as last_check on u.id = last_check.url_id
            order by u.id desc
        ";

        $stmt = $this->pdo->query($sql);

        if ($stmt === false) {
            throw new InvalidArgumentException();
        }

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(string $name): void
    {
        $sql = "
            INSERT INTO urls
                (name, created_at)
            VALUES
                (:name, :createdAt)
        ";

        $this->pdo
            ->prepare($sql)
            ->execute([
                'name' => $name,
                'createdAt' => Carbon::now()->toDateTimeString(),
            ])
        ;
    }
}
