<?php

declare(strict_types=1);


namespace App\Domain\Service\Budget;


use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\BudgetRepositoryInterface;
use App\Domain\Service\Budget\Assembler\BudgetStructureDtoAssembler;
use App\Domain\Service\Budget\Dto\BudgetStructureDto;

readonly class BudgetStructureService
{
    public function __construct(
        private BudgetRepositoryInterface $budgetRepository,
        private BudgetStructureDtoAssembler $budgetDtoAssembler
    ) {
    }

    public function getBudgetStructure(Id $userId, Id $budgetId): BudgetStructureDto
    {
        $budget = $this->budgetRepository->get($budgetId);
        return $this->budgetDtoAssembler->assemble($userId, $budget);
    }
}
