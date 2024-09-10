<?php

declare(strict_types=1);


namespace App\Application\Budget\Assembler;

use App\Application\Budget\Dto\BudgetEnvelopeResultDto;
use App\Domain\Entity\BudgetEnvelope;

readonly class BudgetEnvelopeToResultDtoAssembler
{
    public function assemble(BudgetEnvelope $budgetFolder): BudgetEnvelopeResultDto
    {
        $result = new BudgetEnvelopeResultDto();
        $result->id = $budgetFolder->getId()->getValue();
        $result->name = $budgetFolder->getName()->getValue();
        $result->icon = $budgetFolder->getIcon()->getValue();

        return $result;
    }
}
