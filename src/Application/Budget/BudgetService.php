<?php

declare(strict_types=1);

namespace App\Application\Budget;

use App\Application\Budget\Dto\CreateBudgetV1RequestDto;
use App\Application\Budget\Dto\CreateBudgetV1ResultDto;
use App\Application\Budget\Assembler\CreateBudgetV1ResultAssembler;
use App\Domain\Entity\ValueObject\BudgetName;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Service\Budget\BudgetServiceInterface;

readonly class BudgetService
{
    public function __construct(
        private CreateBudgetV1ResultAssembler $createBudgetV1ResultAssembler,
        private BudgetServiceInterface $budgetService
    ) {
    }

    public function createBudget(
        CreateBudgetV1RequestDto $dto,
        Id $userId
    ): CreateBudgetV1ResultDto {
        $id = new Id($dto->id);
        $name = new BudgetName($dto->name);
        $excludedAccountsIds = array_map(
            fn(string $id) => new Id($id),
            $dto->excludedAccounts
        );
        $budgetDto = $this->budgetService->createBudget($userId, $id, $name, $excludedAccountsIds);
        return $this->createBudgetV1ResultAssembler->assemble($userId, $budgetDto);
    }
}
