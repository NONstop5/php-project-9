<?php

declare(strict_types=1);

namespace App;

use App\Controller\IndexController;
use App\Controller\UrlController;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

class Routes
{
    public static function init(App $app): void
    {
        $app->get('/', [IndexController::class, 'indexAction'])->setName('index');

        $app->group('/urls', function (RouteCollectorProxy $group) {
            $group->get('', [UrlController::class, 'indexAction'])->setName('urls.index');

            $group->post('', [UrlController::class, 'createAction'])->setName('urls');

            $group->get('/{id}', [UrlController::class, 'getUrlAction'])->setName('get_url');
        });
    }
}
