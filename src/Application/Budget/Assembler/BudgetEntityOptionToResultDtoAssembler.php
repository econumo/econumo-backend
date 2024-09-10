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
        $result->entityType = $option->getEntityType()->getAlias();
        $result->position = $option->getPosition();
        $result->currencyId = ($option->getCurrency() === null ? '' : $option->getCurrency()->getId());
        $result->finishedAt = ($option->getFinishedAt() === null ? '' : $option->getFinishedAt()->format('Y-m-d H:i:s'));

        return $result;
    }
}
