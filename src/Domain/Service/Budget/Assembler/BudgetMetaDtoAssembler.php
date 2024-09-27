<?php

declare(strict_types=1);

namespace App\Domain\Service\Budget\Assembler;

use App\Domain\Entity\Budget;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Service\Budget\Dto\BudgetMetaDto;

readonly class BudgetMetaDtoAssembler
{
    public function assemble(Budget $budget): BudgetMetaDto
    {
        return new BudgetMetaDto(
            $budget->getId(),
            $budget->getUser()->getId(),
            $budget->getName(),
            $budget->getStartedAt(),
            null,
            $budget->getAccessList()->toArray()
        );
    }
}