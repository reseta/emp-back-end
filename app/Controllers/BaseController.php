<?php

namespace App\Controllers;

use App\Models\User;
use Psr\Container\ContainerInterface;

abstract class BaseController
{
    /**
     * The container instance.
     *
     * @var ContainerInterface
     */
    protected ContainerInterface $container;

    /**
     * Set up controllers to have access to the container.
     *
     * @param ContainerInterface $container
     *
     * @return void
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Check if user can edit
     *
     * @param array $token
     * @param int $id
     *
     * @return bool
     */
    public function checkEditPermissions(array $token, int $id): bool
    {
        $user = User::query()->find($token['user']->id);

        if ($user) {
            return $user->id === $id;
        }

        return false;
    }
}
