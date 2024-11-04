<?php

declare(strict_types=1);


namespace App\EconumoOneBundle\Domain\Service\Budget\Assembler;


use App\EconumoOneBundle\Domain\Entity\BudgetFolder;
use App\EconumoOneBundle\Domain\Service\Budget\Dto\BudgetStructureFolderDto;

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
