<?php

declare(strict_types=1);

namespace App\Controller;

use Exception;
use GuzzleHttp\Exception\GuzzleException;
use InvalidArgumentException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class UrlCheckController extends BaseController
{
    /**
     * @throws Exception
     */
    public function checkUrlAction(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
        $urlId = (int)$args['id'];

        $url = $this->urlRepository->getUrlById($urlId);

        if ($url === []) {
            throw new InvalidArgumentException("Url with id {$urlId} not found");
        }

        try {
            $urlData = $this->urlChecker->getUrlData($url['name']);
            $urlCheckData = $this->urlChecker->getUrlCheckData($urlData);

            $this->urlCheckRepository->create($url['id'], $urlCheckData);

            $this->flash->addMessage('success', 'Страница успешно проверена');
        } catch (GuzzleException $e) {
            $this->flash->addMessage('warning', 'Не удалось произвести проверку страницы');
        }

        return $response
            ->withHeader('Location', sprintf('/urls/%s', $urlId))
            ->withStatus(302);
    }
}
