<?php

declare(strict_types=1);


namespace App\Application\Budget\Assembler;

use App\Application\Budget\Dto\BudgetStructureResultDto;
use App\Domain\Service\Budget\Dto\BudgetStructureDto;

readonly class BudgetStructureToResultDtoAssembler
{
    public function __construct(
        private BudgetFolderToResultDtoAssembler $budgetFolderToResultDtoAssembler,
        private BudgetParentElementToResultDtoAssembler $budgetParentElementToResultDtoAssembler,
    ) {
    }

    public function assemble(BudgetStructureDto $dto): BudgetStructureResultDto
    {
        $result = new BudgetStructureResultDto();
        $result->folders = [];
        foreach ($dto->folders as $folder) {
            $result->folders[] = $this->budgetFolderToResultDtoAssembler->assemble($folder);
        }

        $result->elements = [];
        foreach ($dto->elements as $element) {
            $result->elements[] = $this->budgetParentElementToResultDtoAssembler->assemble($element);
        }

        return $result;
    }
}
