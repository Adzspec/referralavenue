<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class AddrevenueApiService
{
    protected string $baseUrl;
    protected $token;

    public function __construct($token)
    {
        $this->baseUrl = 'https://addrevenue.io/api/v2/';
        $this->token = $token;
    }

    public function request(
        string $endpoint,
        array $queryParams = [],
        string $method = 'GET',
        array $body = []
    ): array
    {
        $fullUrl = "{$this->baseUrl}{$endpoint}";
        try {
            $http = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->token,
                'Content-Type'  => 'application/json;charset=UTF-8',
            ]);

            $options = [
                'query' => $queryParams,
            ];

            if (strtoupper($method) === 'POST') {
                $options['body'] = json_encode($body);
            }
            $response = $http->send($method, $fullUrl, $options);

            if (!$response->successful()) {
                throw new \Exception("Addrevenue API error: " . $response->status() . ' - ' . $response->body());
            }

            return $response->json();
        } catch (\Throwable $e) {
            \Log::error('Addrevenue API request failed', [
                'url'    => $fullUrl,
                'method' => $method,
                'query'  => $queryParams,
                'body'   => $body,
                'error'  => $e->getMessage()
            ]);
            throw $e;
        }
    }
}
