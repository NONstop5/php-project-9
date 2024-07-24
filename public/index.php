<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Connection;
use DI\Container;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Middleware\MethodOverrideMiddleware;
use Slim\Views\PhpRenderer;

// Старт PHP сессии
session_start();

$container = new Container();
$container->set('renderer', function () {
    // Параметром передается базовая директория, в которой будут храниться шаблоны
    return new PhpRenderer(__DIR__ . '/../templates/php-view');
});

//try {
//    Connection::get()->connect();
//    echo 'A connection to the PostgreSQL database sever has been established successfully.';
//} catch (PDOException $e) {
//    echo $e->getMessage();
//}

$app = AppFactory::createFromContainer($container);

$router = $app->getRouteCollector()->getRouteParser();

$app->addRoutingMiddleware();
$errorMiddleware = $app->addErrorMiddleware(true, true, true);
$app->add(MethodOverrideMiddleware::class);

$app->get('/', function (Request $request, Response $response) {
    $viewData = [
        'name' => 'John',
    ];

    return $this->get('renderer')->render($response, 'index.phtml', $viewData);
})->setName('index');

$app->get('/urls', function (Request $request, Response $response) {
    $viewData = [
        'name' => 'John',
    ];

    return $this->get('renderer')->render($response, 'sites.phtml', $viewData);
})->setName('urls');

$app->get('/urls/{id}', function (Request $request, Response $response, array $args) {
    return $this->get('renderer')->render($response, 'sites.phtml', $args);
})->setName('url_item');

// Run app
$app->run();
