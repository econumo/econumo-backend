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
use App\Domain\Service\Budget\Assembler\BudgetStructureDtoAssembler;
use App\Domain\Service\Budget\Assembler\BudgetDtoAssembler;
use App\Domain\Service\Budget\Dto\BudgetStructureDto;
use App\Domain\Service\Budget\Dto\BudgetDto;
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
        private BudgetStructureDtoAssembler $budgetDtoAssembler,
        private BudgetEntityServiceInterface $budgetEntityService,
        private BudgetDtoAssembler $budgetPreviewDtoAssembler,
        private BudgetDeletionService $budgetDeletionService,
        private AccountRepositoryInterface $accountRepository,
        private BudgetEntityAmountRepositoryInterface $budgetEntityAmountRepository,
        private BudgetDataService $budgetDataService,
        private BudgetStructureService $budgetStructureService
    ) {
    }

    public function createBudget(Id $userId, Id $budgetId, BudgetName $name, array $excludedAccountsIds = []): BudgetStructureDto
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

    public function getStructure(Id $userId, Id $budgetId): BudgetStructureDto
    {
        return $this->budgetStructureService->getBudgetStructure($userId, $budgetId);
    }

    public function getBudgetList(Id $userId): array
    {
        $budgets = $this->budgetRepository->getByUserId($userId);
        $result = [];
        foreach ($budgets as $budget) {
            $result[] = $this->budgetPreviewDtoAssembler->assemble($userId, $budget);
        }

        return $result;
    }

    public function deleteBudget(Id $budgetId): void
    {
        $this->budgetDeletionService->deleteBudget($budgetId);
    }

    public function updateBudget(Id $userId, Id $budgetId, BudgetName $name): BudgetDto
    {
        $budget = $this->budgetRepository->get($budgetId);
        $budget->updateName($name);
        $this->budgetRepository->save([$budget]);
        return $this->budgetPreviewDtoAssembler->assemble($userId, $budget);
    }

    public function excludeAccount(Id $userId, Id $budgetId, Id $accountId): BudgetDto
    {
        $account = $this->accountRepository->get($accountId);
        if (!$account->getUserId()->isEqual($userId)) {
            throw new AccessDeniedException();
        }

        $budget = $this->budgetRepository->get($budgetId);
        $budget->excludeAccount($account);
        $this->budgetRepository->save([$budget]);
        return $this->budgetPreviewDtoAssembler->assemble($userId, $budget);
    }

    public function includeAccount(Id $userId, Id $budgetId, Id $accountId): BudgetDto
    {
        $account = $this->accountRepository->get($accountId);
        if (!$account->getUserId()->isEqual($userId)) {
            throw new AccessDeniedException();
        }

        $budget = $this->budgetRepository->get($budgetId);
        $budget->includeAccount($account);
        $this->budgetRepository->save([$budget]);
        return $this->budgetPreviewDtoAssembler->assemble($userId, $budget);
    }

    public function resetBudget(Id $userId, Id $budgetId, DateTimeInterface $startedAt): BudgetDto
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
        return $this->budgetPreviewDtoAssembler->assemble($userId, $budget);
    }

    public function getData(Id $userId, Id $budgetId, DateTimeInterface $period): BudgetDataDto
    {
        return $this->budgetDataService->getData($userId, $budgetId, $period);
    }
}
