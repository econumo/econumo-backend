<?php

declare(strict_types=1);

namespace App\Domain\Service\Budget;

use App\Domain\Entity\Budget;
use App\Domain\Entity\ValueObject\BudgetName;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Factory\BudgetFactoryInterface;
use App\Domain\Repository\BudgetRepositoryInterface;
use App\Domain\Service\AntiCorruptionServiceInterface;
use App\Domain\Service\Budget\Assembler\BudgetDtoAssembler;
use App\Domain\Service\Budget\Dto\BudgetDto;
use App\Domain\Service\DatetimeServiceInterface;
use App\Domain\Service\UserServiceInterface;
use Throwable;

readonly class BudgetService implements BudgetServiceInterface
{
    public function __construct(
        private BudgetFactoryInterface $budgetFactory,
        private BudgetRepositoryInterface $budgetRepository,
        private DatetimeServiceInterface $datetimeService,
        private UserServiceInterface $userService,
        private AntiCorruptionServiceInterface $antiCorruptionService,
        private BudgetDtoAssembler $budgetDtoAssembler
    ) {
    }

    public function createBudget(Id $userId, Id $budgetId, BudgetName $name, array $excludedAccountsIds = []): BudgetDto
    {
        $this->antiCorruptionService->beginTransaction(__METHOD__);
        try {
            $budget = $this->budgetFactory->create(
                $userId,
                $budgetId,
                $name,
                $excludedAccountsIds,
                $this->datetimeService->getCurrentDatetime()
            );
            $this->budgetRepository->save([$budget]);
            $this->userService->updateDefaultBudget($userId, $budgetId);
            $this->antiCorruptionService->commit(__METHOD__);
        } catch (Throwable $e) {
            $this->antiCorruptionService->rollback(__METHOD__);
            throw $e;
        }

        return $this->budgetDtoAssembler->assemble($userId, $budget);
    }

    public function getBudget(Id $userId, Id $budgetId): BudgetDto
    {
        $budget = $this->budgetRepository->get($budgetId);
        return $this->budgetDtoAssembler->assemble($userId, $budget);
    }
}
