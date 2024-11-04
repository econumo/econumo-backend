<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\Budget\Assembler;

use App\EconumoOneBundle\Application\Budget\Dto\UpdateEnvelopeV1ResultDto;
use App\EconumoOneBundle\Domain\Service\Budget\Dto\BudgetStructureParentElementDto;

readonly class UpdateEnvelopeV1ResultAssembler
{
    public function __construct(
        private BudgetParentElementToResultDtoAssembler $budgetParentElementToResultDtoAssembler
    ) {
    }

    public function assemble(
        BudgetStructureParentElementDto $dto
    ): UpdateEnvelopeV1ResultDto {
        $result = new UpdateEnvelopeV1ResultDto();
        $result->item = $this->budgetParentElementToResultDtoAssembler->assemble($dto);

        return $result;
    }
}
