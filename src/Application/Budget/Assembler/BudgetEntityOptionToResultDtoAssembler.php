<?php

declare(strict_types=1);


namespace App\Application\Budget\Assembler;

use App\Application\Budget\Dto\BudgetEntityOptionResultDto;
use App\Domain\Entity\BudgetEntityOption;

readonly class BudgetEntityOptionToResultDtoAssembler
{
    public function assemble(BudgetEntityOption $option): BudgetEntityOptionResultDto
    {
        $result = new BudgetEntityOptionResultDto();
        $result->entityId = $option->getEntityId()->getValue();
        $result->entityType = $option->getEntityType()->getValue();
        $result->position = $option->getPosition();
        $result->currencyId = $option->getCurrency()?->getId()->getValue();
        $result->folderId = $option->getFolder()?->getId()->getValue();

        return $result;
    }
}
