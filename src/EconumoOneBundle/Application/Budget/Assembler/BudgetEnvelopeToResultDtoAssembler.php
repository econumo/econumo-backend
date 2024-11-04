<?php

declare(strict_types=1);


namespace App\EconumoOneBundle\Application\Budget\Assembler;

use App\EconumoOneBundle\Application\Budget\Dto\BudgetStructureParentElementResultDto;
use App\EconumoOneBundle\Domain\Entity\BudgetEnvelope;

readonly class BudgetEnvelopeToResultDtoAssembler
{
    public function assemble(BudgetEnvelope $budgetFolder): BudgetStructureParentElementResultDto
    {
        $result = new BudgetStructureParentElementResultDto();
        $result->id = $budgetFolder->getId()->getValue();
        $result->name = $budgetFolder->getName()->getValue();
        $result->icon = $budgetFolder->getIcon()->getValue();

        return $result;
    }
}
