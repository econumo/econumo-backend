<?php

declare(strict_types=1);


namespace App\Domain\Service\Budget\Dto;


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
