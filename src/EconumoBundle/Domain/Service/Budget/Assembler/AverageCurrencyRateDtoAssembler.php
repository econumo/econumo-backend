<?php

declare(strict_types=1);


namespace App\EconumoBundle\Domain\Service\Budget\Assembler;


use App\EconumoBundle\Domain\Entity\ValueObject\Id;
use App\EconumoBundle\Domain\Repository\CurrencyRateRepositoryInterface;
use App\EconumoBundle\Domain\Service\Budget\Dto\AverageCurrencyRateDto;
use App\EconumoBundle\Domain\Service\Currency\CurrencyServiceInterface;
use DateTimeInterface;

readonly class AverageCurrencyRateDtoAssembler
{
    public function __construct(
        private CurrencyServiceInterface $currencyService,
        private CurrencyRateRepositoryInterface $currencyRateRepository,
    ) {
    }

    /**
     * @param Id[] $currenciesIds
     * @return AverageCurrencyRateDto[]
     */
    public function assemble(
        DateTimeInterface $periodStart,
        DateTimeInterface $periodEnd,
        array $currenciesIds
    ): array {
        $baseCurrency = $this->currencyService->getBaseCurrency();
        $supportedCurrencyIds = array_map(static fn(Id $id): string => $id->getValue(), $currenciesIds);
        $currencyRates = $this->currencyRateRepository->getAverage($periodStart, $periodEnd, $baseCurrency->getId());

        $result = [];
        foreach ($currencyRates as $item) {
            if (in_array($item['currencyId'], $supportedCurrencyIds, true)) {
                $result[] = new AverageCurrencyRateDto(new Id($item['currencyId']), round((float) $item['rate'], 8));
            }
        }

        return $result;
    }
}
