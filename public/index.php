<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Connection;
use App\Handlers\HttpErrorHandler;
use App\Migrations;
use App\Routes;
use DI\Container;
use Dotenv\Dotenv;
use Slim\Factory\AppFactory;
use Slim\Flash\Messages;
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

$container->set(PDO::class, fn() => $conn);
$container->set(PhpRenderer::class, fn() => new PhpRenderer(__DIR__ . '/../templates/php-view'));
$container->set(Messages::class, fn() => new Messages());

$app = AppFactory::createFromContainer($container);

$callableResolver = $app->getCallableResolver();
$responseFactory = $app->getResponseFactory();

// Определяем пользовательский обработчик ошибок
$httpErrorHandler = new HttpErrorHandler(
    $callableResolver,
    $responseFactory,
    $container->get(PhpRenderer::class)
);

// Добавление промежуточного ПО маршрутизации
$app->addRoutingMiddleware();

// Добавляем промежуточное ПО обработки ошибок
$errorMiddleware = $app->addErrorMiddleware(true, true, true);
$errorMiddleware->setDefaultErrorHandler($httpErrorHandler);

$app->add(MethodOverrideMiddleware::class);

Routes::init($app);

$app->run();
