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
$dotenv->load();
$dotenv->required(['APP_ENV', 'DATABASE_URL'])->notEmpty();

$conn = Connection::create($_ENV['DATABASE_URL']);
(new Migrations())->run($conn);

// Старт PHP сессии
session_start();

$container = new Container();

$container->set(PDO::class, fn() => $conn);
$container->set(Messages::class, fn() => new Messages());
$container->set(PhpRenderer::class, fn() => new PhpRenderer(
    __DIR__ . '/../templates/php-view',
    ['flashMessages' => $container->get(Messages::class)]
));

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

if ($_ENV['APP_ENV'] === 'prod') {
    $errorMiddleware->setDefaultErrorHandler($httpErrorHandler);
}

$app->add(MethodOverrideMiddleware::class);

Routes::init($app);

$app->run();
