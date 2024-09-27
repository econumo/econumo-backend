<?php

declare(strict_types=1);


namespace App\Domain\Service\Budget\Assembler;


use App\Domain\Entity\BudgetFolder;
use App\Domain\Service\Budget\Dto\BudgetStructureFolderDto;

readonly class BudgetStructureFolderDtoAssembler
{
    public function assemble(BudgetFolder $folder): BudgetStructureFolderDto
    {
        return new BudgetStructureFolderDto(
            $folder->getId(),
            $folder->getName(),
            $folder->getPosition()
        );
    }
}
