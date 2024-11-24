<?php

declare(strict_types=1);

namespace App\Service;

use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

class UrlChecker
{
    /**
     * @throws GuzzleException
     */
    public function check(string $url): ResponseInterface
    {
        $httpClient = HttpClient::create($url);

        return $httpClient->request('GET');
    }
}
