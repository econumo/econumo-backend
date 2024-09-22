<?php

declare(strict_types=1);

namespace App\Application\Budget\Assembler;

use App\Application\Budget\Dto\AverageCurrencyRateDto;
use App\Application\Budget\Dto\BudgetCurrencyBalanceDto;
use App\Application\Budget\Dto\BudgetEntityAmountDto;
use App\Application\Budget\Dto\BudgetEntityAmountSpentDto;
use App\Application\Budget\Dto\GetDataV1ResultDto;
use App\Domain\Service\Budget\Dto\BudgetDataDto;

readonly class GetDataV1ResultAssembler
{
    public function assemble(
        BudgetDataDto $budgetDataDto
    ): GetDataV1ResultDto {
        $result = new GetDataV1ResultDto();
        $result->id = $budgetDataDto->budgetId->getValue();
        $result->periodStart = $budgetDataDto->periodStart->format('Y-m-d H:i:s');
        $result->periodEnd = $budgetDataDto->periodEnd->format('Y-m-d H:i:s');
        $result->currencyBalances = [];
        foreach ($budgetDataDto->currencyBalances as $currencyBalancesDto) {
            $item = new BudgetCurrencyBalanceDto();
            $item->currencyId = $currencyBalancesDto->currencyId->getValue();
            $item->startBalance = $currencyBalancesDto->startBalance === null ? null : (float)$currencyBalancesDto->startBalance;
            $item->endBalance = $currencyBalancesDto->endBalance === null ? null : (float)$currencyBalancesDto->endBalance;
            $item->income = $currencyBalancesDto->income === null ? null : (float)$currencyBalancesDto->income;
            $item->expenses = $currencyBalancesDto->expenses === null ? null : (float)$currencyBalancesDto->expenses;
            $item->exchanges = $currencyBalancesDto->exchanges === null ? null : (float)$currencyBalancesDto->exchanges;
            $item->holdings = $currencyBalancesDto->holdings === null ? null : (float)$currencyBalancesDto->holdings;
            $result->currencyBalances[] = $item;
        }
        $result->averageCurrencyRates = [];
        foreach ($budgetDataDto->averageCurrencyRates as $averageCurrencyRate) {
            $item1 = new AverageCurrencyRateDto();
            $item1->currencyId = $averageCurrencyRate->currencyId->getValue();
            $item1->value = $averageCurrencyRate->value;
            $result->averageCurrencyRates[] = $item1;
        }
        $result->entityAmounts = [];
        foreach ($budgetDataDto->entityAmounts as $entityAmount) {
            $item4 = new BudgetEntityAmountDto();
            $item4->id = $entityAmount->entityId->getValue();
            $item4->type = $entityAmount->entityType->getValue();
            $item4->budget = $entityAmount->budget === null ? .0 : (float)$entityAmount->budget;
            $item4->available = $entityAmount->available === null ? .0 : (float)$entityAmount->available;
            $item4->spent = [];
            foreach ($entityAmount->spent as $entitySpent) {
                $itemSpent = new BudgetEntityAmountSpentDto();
                $itemSpent->currencyId = $entitySpent->currencyId->getValue();
                $itemSpent->amount = $entitySpent->amount;
                $item4->spent[] = $itemSpent;
            }
            $result->entityAmounts[] = $item4;
        }

        return $result;
    }
}
