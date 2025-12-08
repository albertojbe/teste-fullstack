<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddSobrenomePrestadores extends AbstractMigration
{
    public function change()
    {
        $table = $this->table('prestadores');

        $table
            ->addColumn('sobrenome', 'string', [
                'limit' => 150,
                'null' => false,
            ])
            ->save();
    }
}
