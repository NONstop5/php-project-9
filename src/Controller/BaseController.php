<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\UrlCheckRepository;
use App\Repository\UrlRepository;
use PDO;
use Psr\Container\ContainerInterface;
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

    public function __construct(
        ContainerInterface $container,
        PDO $db,
        PhpRenderer $view,
        Messages $flash,
        UrlRepository $urlRepository,
        UrlCheckRepository $urlCheckRepository
    ) {
        $this->container = $container;
        $this->db = $db;
        $this->view = $view;
        $this->flash = $flash;
        $this->urlRepository = $urlRepository;
        $this->urlCheckRepository = $urlCheckRepository;
    }
}
