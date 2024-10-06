<?php

declare(strict_types=1);


namespace App\EconumoBundle\Domain\Service\Budget;


use App\EconumoBundle\Domain\Entity\ValueObject\Id;
use App\EconumoBundle\Domain\Repository\BudgetRepositoryInterface;
use App\EconumoBundle\Domain\Service\Budget\Assembler\BudgetFiltersDtoAssembler;
use App\EconumoBundle\Domain\Service\Budget\Dto\BudgetFiltersDto;

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
