<?php

declare(strict_types=1);

namespace App;

use App\Controller\IndexController;
use App\Controller\UrlCheckController;
use App\Controller\UrlController;
use DI\Container;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

class Routes
{
    /**
     * @param App<Container> $app
     */
    public static function init(App $app): void
    {
        $app->get('/', [IndexController::class, 'indexAction'])->setName('index');

        $app->group('/urls', function (RouteCollectorProxy $group) {
            $group->get('', [UrlController::class, 'indexAction'])->setName('urls.index');

            $group->post('', [UrlController::class, 'createAction'])->setName('urls.create');

            $group->get('/{id:[0-9]+}', [UrlController::class, 'getUrlAction'])->setName('urls.show');

            $group->post('/{id:[0-9]+}/checks', [UrlCheckController::class, 'checkUrlAction'])->setName('urls.check');
        });
    }
}
