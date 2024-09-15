<?php

declare(strict_types=1);

namespace App\Application\Budget;

use App\Application\Budget\Dto\CreateBudgetV1RequestDto;
use App\Application\Budget\Dto\CreateBudgetV1ResultDto;
use App\Application\Budget\Assembler\CreateBudgetV1ResultAssembler;
use App\Application\Exception\AccessDeniedException;
use App\Domain\Entity\ValueObject\BudgetName;
use App\Application\Budget\Dto\GetBudgetV1RequestDto;
use App\Application\Budget\Dto\GetBudgetV1ResultDto;
use App\Application\Budget\Assembler\GetBudgetV1ResultAssembler;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Service\Budget\BudgetAccessServiceInterface;
use App\Domain\Service\Budget\BudgetServiceInterface;

readonly class BudgetService
{
    public function __construct(
        private CreateBudgetV1ResultAssembler $createBudgetV1ResultAssembler,
        private BudgetServiceInterface $budgetService,
        private GetBudgetV1ResultAssembler $getBudgetV1ResultAssembler,
        private BudgetAccessServiceInterface $budgetAccessService,
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

    public function getBudget(
        GetBudgetV1RequestDto $dto,
        Id $userId
    ): GetBudgetV1ResultDto {
        $budgetId = new Id($dto->id);
        if (!$this->budgetAccessService->canReadBudget($userId, $budgetId)) {
            throw new AccessDeniedException();
        }

        $budgetDto = $this->budgetService->getBudget($userId, $budgetId);
        return $this->getBudgetV1ResultAssembler->assemble($userId, $budgetDto);
    }
}
