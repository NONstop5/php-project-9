<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Connection;
use App\Migrations;
use App\Routes;
use DI\Container;
use Dotenv\Dotenv;
use Slim\Factory\AppFactory;
use Slim\Middleware\MethodOverrideMiddleware;
use Slim\Views\PhpRenderer;

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->safeLoad();

if (!isset($_ENV['DATABASE_URL'])) {
    throw new Exception('Missing ENV variable "DATABASE_URL"!');
}

$conn = Connection::create($_ENV['DATABASE_URL']);
(new Migrations())->run($conn);

session_start();

$container = new Container();

$container->set('db', fn () => $conn);
$container->set('view', fn () => new PhpRenderer(__DIR__ . '/../templates/php-view'));

$app = AppFactory::createFromContainer($container);

$app->addRoutingMiddleware();
$errorMiddleware = $app->addErrorMiddleware(true, true, true);
$app->add(MethodOverrideMiddleware::class);

Routes::init($app);

$app->run();
