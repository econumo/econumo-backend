<?php

declare(strict_types=1);


namespace App\EconumoOneBundle\Application\Budget\Assembler;

use App\EconumoOneBundle\Application\Budget\Dto\BudgetFolderResultDto;
use App\EconumoOneBundle\Domain\Service\Budget\Dto\BudgetStructureFolderDto;

readonly class BudgetFolderToResultDtoAssembler
{
    public function assemble(BudgetStructureFolderDto $dto): BudgetFolderResultDto
    {
        $result = new BudgetFolderResultDto();
        $result->id = $dto->id->getValue();
        $result->name = $dto->name->getValue();
        $result->position = $dto->position;

        return $result;
    }
}
