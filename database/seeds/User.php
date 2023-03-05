<?php

use Phinx\Seed\AbstractSeed;

class User extends AbstractSeed
{
    /**
     * Seed user table
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'John Doe',
                'email' => 'john@domain.com',
                'password' => '$2y$10$BlsdOkb7yWKdtq4pUhMcsud20yBmdz3Q.xhCNds0132GQKO1pdreO',
                'role' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $posts = $this->table('users');
        $posts->insert($data)
            ->saveData();
    }
}
