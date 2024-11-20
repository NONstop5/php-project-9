<?php

declare(strict_types=1);

namespace App\Handlers;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Handlers\ErrorHandler;
use Slim\Interfaces\CallableResolverInterface;
use Slim\Views\PhpRenderer;
use Throwable;

class HttpErrorHandler extends ErrorHandler
{
    private PhpRenderer $view;

    public function __construct(
        CallableResolverInterface $callableResolver,
        ResponseFactoryInterface $responseFactory,
        PhpRenderer $view
    ) {
        $this->view = $view;

        parent::__construct($callableResolver, $responseFactory);
    }

    /**
     * @throws Throwable
     */
    protected function respond(): ResponseInterface
    {
        if ($this->statusCode === 404) {
            $response = $this->responseFactory->createResponse(404);

            return $this->view->render($response, '404.phtml');
        }

        $response = $this->responseFactory->createResponse(500);

        return $this->view->render($response, '500.phtml');
    }
}
