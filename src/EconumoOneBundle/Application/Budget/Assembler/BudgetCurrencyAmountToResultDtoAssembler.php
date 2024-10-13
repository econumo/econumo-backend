<?php

declare(strict_types=1);


namespace App\EconumoOneBundle\Application\Budget\Assembler;

use App\EconumoOneBundle\Application\Budget\Dto\BudgetCurrencyAmountDto;
use App\EconumoOneBundle\Domain\Service\Budget\Dto\BudgetEntityAmountSpentDto;

readonly class BudgetCurrencyAmountToResultDtoAssembler
{
    public function assemble(BudgetEntityAmountSpentDto $dto): BudgetCurrencyAmountDto
    {
        $result = new BudgetCurrencyAmountDto();
        $result->currencyId = $dto->currencyId->getValue();
        $result->amount = $dto->amount;

        return $result;
    }
}
