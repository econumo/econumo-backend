<?php

declare(strict_types=1);


namespace App\EconumoOneBundle\Application\Budget\Assembler;

use App\EconumoOneBundle\Application\Budget\Dto\BudgetStructureChildElementResultDto;
use App\EconumoOneBundle\Domain\Service\Budget\Dto\BudgetStructureChildElementDto;

readonly class BudgetChildElementToResultDtoAssembler
{
    public function assemble(BudgetStructureChildElementDto $dto): BudgetStructureChildElementResultDto
    {
        $result = new BudgetStructureChildElementResultDto();
        $result->id = $dto->id->getValue();
        $result->type = $dto->type->getValue();
        $result->name = $dto->name->getValue();
        $result->icon = $dto->icon->getValue();
        $result->isArchived = $dto->isArchived ? 1 : 0;
        $result->spent = $dto->spent;
        $result->budgetSpent = $dto->spentInBudgetCurrency;
        $result->ownerUserId = $dto->ownerId->getValue();

        return $result;
    }
}
