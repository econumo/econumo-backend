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
    /**
     * @var string
     */
    private const CURRENCIES_URL = 'https://openexchangerates.org/api/currencies.json';

    public function __construct(private readonly HttpClientInterface $client)
    {
    }

    public function loadCurrencies(): array
    {
        $response = $this->client->request('GET', self::CURRENCIES_URL);
        $result = [];
        foreach (array_keys($response->toArray()) as $code) {
            $item = new CurrencyDto();
            $item->code = new CurrencyCode($code);
            try {
                $item->symbol = Currencies::getSymbol($code);
            } catch (MissingResourceException) {
                $item->symbol = $code;
            }

            $result[] = $item;
        }

        return $result;
    }
}
