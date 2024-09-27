<?php

declare(strict_types=1);


namespace App\Domain\Service\Budget;


use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\BudgetRepositoryInterface;
use App\Domain\Service\Budget\Assembler\BudgetFiltersDtoAssembler;
use App\Domain\Service\Budget\Dto\BudgetFiltersDto;

readonly class BudgetStructureService
{
    public function __construct(
        private BudgetRepositoryInterface $budgetRepository,
        private BudgetFiltersDtoAssembler $budgetDtoAssembler
    ) {
    }

    public function getBudgetStructure(Id $userId, Id $budgetId): BudgetFiltersDto
    {
        $budget = $this->budgetRepository->get($budgetId);
        return $this->budgetDtoAssembler->assemble($userId, $budget);
    }
}
