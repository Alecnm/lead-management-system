<?php

namespace App\Models;

use PDO;
use PDOException;

abstract class BaseModel
{
    protected PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * Generic method to execute a query with bindings.
     */
    protected function query(string $sql, array $params = []): bool|array|object
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    /**
     * Fetch a single record.
     */
    protected function fetch(string $sql, array $params = []): array|false
    {
        return $this->query($sql, $params)->fetch();
    }

    /**
     * Fetch multiple records.
     */
    protected function fetchAll(string $sql, array $params = []): array
    {
        return $this->query($sql, $params)->fetchAll();
    }

    /**
     * Insert data into a table.
     */
    public function insert(string $table, array $data): int
    {
        $columns = implode(',', array_keys($data));
        $placeholders = implode(',', array_map(fn($col) => ":$col", array_keys($data)));
        $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
        $this->query($sql, $data);

        return (int) $this->db->lastInsertId();
    }

    /**
     * Update records in a table.
     */
    public function update(string $table, array $data, string $where, array $whereParams): int
    {
        $set = implode(',', array_map(fn($col) => "$col = :$col", array_keys($data)));
        $sql = "UPDATE $table SET $set WHERE $where";
        $params = array_merge($data, $whereParams);

        return $this->query($sql, $params)->rowCount();
    }

    /**
     * Delete records from a table.
     */
    public function delete(string $table, string $where, array $params): int
    {
        $sql = "DELETE FROM $table WHERE $where";
        return $this->query($sql, $params)->rowCount();
    }
}
