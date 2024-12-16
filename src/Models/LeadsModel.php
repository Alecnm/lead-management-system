<?php

namespace App\Models;

class LeadsModel extends BaseModel
{
    protected string $table = 'leads';

    /**
     * Find a lead by its ID.
     */
    public function find(int $id): array|false
    {
        return $this->fetch("SELECT * FROM {$this->table} WHERE id = :id", ['id' => $id]);
    }

    /**
     * Get all leads.
     */
    public function all(): array
    {
        return $this->fetchAll("SELECT * FROM {$this->table}");
    }

    /**
     * Create a new lead.
     */
    public function create(array $data): int
    {
        return $this->insert($this->table, $data);
    }

    /**
     * Update a lead.
     */
    public function updateLead(int $id, array $data): int
    {
        return $this->update($this->table, $data, 'id = :id', ['id' => $id]);
    }

    /**
     * Delete a lead.
     */
    public function deleteLead(int $id): int
    {
        return $this->delete($this->table, 'id = :id', ['id' => $id]);
    }
}
