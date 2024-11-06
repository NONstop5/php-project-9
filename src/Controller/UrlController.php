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
        $urls = $this->urlRepository->getUrlsCheckInfoList();

        return $this->view->render($response, 'urls.phtml', compact('urls'));
    }

    /**
     * @throws Throwable
     */
    public function createAction(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
        $url = $request->getParsedBody()['url']['name'];

        $this->urlRepository->insert($url);

        return $response
            ->withHeader('Location', '/urls')
            ->withStatus(302);
    }

    /**
     * @throws Throwable
     */
    public function getUrlAction(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
        $urlId = (int)$request->getAttribute('id');

        $urlData = $this->urlRepository->getUrlById($urlId);
        $urlCheckData = $this->urlCheckRepository->getUrlChecks($urlId);

        $data = [
            'urlData' => $urlData,
            'urlCheckData' => $urlCheckData,
        ];

        return $this->view->render($response, 'url.phtml', compact('data'));
    }
}
