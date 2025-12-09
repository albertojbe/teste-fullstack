<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreatePrestadores extends AbstractMigration
{
    public function change()
    {
        $table = $this->table('prestadores');

        $table
            ->addColumn('nome', 'string', [
                'limit' => 150,
                'null' => false,
            ])
            ->addColumn('telefone', 'string', [
                'limit' => 20,
                'null' => true,
            ])
            ->addColumn('email', 'string', [
                'limit' => 150,
                'null' => true,
            ])
            ->addColumn('foto', 'string', [
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('created', 'datetime', [
                'default' => 'CURRENT_TIMESTAMP',
            ])
            ->addColumn('modified', 'datetime', [
                'null' => true,
            ])
            ->addIndex(['email'], ['unique' => true])
            ->create();
    }
}

