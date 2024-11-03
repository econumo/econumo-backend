<?php

declare(strict_types=1);


namespace App\EconumoOneBundle\Domain\Service\Currency;

use App\EconumoOneBundle\Domain\Entity\ValueObject\CurrencyCode;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Repository\CurrencyRateRepositoryInterface;
use App\EconumoOneBundle\Domain\Repository\CurrencyRepositoryInterface;
use App\EconumoOneBundle\Domain\Service\DatetimeServiceInterface;
use App\EconumoOneBundle\Domain\Service\Dto\FullCurrencyRateDto;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\ORM\NoResultException;

readonly class CurrencyRateService implements CurrencyRateServiceInterface
{
    private CurrencyCode $baseCurrency;

    public function __construct(
        string $baseCurrency,
        private CurrencyRateRepositoryInterface $currencyRateRepository,
        private CurrencyRepositoryInterface $currencyRepository,
        private DatetimeServiceInterface $datetimeService
    ) {
        $this->baseCurrency = new CurrencyCode($baseCurrency);
    }

    /**
     * @inheritDoc
     */
    public function getCurrencyRates(\DateTimeInterface $dateTime): array
    {
        return $this->currencyRateRepository->getAll($dateTime);
    }

    /**
     * @inheritDoc
     */
    public function getLatestCurrencyRates(): array
    {
        try {
            $result = $this->currencyRateRepository->getAll();
        } catch (NoResultException $exception) {
            $result = [];
        }

        return $result;
    }

    public function getChanged(DateTimeInterface $lastUpdate): array
    {
        $currencyRates = $this->getLatestCurrencyRates();
        $result = [];
        foreach ($currencyRates as $currencyRate) {
            if ($currencyRate->getPublishedAt() > $lastUpdate) {
                $result[] = $currencyRate;
            }
        }

        return $result;
    }

    public function getAverageCurrencyRates(
        DateTimeInterface $startDate,
        DateTimeInterface $endDate
    ): array {
        $currentDateTime = $this->datetimeService->getCurrentDatetime();
        if ($currentDateTime < $startDate) {
            $diff = $endDate->getTimestamp() - $startDate->getTimestamp();
            $endDate = $currentDateTime;
            $startDate = DateTimeImmutable::createFromFormat('U', (string)($endDate->getTimestamp() - $diff));
        }

        $baseCurrency = $this->currencyRepository->getByCode($this->baseCurrency);
        $rates = $this->currencyRateRepository->getAverage($startDate, $endDate, $baseCurrency->getId());
        $data = [];
        foreach ($rates as $rate) {
            $data[$rate['currencyId']] = [
                'id' => new Id($rate['currencyId']),
                'rate' => $rate['rate'],
            ];
        }
        $currencies = $this->currencyRepository->getByIds(array_column($data, 'id'));
        $result = [];
        foreach ($currencies as $currency) {
            if (!isset($data[$currency->getId()->getValue()])) {
                continue;
            }

            $dto = new FullCurrencyRateDto();
            $dto->baseCurrencyId = $baseCurrency->getId();
            $dto->baseCurrencyCode = $baseCurrency->getCode();
            $dto->currencyId = $currency->getId();
            $dto->currencyCode = $currency->getCode();
            $dto->rate = (float)$data[$currency->getId()->getValue()]['rate'];
            $dto->date = $startDate;
            $result[] = $dto;
        }

        return $result;
    }
}
