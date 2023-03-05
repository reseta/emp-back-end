<?php

use App\Migration\Migration;

final class Users extends Migration
{
    /**
     * Create users table
     */
    public function change(): void
    {
        $table = $this->table('users');
        $table
            ->addColumn('name', 'string')
            ->addColumn('email', 'string')
            ->addColumn('password', 'string')
            ->addColumn('role', 'enum', ['values' => 'admin, user', 'default' => 'user'])
            ->addColumn('created_at', 'datetime')
            ->addColumn('updated_at', 'datetime')
            ->addColumn('deleted_at', 'datetime')
            ->addIndex(['email'], ['unique' => true])
            ->create();
    }
}
