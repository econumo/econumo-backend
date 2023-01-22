<?php

declare(strict_types=1);

namespace App\Infrastructure\OpenExchangeRates;

use App\Domain\Entity\Currency;
use App\Domain\Entity\ValueObject\CurrencyCode;
use App\Domain\Service\Currency\CurrencyRatesLoaderServiceInterface;
use App\Domain\Service\Currency\CurrencyServiceInterface;
use App\Domain\Service\Dto\CurrencyRateDto;
use DateTimeInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CurrencyRatesLoaderService implements CurrencyRatesLoaderServiceInterface
{
    /**
     * @var string
     */
    private const CURRENCY_RATES_LATEST_URL = 'https://openexchangerates.org/api/latest.json';

    /**
     * @var string
     */
    private const CURRENCY_RATES_HISTORICAL_URL = 'https://openexchangerates.org/api/historical/:date.json';

    private HttpClientInterface $client;

    private CurrencyServiceInterface $currencyService;

    private string $token;

    public function __construct(
        string $token,
        HttpClientInterface $client,
        CurrencyServiceInterface $currencyService
    ) {
        $this->client = $client;
        $this->currencyService = $currencyService;
        $this->token = $token;
    }

    public function loadCurrencyRates(DateTimeInterface $date): array
    {
        $datasourceUrl = str_replace(':date', $date->format('Y-m-d'), self::CURRENCY_RATES_HISTORICAL_URL);
        if ($date->format('Y-m-d') === date('Y-m-d')) {
            $datasourceUrl = self::CURRENCY_RATES_LATEST_URL;
        }

        $baseCurrency = $this->currencyService->getBaseCurrency();
        $response = $this->client->request('GET', $datasourceUrl, [
            'query' => [
                'app_id' => $this->token,
                'base' => $baseCurrency->getCode()->getValue(),
                'symbols' => $this->getCurrenciesList($this->currencyService->getAvailableCurrencies()),
            ]
        ]);
        $result = [];
        $data = $response->toArray();
        $updatedAt = \DateTimeImmutable::createFromFormat('U', (string)$data['timestamp']);
        $currencyRateDate = \DateTime::createFromFormat('Y-m-d', $updatedAt->format('Y-m-d'));
        $currencyRateDate->setTime(0, 0, 0, 0);

        $baseCode = new CurrencyCode($data['base']);
        foreach ($data['rates'] as $code => $rate) {
            $item = new CurrencyRateDto();
            $item->code = new CurrencyCode($code);
            $item->base = $baseCode;
            $item->rate = (float)$rate;
            $item->date = $currencyRateDate;
            $result[] = $item;
        }

        return $result;
    }

    /**
     * @param Currency[] $currencies
     * @return string
     */
    private function getCurrenciesList(array $currencies): string
    {
        $result = [];
        foreach ($currencies as $currency) {
            $result[] = $currency->getCode()->getValue();
        }

        $result = array_unique($result);
        return implode(',', $result);
    }
}
