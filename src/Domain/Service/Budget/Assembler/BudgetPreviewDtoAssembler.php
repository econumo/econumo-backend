<?php

declare(strict_types=1);

namespace App\Domain\Service\Budget\Assembler;

use App\Domain\Entity\Budget;
use App\Domain\Service\Budget\Dto\BudgetPreviewDto;

readonly class BudgetPreviewDtoAssembler
{
    public function assemble(
        Budget $budget
    ): BudgetPreviewDto {
        return new BudgetPreviewDto(
            $budget->getId(),
            $budget->getUser()->getId(),
            $budget->getName(),
            $budget->getStartedAt(),
            $budget->getAccessList()->toArray()
        );
    }
}