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
use App\Domain\Service\Budget\Assembler\BudgetMetaDtoAssembler;
use App\Domain\Service\Budget\Dto\BudgetDto;
use App\Domain\Service\Budget\Dto\BudgetMetaDto;
use App\Domain\Service\Budget\Dto\BudgetDataDto;
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
        private BudgetEntityServiceInterface $budgetEntityService,
        private BudgetMetaDtoAssembler $budgetMetaDtoAssembler,
        private BudgetDeletionService $budgetDeletionService,
        private AccountRepositoryInterface $accountRepository,
        private BudgetEntityAmountRepositoryInterface $budgetEntityAmountRepository,
        private BudgetDataService $budgetDataService,
        private BudgetDtoAssembler $budgetDtoAssembler,
    ) {
    }

    public function createBudget(Id $userId, Id $budgetId, BudgetName $name, array $excludedAccountsIds = []): BudgetMetaDto
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

        return $this->budgetMetaDtoAssembler->assemble($budget);
    }

    public function getBudgetList(Id $userId): array
    {
        $budgets = $this->budgetRepository->getByUserId($userId);
        $result = [];
        foreach ($budgets as $budget) {
            $result[] = $this->budgetMetaDtoAssembler->assemble($budget);
        }

        return $result;
    }

    public function deleteBudget(Id $budgetId): void
    {
        $this->budgetDeletionService->deleteBudget($budgetId);
    }

    public function updateBudget(Id $userId, Id $budgetId, BudgetName $name): BudgetMetaDto
    {
        $budget = $this->budgetRepository->get($budgetId);
        $budget->updateName($name);
        $this->budgetRepository->save([$budget]);
        return $this->budgetMetaDtoAssembler->assemble($budget);
    }

    public function excludeAccount(Id $userId, Id $budgetId, Id $accountId): BudgetMetaDto
    {
        $account = $this->accountRepository->get($accountId);
        if (!$account->getUserId()->isEqual($userId)) {
            throw new AccessDeniedException();
        }

        $budget = $this->budgetRepository->get($budgetId);
        $budget->excludeAccount($account);
        $this->budgetRepository->save([$budget]);
        return $this->budgetMetaDtoAssembler->assemble($budget);
    }

    public function includeAccount(Id $userId, Id $budgetId, Id $accountId): BudgetMetaDto
    {
        $account = $this->accountRepository->get($accountId);
        if (!$account->getUserId()->isEqual($userId)) {
            throw new AccessDeniedException();
        }

        $budget = $this->budgetRepository->get($budgetId);
        $budget->includeAccount($account);
        $this->budgetRepository->save([$budget]);
        return $this->budgetMetaDtoAssembler->assemble($budget);
    }

    public function resetBudget(Id $userId, Id $budgetId, DateTimeInterface $startedAt): BudgetMetaDto
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
        return $this->budgetMetaDtoAssembler->assemble($budget);
    }

    public function getBudget($userId, $budgetId, DateTimeInterface $periodStart): BudgetDto
    {
        $budget = $this->budgetRepository->get($budgetId);
        $dto = $this->budgetDtoAssembler->assemble($userId, $budget, $periodStart);
        // --- dump ---
        echo '<pre>';
        echo __FILE__ . chr(10);
        echo __METHOD__ . chr(10);
        var_dump($dto->structure);
        echo '</pre>';
        exit;
        // --- // ---
    }

    public function getData(Id $userId, Id $budgetId, DateTimeInterface $period): BudgetDataDto
    {
        return $this->budgetDataService->getData($userId, $budgetId, $period);
    }
}
