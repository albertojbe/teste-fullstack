<?php

use Phinx\Migration\AbstractMigration;

final class CreateServicos extends AbstractMigration
{
    public function change()
    {
        $table = $this->table('servicos');

        $table
            ->addColumn('nome', 'string', [
                'limit' => 150,
                'null'  => false,
            ])
            ->addColumn('descricao', 'text', [
                'null' => true,
            ])
            ->addColumn('valor', 'decimal', [
                'precision' => 10,
                'scale'     => 2,
                'null'      => false,
                'default'   => 0.00,
            ])
            ->addColumn('created', 'datetime', [
                'default' => 'CURRENT_TIMESTAMP',
            ])
            ->addColumn('modified', 'datetime', [
                'null' => true,
                'default' => null,
            ])
            ->addIndex(['nome'], ['unique' => true])
            ->create();
    }
}
