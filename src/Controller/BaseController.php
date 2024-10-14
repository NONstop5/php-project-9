<?php

declare(strict_types=1);

namespace App\Controller;

use PDO;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Slim\Views\PhpRenderer;

class BaseController
{
    protected ContainerInterface $container;
    protected PDO $db;
    protected PhpRenderer $view;

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->db = $container->get('db');
        $this->view = $container->get('view');
    }
}
