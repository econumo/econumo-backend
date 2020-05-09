<?php
declare(strict_types=1);

namespace App\Application\Budget\Assembler;

use App\Application\Budget\Dto\GetItemDisplayDto;
use App\Application\Budget\Dto\GetItemPeriodDisplayDto;
use App\Application\Budget\Dto\GetItemValueDisplayDto;
use App\Application\Budget\Dto\GetListItemDisplayDto;
use App\Domain\Entity\Budget;
use App\Domain\Entity\BudgetData;
use DateInterval;
use DatePeriod;
use DateTimeInterface;

class GetItemDisplayAssembler
{
    private const PERIOD_ID_FORMAT = 'Y-m-d';

    /**
     * @param Budget $budget
     * @param BudgetData[] $data
     * @param DateTimeInterface $fromDate
     * @param DateTimeInterface $toDate
     * @return GetItemDisplayDto
     */
    public function assemble(Budget $budget, array $data, DateTimeInterface $fromDate, DateTimeInterface $toDate): GetItemDisplayDto
    {
        $dto = new GetItemDisplayDto();
        $dto->budget = new GetListItemDisplayDto();
        $dto->budget->id = $budget->getId()->getValue();
        $dto->budget->name = $budget->getName();
        $dto->budget->position = $budget->getPosition();
        $dto->budget->currencyId = $budget->getCurrencyId()->getValue();

        $dto->period = [];
        $interval = new DateInterval('P1M');
        /** @var DateTimeInterface[] $period */
        $period = new DatePeriod($fromDate, $interval, $toDate);
        $i = 1;
        foreach ($period as $date) {
            $item = new GetItemPeriodDisplayDto();
            $item->id = $date->format(static::PERIOD_ID_FORMAT);
            $item->month = $date->format('m');
            $item->position = $i++;
            $dto->period[] = $item;
        }

        $dto->values = [];
        /** @var BudgetData $dataItem */
        foreach ($data as $dataItem) {
            $item = new GetItemValueDisplayDto();
            $item->periodId = $dataItem->getDate()->format(static::PERIOD_ID_FORMAT);
            $item->categoryId = $dataItem->getCategoryId()->getValue();
            $item->expectedValue = $dataItem->getExpectedValue();
            $item->actualValue = $dataItem->getActualValue();
            $dto->values[] = $item;
        }

        return $dto;
    }
}
