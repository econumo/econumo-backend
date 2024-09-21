<?php

declare(strict_types=1);

namespace App\Application\Budget\Assembler;

use App\Application\Budget\Dto\AverageCurrencyRateDto;
use App\Application\Budget\Dto\BudgetCurrencyBalanceDto;
use App\Application\Budget\Dto\BudgetEntityBudgetAmountDto;
use App\Application\Budget\Dto\BudgetEntitySpendAmountDto;
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
        $result->entityBudgetAmounts = [];
        foreach ($budgetDataDto->entityBudgetAmounts as $entityBudgetAmount) {
            $item2 = new BudgetEntityBudgetAmountDto();
            $item2->entityId = $entityBudgetAmount->entityId->getValue();
            $item2->entityType = $entityBudgetAmount->entityType->getAlias();
            $item2->amount = $entityBudgetAmount->amount;
            $result->entityBudgetAmounts[] = $item2;
        }
        $result->entitySpendAmounts = [];
        foreach ($budgetDataDto->entitySpendAmounts as $entitySpendAmount) {
            $item3 = new BudgetEntitySpendAmountDto();
            $item3->entityId = $entitySpendAmount->entityId->getValue();
            $item3->currencyId = $entitySpendAmount->currencyId->getValue();
            $item3->entityType = $entitySpendAmount->entityType->getAlias();
            $item3->amount = $entitySpendAmount->amount;
            $result->entitySpendAmounts[] = $item3;
        }

        return $result;
    }
}
