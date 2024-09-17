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
use App\Application\Budget\Dto\DeleteBudgetV1RequestDto;
use App\Application\Budget\Dto\DeleteBudgetV1ResultDto;
use App\Application\Budget\Assembler\DeleteBudgetV1ResultAssembler;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Service\Budget\BudgetAccessServiceInterface;
use App\Domain\Service\Budget\BudgetServiceInterface;
use App\Application\Budget\Dto\UpdateBudgetV1RequestDto;
use App\Application\Budget\Dto\UpdateBudgetV1ResultDto;
use App\Application\Budget\Assembler\UpdateBudgetV1ResultAssembler;
use App\Application\Budget\Dto\ResetBudgetV1RequestDto;
use App\Application\Budget\Dto\ResetBudgetV1ResultDto;
use App\Application\Budget\Assembler\ResetBudgetV1ResultAssembler;
use DateTimeImmutable;

readonly class BudgetService
{
    public function __construct(
        private CreateBudgetV1ResultAssembler $createBudgetV1ResultAssembler,
        private BudgetServiceInterface $budgetService,
        private GetBudgetV1ResultAssembler $getBudgetV1ResultAssembler,
        private BudgetAccessServiceInterface $budgetAccessService,
        private DeleteBudgetV1ResultAssembler $deleteBudgetV1ResultAssembler,
        private UpdateBudgetV1ResultAssembler $updateBudgetV1ResultAssembler,
        private ResetBudgetV1ResultAssembler $resetBudgetV1ResultAssembler,
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

    public function deleteBudget(
        DeleteBudgetV1RequestDto $dto,
        Id $userId
    ): DeleteBudgetV1ResultDto {
        $budgetId = new Id($dto->id);
        if (!$this->budgetAccessService->canDeleteBudget($userId, $budgetId)) {
            throw new AccessDeniedException();
        }

        $this->budgetService->deleteBudget($budgetId);
        return $this->deleteBudgetV1ResultAssembler->assemble();
    }

    public function updateBudget(
        UpdateBudgetV1RequestDto $dto,
        Id $userId
    ): UpdateBudgetV1ResultDto {
        $budgetId = new Id($dto->id);
        if (!$this->budgetAccessService->canUpdateBudget($userId, $budgetId)) {
            throw new AccessDeniedException();
        }

        $budgetDto = $this->budgetService->updateBudget($budgetId, new BudgetName($dto->name));
        return $this->updateBudgetV1ResultAssembler->assemble($budgetDto);
    }

    public function resetBudget(
        ResetBudgetV1RequestDto $dto,
        Id $userId
    ): ResetBudgetV1ResultDto {
        $budgetId = new Id($dto->id);
        if (!$this->budgetAccessService->canResetBudget($userId, $budgetId)) {
            throw new AccessDeniedException();
        }
        $startedAt = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $dto->startedAt);
        $budgetDto = $this->budgetService->resetBudget($budgetId, $startedAt);
        return $this->resetBudgetV1ResultAssembler->assemble($budgetDto);
    }
}
