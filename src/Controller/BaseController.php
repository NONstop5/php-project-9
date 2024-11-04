<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\UrlCheckRepository;
use App\Repository\UrlRepository;
use PDO;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Slim\Flash\Messages;
use Slim\Views\PhpRenderer;

class BaseController
{
    protected ContainerInterface $container;
    protected PDO $db;
    protected PhpRenderer $view;
    protected Messages $flash;
    protected UrlRepository $urlRepository;
    protected UrlCheckRepository $urlCheckRepository;

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->db = $container->get('db');
        $this->view = $container->get('view');
        $this->flash = $container->get('flash');
        $this->urlRepository = $container->get('UrlRepository');
        $this->urlCheckRepository = $container->get('UrlCheckRepository');
    }
}
