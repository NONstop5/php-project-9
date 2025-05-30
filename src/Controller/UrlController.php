<?php

declare(strict_types=1);

namespace App\Controller;

use Illuminate\Support\Arr;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;
use Valitron\Validator;

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
        $urls = $this->urlRepository->getUrls();
        $urlsCheckInfo = $this->urlCheckRepository->getUrlsLastChecks();

        $urlsData = array_map(
            static function (array $url, array $urlCheckInfo) {
                $urlId = $url['id'];

                return [
                    'id' => $urlId,
                    'url' => $url['name'],
                    'url_check_date' => $urlCheckInfo['url_check_date'],
                    'url_check_status_code' => $urlCheckInfo['url_check_status_code'],
                ];
            },
            $urls,
            $urlsCheckInfo
        );

        return $this->view->render($response, 'urls.phtml', compact('urlsData'));
    }

    /**
     * @throws Throwable
     */
    public function createAction(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
        /** @var array $params */
        $params = $request->getParsedBody();

        $urlName = htmlspecialchars($params['url']['name']);

        $validator = new Validator(['url' => $urlName]);
        $validator->rule('required', 'url')->message('URL не должен быть пустым');
        $validator->rule('url', 'url')->message('Некорректный URL');

        if (!$validator->validate()) {
            $errors = $validator->errors('url');
            $error = is_array($errors) ? Arr::first($errors) : null;

            return $this->view->render($response, 'index.phtml', compact('error'))->withStatus(422);
        }

        /** @var array{scheme: string, host: string} $parsedUrl */
        $parsedUrl = parse_url($urlName);
        $normalizedUrl = $parsedUrl['scheme'] . '://' . $parsedUrl['host'];

        $urlData = $this->urlRepository->getUrlByName($normalizedUrl);

        if (count($urlData) > 0) {
            $this->flash->addMessage('success', 'Страница уже существует');

            return $response
                ->withHeader('Location', sprintf('/urls/%s', $urlData['id']))
                ->withStatus(302)
            ;
        }

        $this->urlRepository->create($normalizedUrl);
        $urlData = $this->urlRepository->getUrlByName($normalizedUrl);

        $this->flash->addMessage('success', 'Страница успешно добавлена');

        return $response
            ->withHeader('Location', sprintf('/urls/%s', $urlData['id']))
            ->withStatus(302)
        ;
    }

    /**
     * @throws Throwable
     */
    public function getUrlAction(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
        $urlId = (int)$args['id'];

        $urlData = $this->urlRepository->getUrlById($urlId);
        $urlCheckData = $this->urlCheckRepository->getUrlChecks($urlId);

        $data = [
            'urlData' => $urlData,
            'urlCheckData' => $urlCheckData,
        ];

        return $this->view->render($response, 'url.phtml', compact('data'));
    }
}
