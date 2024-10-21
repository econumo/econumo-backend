<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\Budget\Assembler;

use App\EconumoOneBundle\Application\Budget\Dto\CreateEnvelopeV1ResultDto;
use App\EconumoOneBundle\Domain\Service\Budget\Dto\BudgetStructureParentElementDto;

readonly class CreateEnvelopeV1ResultAssembler
{
    public function __construct(
        private BudgetParentElementToResultDtoAssembler $budgetParentElementToResultDtoAssembler
    ) {
    }

    public function assemble(
        BudgetStructureParentElementDto $dto
    ): CreateEnvelopeV1ResultDto {
        $result = new CreateEnvelopeV1ResultDto();
        $result->item = $this->budgetParentElementToResultDtoAssembler->assemble($dto);

        return $result;
    }
}
