<?php

use \App\Migration\Migration;

final class Posts extends Migration
{
    /**
     * Create posts table
     */
    public function change(): void
    {
        $table = $this->table('posts');
        $table
            ->addColumn('title', 'string')
            ->addColumn('content', 'text')
            ->addColumn('image', 'string')
            ->addColumn('user_id', 'integer', ['signed' => false])
            ->addColumn('created_at', 'datetime')
            ->addColumn('updated_at', 'datetime')
            ->addColumn('deleted_at', 'datetime')
            ->create();
    }
}
