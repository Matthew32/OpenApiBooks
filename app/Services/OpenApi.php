<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class OpenApi
{
    private const URL = 'https://openlibrary.org';

    public function search($payload)
    {
        $query = $payload['query'];
        $page = $payload['page'];
        $limitPerPage = $payload['limitPerPage'];
        $url = self::URL . '/search';
        $requestResponse = Http::acceptJson()
            ->get(
                $url,
                [
                    'q' => $query,
                    'page' => $page,
                    'limit' => $limitPerPage
                ]
            );
        if ($requestResponse->ok()) {
            return $requestResponse->json();
        }
        throw new \Exception('Error on query');
    }
}
