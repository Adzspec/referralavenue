<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class AdtractionApiService
{
    protected string $baseUrl;
    protected string $token;

    public function __construct($token = null)
    {
        $this->baseUrl = 'https://api.adtraction.com/v2';
        $this->token = $token;
    }

    public function request(string $endpoint, array $queryParams = [], string $method = 'GET', array $body = [],$version = 'v2'): array
    {
        if ($version === 'v3'){
            $this->baseUrl = 'https://api.adtraction.com/v3';
        }
        $queryParams['token'] = $this->token;
        $fullUrl = "{$this->baseUrl}{$endpoint}";
        try {
            $http = Http::withHeaders([
                'Content-Type' => 'application/json;charset=UTF-8',
            ]);

            $options = [
                'query' => $queryParams,
            ];

            if (strtoupper($method) === 'POST') {
                $options['body'] = json_encode($body); // 🔥 send raw JSON like cURL
            }
            $response = $http->send($method, $fullUrl, $options);

            if (!$response->successful()) {
                throw new \Exception("Adtraction API error: " . $response->status() . ' - ' . $response->body());
            }

            return $response->json();
        } catch (\Throwable $e) {
            \Log::error('Adtraction API request failed', [
                'url' => $fullUrl,
                'method' => $method,
                'query' => $queryParams,
                'body' => $body,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }


}
