<?php

declare(strict_types=1);


namespace App\Domain\Service\Budget;


use App\Domain\Entity\Budget;
use App\Domain\Entity\ValueObject\BudgetName;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Factory\BudgetFactoryInterface;
use App\Domain\Repository\BudgetRepositoryInterface;
use App\Domain\Service\AntiCorruptionServiceInterface;
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
        private AntiCorruptionServiceInterface $antiCorruptionService
    ) {
    }

    public function createBudget(Id $userId, Id $id, BudgetName $name, array $excludedAccountsIds = []): Budget
    {
        $this->antiCorruptionService->beginTransaction(__METHOD__);
        try {
            $budget = $this->budgetFactory->create(
                $userId,
                $id,
                $name,
                $excludedAccountsIds,
                $this->datetimeService->getCurrentDatetime()
            );
            $this->budgetRepository->save([$budget]);
            $this->userService->updateDefaultBudget($userId, $id);
            $this->antiCorruptionService->commit(__METHOD__);
        } catch (Throwable $e) {
            $this->antiCorruptionService->rollback(__METHOD__);
            throw $e;
        }

        return $budget;
    }
}