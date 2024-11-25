<?php

declare(strict_types=1);

namespace App\Service;

use DiDom\Document;
use GuzzleHttp\Exception\GuzzleException;

class UrlChecker
{
    private HttpClient $httpClient;

    public function __construct(HttpClient $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @throws GuzzleException
     */
    public function getUrlData(string $url): array
    {
        return $this->httpClient->sendGetRequest($url);
    }

    public function getUrlCheckData(array $urlData): array
    {
        $content = $urlData['content'];
        $doc = new Document($content);
        $h1 = optional($doc->first('h1'))->text();
        $title = optional($doc->first('title'))->text();
        $description = optional($doc->first('meta[name="description"]'))->getAttribute('content');

        return [
            'statusCode' => $urlData['statusCode'],
            'h1' => $h1 === null ? null : trim($h1),
            'title' => $title === null ? null : trim($title),
            'description' => $description === null ? null : trim($description),
        ];
    }
}
