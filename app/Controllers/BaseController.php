<?php

namespace App\Controllers;

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
     * @return void
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
}
