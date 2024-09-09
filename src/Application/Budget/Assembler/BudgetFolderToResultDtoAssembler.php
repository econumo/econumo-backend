<?php

declare(strict_types=1);


namespace App\Application\Budget\Assembler;

use App\Application\Budget\Dto\BudgetFolderResultDto;
use App\Domain\Entity\BudgetFolder;

readonly class BudgetFolderToResultDtoAssembler
{
    public function assemble(BudgetFolder $budgetFolder): BudgetFolderResultDto
    {
        $result = new BudgetFolderResultDto();
        $result->id = $budgetFolder->getId()->getValue();
        $result->name = $budgetFolder->getName()->getValue();
        $result->position = $budgetFolder->getPosition();


        return $result;
    }
}
