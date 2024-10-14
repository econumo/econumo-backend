<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\Budget;

use App\EconumoOneBundle\Application\Budget\Dto\CreateBudgetV1RequestDto;
use App\EconumoOneBundle\Application\Budget\Dto\CreateBudgetV1ResultDto;
use App\EconumoOneBundle\Application\Budget\Assembler\CreateBudgetV1ResultAssembler;
use App\EconumoOneBundle\Application\Exception\AccessDeniedException;
use App\EconumoOneBundle\Domain\Entity\ValueObject\BudgetName;
use App\EconumoOneBundle\Application\Budget\Dto\DeleteBudgetV1RequestDto;
use App\EconumoOneBundle\Application\Budget\Dto\DeleteBudgetV1ResultDto;
use App\EconumoOneBundle\Application\Budget\Assembler\DeleteBudgetV1ResultAssembler;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Service\Budget\BudgetAccessServiceInterface;
use App\EconumoOneBundle\Domain\Service\Budget\BudgetServiceInterface;
use App\EconumoOneBundle\Application\Budget\Dto\UpdateBudgetV1RequestDto;
use App\EconumoOneBundle\Application\Budget\Dto\UpdateBudgetV1ResultDto;
use App\EconumoOneBundle\Application\Budget\Assembler\UpdateBudgetV1ResultAssembler;
use App\EconumoOneBundle\Application\Budget\Dto\ResetBudgetV1RequestDto;
use App\EconumoOneBundle\Application\Budget\Dto\ResetBudgetV1ResultDto;
use App\EconumoOneBundle\Application\Budget\Assembler\ResetBudgetV1ResultAssembler;
use DateTimeImmutable;
use App\EconumoOneBundle\Application\Budget\Dto\GetBudgetV1RequestDto;
use App\EconumoOneBundle\Application\Budget\Dto\GetBudgetV1ResultDto;
use App\EconumoOneBundle\Application\Budget\Assembler\GetBudgetV1ResultAssembler;

readonly class BudgetService
{
    public function __construct(
        private CreateBudgetV1ResultAssembler $createBudgetV1ResultAssembler,
        private BudgetServiceInterface $budgetService,
        private BudgetAccessServiceInterface $budgetAccessService,
        private DeleteBudgetV1ResultAssembler $deleteBudgetV1ResultAssembler,
        private UpdateBudgetV1ResultAssembler $updateBudgetV1ResultAssembler,
        private ResetBudgetV1ResultAssembler $resetBudgetV1ResultAssembler,
        private GetBudgetV1ResultAssembler $getBudgetV1ResultAssembler,
    ) {
    }

    public function createBudget(
        CreateBudgetV1RequestDto $dto,
        Id $userId
    ): CreateBudgetV1ResultDto {
        $budgetId = new Id($dto->id);
        $name = new BudgetName($dto->name);
        $startDate = empty($dto->startDate) ? null : DateTimeImmutable::createFromFormat('Y-m-d', $dto->startDate);
        $currencyId = empty($dto->currencyId) ? null : new Id($dto->currencyId);
        $excludedAccountsIds = array_map(
            fn(string $id) => new Id($id),
            $dto->excludedAccounts
        );
        $budget = $this->budgetService->createBudget(
            $userId,
            $budgetId,
            $name,
            $startDate,
            $currencyId,
            $excludedAccountsIds
        );
        return $this->createBudgetV1ResultAssembler->assemble($budget);
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
        $excludedAccountsIds = array_map(
            fn(string $id) => new Id($id),
            $dto->excludedAccounts
        );

        $budgetDto = $this->budgetService->updateBudget(
            $userId,
            $budgetId,
            new BudgetName($dto->name),
            $excludedAccountsIds
        );
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
        $budgetDto = $this->budgetService->resetBudget($userId, $budgetId, $startedAt);
        return $this->resetBudgetV1ResultAssembler->assemble($budgetDto);
    }

    public function getBudget(
        GetBudgetV1RequestDto $dto,
        Id $userId
    ): GetBudgetV1ResultDto {
        $budgetId = new Id($dto->id);
        $date = new DateTimeImmutable($dto->date);
        $date = $date->setDate((int)$date->format('Y'), (int)$date->format('m'), 1);
        $date = $date->setTime(0, 0, 0);
        if (!$this->budgetAccessService->canReadBudget($userId, $budgetId)) {
            throw new AccessDeniedException();
        }

        $budget = $this->budgetService->getBudget($userId, $budgetId, $date);
        return $this->getBudgetV1ResultAssembler->assemble($budget);
    }
}
