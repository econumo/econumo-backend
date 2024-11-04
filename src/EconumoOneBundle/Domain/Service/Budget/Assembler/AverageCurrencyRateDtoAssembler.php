<?php

declare(strict_types=1);


namespace App\EconumoOneBundle\Domain\Service\Budget\Assembler;


use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Repository\CurrencyRateRepositoryInterface;
use App\EconumoOneBundle\Domain\Service\Budget\Dto\AverageCurrencyRateDto;
use App\EconumoOneBundle\Domain\Service\Currency\CurrencyServiceInterface;
use DateTimeInterface;

readonly class AverageCurrencyRateDtoAssembler
{
    public function __construct(
        private CurrencyServiceInterface $currencyService,
        private CurrencyRateRepositoryInterface $currencyRateRepository,
    ) {
    }

    /**
     * @param DateTimeInterface $periodStart
     * @param DateTimeInterface $periodEnd
     * @param Id[] $currenciesIds
     * @return AverageCurrencyRateDto[]
     */
    public function assemble(
        DateTimeInterface $periodStart,
        DateTimeInterface $periodEnd,
        array $currenciesIds
    ): array {
        $baseCurrency = $this->currencyService->getBaseCurrency();
        $supportedCurrencyIds = array_map(fn(Id $id) => $id->getValue(), $currenciesIds);
        $currencyRates = $this->currencyRateRepository->getAverage($periodStart, $periodEnd, $baseCurrency->getId());

        $result = [];
        foreach ($currencyRates as $item) {
            if (in_array($item['currencyId'], $supportedCurrencyIds, true)) {
                $result[] = new AverageCurrencyRateDto(new Id($item['currencyId']), round(floatval($item['rate']), 8));
            }
        }

        return $result;
    }
}
