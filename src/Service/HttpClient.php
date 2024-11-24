<?php

declare(strict_types=1);

namespace App\Service;

use GuzzleHttp\Client;

class HttpClient
{
    public static function create(string $url): Client
    {
        return new Client([
            // Base URI is used with relative requests
            'base_uri' => $url,
            // You can set any number of default request options.
            'timeout' => 2.0,
        ]);
    }
}
