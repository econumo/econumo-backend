<?php

declare(strict_types=1);

namespace App\EconumoBundle\Domain\Service\Budget\Assembler;

use App\EconumoBundle\Domain\Entity\Budget;
use App\EconumoBundle\Domain\Service\Budget\Dto\BudgetMetaDto;

readonly class BudgetMetaDtoAssembler
{
    public function assemble(Budget $budget): BudgetMetaDto
    {
        return new BudgetMetaDto(
            $budget->getId(),
            $budget->getUser()->getId(),
            $budget->getName(),
            $budget->getStartedAt(),
            $budget->getCurrencyId(),
            $budget->getAccessList()->toArray()
        );
    }
}