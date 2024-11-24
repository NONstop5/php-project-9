<?php

declare(strict_types=1);

namespace App\Service;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class HttpClient
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @throws GuzzleException
     */
    public function sendGetRequest(string $uri = '', array $options = []): array
    {
        $response = $this->client->get($uri, $options);

        return [
            'statusCode' => $response->getStatusCode(),
            'content' => $response->getBody()->getContents(),
        ];
    }
}
