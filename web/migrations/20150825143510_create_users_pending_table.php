<?php

use Phinx\Migration\AbstractMigration;

class CreateUsersPendingTable extends AbstractMigration
{

    public function change()
    {
        $users_pending = $this->table('users_pending');
        $users_pending->addColumn('token', 'string')
            ->addColumn('user_id', 'integer')
            ->addForeignKey('user_id', 'users', 'id', ['delete' => 'cascade', 'update' => 'cascade'])
            ->addColumn('created_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated_at', 'datetime', ['null' => true])
            ->save();
    }
}
