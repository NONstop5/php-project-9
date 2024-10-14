<?php

declare(strict_types=1);

namespace App\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;

class UrlController extends BaseController
{
    /**
     * @throws Throwable
     */
    public function indexAction(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
        $data = [
            'name' => 'John',
        ];

        return $this->view->render($response, 'urls.phtml', $data);
    }

    /**
     * @throws Throwable
     */
    public function getUrlAction(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
        $data = [
            'name' => 'John',
        ];

        return $this->view->render($response, 'urls.phtml', $data);
    }

}
