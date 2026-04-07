<?php

declare(strict_types=1);

namespace App\Database;

use PDOStatement;
use PDO;
use PDOException;
use RuntimeException;

class Database
{
    private PDO $connection;
    private static ?self $instance = null;

    private ?PDOStatement $statement = null;

    private function __construct()
    {
        $dsn = "pgsql:host={$_ENV['DB_HOST']};port={$_ENV['DB_PORT']};dbname={$_ENV['DB_NAME']}";

        try {
            $this->connection = new PDO(
                $dsn,
                $_ENV['DB_USER'],
                $_ENV['DB_PASS'],
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
        } catch (PDOException $e) {
            throw new RuntimeException("Error Processing Request: " . $e->getMessage());
        }
    }

    private function __clone(): void
    {
    }

    public static function getInstance(): self
    {
        if (!isset(self::$instance)) {
            return self::$instance = new self();
        }
        return self::$instance;
    }

    public function getPdo(): PDO
    {
        return $this->connection;
    }

    public function query($query, array $params = []): self
    {
        $this->statement = $this->connection->prepare($query);

        $this->statement->execute($params);

        return $this;
    }

    public function getLastInsertId(?string $name = null): string
    {
        return $this->getPdo()->lastInsertId($name);
    }

    public function fetch(): mixed
    {
        return $this->statement->fetch();
    }

    public function fetchAll(): array
    {
        return $this->statement->fetchAll();
    }
    public function countRows(): int
    {
        return $this->statement->rowCount();
    }

    public function beginTransaction(): void
    {
        $this->getPdo()->beginTransaction();
    }

    public function commit(): void
    {
        $this->getPdo()->commit();
    }
    public function rollback(): void
    {
        $this->getPdo()->rollBack();
    }
}


