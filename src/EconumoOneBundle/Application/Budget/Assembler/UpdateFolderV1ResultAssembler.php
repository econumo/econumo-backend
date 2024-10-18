<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\Budget\Assembler;

use App\EconumoOneBundle\Application\Budget\Dto\UpdateFolderV1ResultDto;
use App\EconumoOneBundle\Domain\Service\Budget\Dto\BudgetStructureFolderDto;

readonly class UpdateFolderV1ResultAssembler
{
    public function __construct(
        private BudgetFolderToResultDtoAssembler $budgetFolderToResultDtoAssembler,
    ) {
    }

    public function assemble(
        BudgetStructureFolderDto $folder
    ): UpdateFolderV1ResultDto {
        $result = new UpdateFolderV1ResultDto();
        $result->item = $this->budgetFolderToResultDtoAssembler->assemble($folder);

        return $result;
    }
}
