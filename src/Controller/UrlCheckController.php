<?php

declare(strict_types=1);

namespace App\Controller;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;
use Valitron\Validator;

class UrlCheckController extends BaseController
{
    /**
     * @throws Throwable
     */
    public function checkUrlAction(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
        $urlId = (int)$args['id'];

        $urlData = $this->urlRepository->getUrlById($urlId);
        $this->urlCheckRepository->create($urlData['id'], ['createdAt' => Carbon::now()]);

        return $response
            ->withHeader('Location', sprintf('/urls/%s', $urlData['id']))
            ->withStatus(302);
    }
}
