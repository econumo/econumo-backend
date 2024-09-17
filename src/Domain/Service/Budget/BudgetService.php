<?php

declare(strict_types=1);

namespace App\Domain\Service\Budget;

use App\Domain\Entity\ValueObject\BudgetName;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Exception\AccessDeniedException;
use App\Domain\Factory\BudgetFactoryInterface;
use App\Domain\Repository\AccountRepositoryInterface;
use App\Domain\Repository\BudgetEntityAmountRepositoryInterface;
use App\Domain\Repository\BudgetRepositoryInterface;
use App\Domain\Service\AntiCorruptionServiceInterface;
use App\Domain\Service\Budget\Assembler\BudgetDtoAssembler;
use App\Domain\Service\Budget\Assembler\BudgetPreviewDtoAssembler;
use App\Domain\Service\Budget\Dto\BudgetDto;
use App\Domain\Service\Budget\Dto\BudgetPreviewDto;
use App\Domain\Service\DatetimeServiceInterface;
use App\Domain\Service\UserServiceInterface;
use DateTimeInterface;
use Throwable;

readonly class BudgetService implements BudgetServiceInterface
{
    public function __construct(
        private BudgetFactoryInterface $budgetFactory,
        private BudgetRepositoryInterface $budgetRepository,
        private DatetimeServiceInterface $datetimeService,
        private UserServiceInterface $userService,
        private AntiCorruptionServiceInterface $antiCorruptionService,
        private BudgetDtoAssembler $budgetDtoAssembler,
        private BudgetEntityServiceInterface $budgetEntityService,
        private BudgetPreviewDtoAssembler $budgetPreviewDtoAssembler,
        private BudgetDeletionService $budgetDeletionService,
        private AccountRepositoryInterface $accountRepository,
        private BudgetEntityAmountRepositoryInterface $budgetEntityAmountRepository,
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
            $categoriesOptions = $this->budgetEntityService->createCategoriesOptions($userId, $budgetId);
            $this->budgetEntityService->createTagsOptions($userId, $budgetId, count($categoriesOptions));
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

    public function getBudgetList(Id $userId): array
    {
        $budgets = $this->budgetRepository->getByUserId($userId);
        $result = [];
        foreach ($budgets as $budget) {
            $result[] = $this->budgetPreviewDtoAssembler->assemble($budget);
        }

        return $result;
    }

    public function deleteBudget(Id $budgetId): void
    {
        $this->budgetDeletionService->deleteBudget($budgetId);
    }

    public function updateBudget(Id $budgetId, BudgetName $name): BudgetPreviewDto
    {
        $budget = $this->budgetRepository->get($budgetId);
        $budget->updateName($name);
        $this->budgetRepository->save([$budget]);
        return $this->budgetPreviewDtoAssembler->assemble($budget);
    }

    public function excludeAccount(Id $userId, Id $budgetId, Id $accountId): BudgetPreviewDto
    {
        $account = $this->accountRepository->get($accountId);
        if (!$account->getUserId()->isEqual($userId)) {
            throw new AccessDeniedException();
        }

        $budget = $this->budgetRepository->get($budgetId);
        $budget->excludeAccount($account);
        $this->budgetRepository->save([$budget]);
        return $this->budgetPreviewDtoAssembler->assemble($budget);
    }

    public function includeAccount(Id $userId, Id $budgetId, Id $accountId): BudgetPreviewDto
    {
        $account = $this->accountRepository->get($accountId);
        if (!$account->getUserId()->isEqual($userId)) {
            throw new AccessDeniedException();
        }

        $budget = $this->budgetRepository->get($budgetId);
        $budget->includeAccount($account);
        $this->budgetRepository->save([$budget]);
        return $this->budgetPreviewDtoAssembler->assemble($budget);
    }

    public function resetBudget(Id $budgetId, DateTimeInterface $startedAt): BudgetPreviewDto
    {
        $budget = $this->budgetRepository->get($budgetId);
        try {
            $this->antiCorruptionService->beginTransaction(__METHOD__);
            $this->budgetEntityAmountRepository->deleteByBudgetId($budgetId);

            $budget->startFrom($startedAt);
            $this->budgetRepository->save([$budget]);
            $this->antiCorruptionService->commit(__METHOD__);
        } catch (Throwable $e) {
            $this->antiCorruptionService->rollback(__METHOD__);
            throw $e;
        }
        return $this->budgetPreviewDtoAssembler->assemble($budget);
    }
}
