<?php

declare(strict_types=1);

namespace App\Infrastructure\OpenExchangeRates;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class CurrencyExchangeUsageService
{
    private const CURRENCIES_URL = 'https://openexchangerates.org/api/usage.json';
    private HttpClientInterface $client;
    private string $token;

    public function __construct(HttpClientInterface $client, string $token)
    {
        $this->client = $client;
        $this->token = $token;
    }

    public function getUsage(): array
    {
        $response = $this->client->request('GET', self::CURRENCIES_URL . '?app_id=' . $this->token);
        return $response->toArray();
    }
}
