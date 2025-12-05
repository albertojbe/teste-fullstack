<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;


final class CreatePrestadoresServicos extends AbstractMigration
{
    public function change()
    {
        $table = $this->table('prestadores_servicos');

        $table
            ->addColumn('prestador_id', 'integer', ['null' => false])
            ->addColumn('servico_id', 'integer', ['null' => false])
            ->addColumn('created', 'datetime', [
                'default' => 'CURRENT_TIMESTAMP',
            ])
            ->addIndex(['prestador_id'])
            ->addIndex(['servico_id'])
            ->addIndex(['prestador_id', 'servico_id'], ['unique' => true])
            ->create();
    }
}

