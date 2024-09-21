<?php

declare(strict_types=1);


namespace App\Domain\Service\Budget\Assembler;


use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\CurrencyRateRepositoryInterface;
use App\Domain\Service\Budget\Dto\AverageCurrencyRateDto;
use App\Domain\Service\Budget\Dto\BudgetStructureDto;
use App\Domain\Service\Currency\CurrencyServiceInterface;
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
     * @param BudgetStructureDto $budgetStructureDto
     * @return AverageCurrencyRateDto[]
     */
    public function assemble(
        DateTimeInterface $periodStart,
        DateTimeInterface $periodEnd,
        BudgetStructureDto $budgetStructureDto
    ): array {
        $baseCurrency = $this->currencyService->getBaseCurrency();
        $supportedCurrencyIds = array_map(fn(Id $id) => $id->getValue(), $budgetStructureDto->currencies);
        $currencyRates = $this->currencyRateRepository->getAverage($periodStart, $periodEnd, $baseCurrency->getId());

        $result = [];
        foreach ($currencyRates as $item) {
            if (in_array($item['currencyId'], $supportedCurrencyIds, true)) {
                $result[] = new AverageCurrencyRateDto(new Id($item['currencyId']), floatval($item['rate']));
            }
        }

        return $result;
    }
}
