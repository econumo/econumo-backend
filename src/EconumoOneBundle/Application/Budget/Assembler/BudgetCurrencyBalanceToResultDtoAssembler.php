<?php

declare(strict_types=1);


namespace App\EconumoOneBundle\Application\Budget\Assembler;

use App\EconumoOneBundle\Application\Budget\Dto\BudgetCurrencyBalanceResultDto;
use App\EconumoOneBundle\Domain\Service\Budget\Dto\CurrencyBalanceDto;

readonly class BudgetCurrencyBalanceToResultDtoAssembler
{
    public function assemble(CurrencyBalanceDto $dto): BudgetCurrencyBalanceResultDto
    {
        $result = new BudgetCurrencyBalanceResultDto();
        $result->currencyId = $dto->currencyId->getValue();
        $result->startBalance = $dto->startBalance;
        $result->endBalance = $dto->endBalance;
        $result->income = $dto->income;
        $result->expenses = $dto->expenses;
        $result->exchanges = $dto->exchanges;
        $result->holdings = $dto->holdings;

        return $result;
    }
}
