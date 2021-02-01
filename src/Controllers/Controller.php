<?php

namespace App\Controllers;

use DI\Container;

abstract class Controller
{

    protected Container $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function __get($name)
    {
        return $this->container->get($name);
    }

}