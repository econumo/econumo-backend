<?php

declare(strict_types=1);


namespace App\EconumoOneBundle\Domain\Service\Budget\Dto;


use App\EconumoOneBundle\Domain\Service\Budget\Dto\BudgetStructureFolderDto;
use App\EconumoOneBundle\Domain\Service\Budget\Dto\BudgetStructureParentElementDto;

readonly class BudgetStructureDto
{
    public function __construct(
        /** @var BudgetStructureFolderDto[] */
        public array $folders,
        /** @var BudgetStructureParentElementDto[] */
        public array $elements,
    ) {
    }
}
