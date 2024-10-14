<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\Budget\Assembler;

use App\EconumoOneBundle\Application\Budget\Dto\CreateFolderV1ResultDto;
use App\EconumoOneBundle\Domain\Service\Budget\Dto\BudgetStructureFolderDto;

readonly class CreateFolderV1ResultAssembler
{
    public function __construct(
        private BudgetFolderToResultDtoAssembler $budgetFolderToResultDtoAssembler,
    ) {
    }

    public function assemble(
        BudgetStructureFolderDto $folder
    ): CreateFolderV1ResultDto {
        $result = new CreateFolderV1ResultDto();
        $result->item = $this->budgetFolderToResultDtoAssembler->assemble($folder);

        return $result;
    }
}
