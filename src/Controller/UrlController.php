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
        $params = $request->getParsedBody();

        $urlName = $params['url']['name'];

        $urlData = $this->urlRepository->getUrlByName($urlName);

        if (count($urlData) > 0) {
            $this->flash->addMessage('success', 'Страница уже существует');

            return $response
                ->withHeader('Location', sprintf('/urls/%s', $urlData['id']))
                ->withStatus(302);
        }

        $this->urlRepository->insert($urlName);

        $this->flash->addMessage('success', 'Страница успешно добавлена');

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
