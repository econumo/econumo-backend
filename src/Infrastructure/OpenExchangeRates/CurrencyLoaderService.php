<?php

declare(strict_types=1);

namespace App\Infrastructure\OpenExchangeRates;

use App\Domain\Entity\ValueObject\CurrencyCode;
use App\Domain\Service\Currency\CurrencyLoaderServiceInterface;
use App\Domain\Service\Dto\CurrencyDto;
use Symfony\Component\Intl\Currencies;
use Symfony\Component\Intl\Exception\MissingResourceException;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CurrencyLoaderService implements CurrencyLoaderServiceInterface
{
    private const CURRENCIES_URL = 'https://openexchangerates.org/api/currencies.json';
    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function loadCurrencies(): array
    {
        $response = $this->client->request('GET', self::CURRENCIES_URL);
        $result = [];
        foreach ($response->toArray() as $code => $name) {
            $item = new CurrencyDto();
            $item->code = new CurrencyCode($code);
            try {
                $item->symbol = Currencies::getSymbol($code);
            } catch (MissingResourceException $exception) {
                $item->symbol = $code;
            }
            $result[] = $item;
        }

        return $result;
    }
}
