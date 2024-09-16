<?php

declare(strict_types=1);

namespace App\Application\Budget;

use App\Application\Budget\Dto\GetBudgetListV1RequestDto;
use App\Application\Budget\Dto\GetBudgetListV1ResultDto;
use App\Application\Budget\Assembler\GetBudgetListV1ResultAssembler;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Service\Budget\BudgetServiceInterface;

readonly class BudgetListService
{
    public function __construct(
        private GetBudgetListV1ResultAssembler $getBudgetListV1ResultAssembler,
        private BudgetServiceInterface $budgetService,
    ) {
    }

    public function getBudgetList(
        GetBudgetListV1RequestDto $dto,
        Id $userId
    ): GetBudgetListV1ResultDto {
        $budgets = $this->budgetService->getBudgetList($userId);
        return $this->getBudgetListV1ResultAssembler->assemble($budgets);
    }
}
