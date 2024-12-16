<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateLeadsTable extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change(): void
    {
        $table = $this->table('leads', ['id' => 'id']);
        $table
            ->addColumn('name', 'string', ['limit' => 50, 'null' => false])
            ->addColumn('email', 'string', ['limit' => 100, 'null' => false])
            ->addColumn('phone', 'string', ['limit' => 20, 'null' => true])
            ->addColumn('source', 'enum', [
                'values' => ['facebook', 'google', 'linkedin', 'manual'],
                'null' => false,
            ])
            ->addColumn('created_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->addIndex(['email'], ['unique' => true])
            ->create();
    }
}
