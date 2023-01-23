<?php

declare(strict_types=1);

namespace App\Infrastructure\OpenExchangeRates;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class CurrencyExchangeUsageService
{
    /**
     * @var string
     */
    private const CURRENCIES_URL = 'https://openexchangerates.org/api/usage.json';

    public function __construct(private readonly HttpClientInterface $client, private readonly string $token)
    {
    }

    public function getUsage(): array
    {
        $response = $this->client->request('GET', self::CURRENCIES_URL . '?app_id=' . $this->token);
        return $response->toArray();
    }
}
