<?php

declare(strict_types=1);

namespace App\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;

class IndexController extends BaseController
{
    /**
     * @throws Throwable
     */
    public function indexAction(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
        return $this->view->render($response, 'index.phtml');
    }
}
