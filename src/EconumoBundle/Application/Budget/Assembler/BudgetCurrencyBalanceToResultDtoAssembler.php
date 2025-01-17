<?php

declare(strict_types=1);


namespace App\EconumoBundle\Application\Budget\Assembler;

use App\EconumoBundle\Application\Budget\Dto\BudgetCurrencyBalanceResultDto;
use App\EconumoBundle\Domain\Service\Budget\Dto\CurrencyBalanceDto;

readonly class BudgetCurrencyBalanceToResultDtoAssembler
{
    public function assemble(CurrencyBalanceDto $dto): BudgetCurrencyBalanceResultDto
    {
        $result = new BudgetCurrencyBalanceResultDto();
        $result->currencyId = $dto->currencyId->getValue();
        $result->startBalance = $dto->startBalance?->float();
        $result->endBalance = $dto->endBalance?->float();
        $result->income = $dto->income?->float();
        $result->expenses = $dto->expenses?->float();
        $result->exchanges = $dto->exchanges?->float();
        $result->holdings = $dto->holdings?->float();

        return $result;
    }
}
