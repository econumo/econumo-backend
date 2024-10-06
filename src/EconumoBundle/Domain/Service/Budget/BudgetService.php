<?php

declare(strict_types=1);

namespace App\EconumoBundle\Domain\Service\Budget;

use App\EconumoBundle\Domain\Entity\ValueObject\BudgetName;
use App\EconumoBundle\Domain\Entity\ValueObject\Id;
use App\EconumoBundle\Domain\Exception\AccessDeniedException;
use App\EconumoBundle\Domain\Factory\BudgetFactoryInterface;
use App\EconumoBundle\Domain\Repository\AccountRepositoryInterface;
use App\EconumoBundle\Domain\Repository\BudgetEntityAmountRepositoryInterface;
use App\EconumoBundle\Domain\Repository\BudgetRepositoryInterface;
use App\EconumoBundle\Domain\Repository\CurrencyRepositoryInterface;
use App\EconumoBundle\Domain\Repository\UserRepositoryInterface;
use App\EconumoBundle\Domain\Service\AntiCorruptionServiceInterface;
use App\EconumoBundle\Domain\Service\Budget\Assembler\BudgetDtoAssembler;
use App\EconumoBundle\Domain\Service\Budget\Assembler\BudgetMetaDtoAssembler;
use App\EconumoBundle\Domain\Service\Budget\BudgetDataService;
use App\EconumoBundle\Domain\Service\Budget\BudgetServiceInterface;
use App\EconumoBundle\Domain\Service\Budget\Dto\BudgetDto;
use App\EconumoBundle\Domain\Service\Budget\Dto\BudgetMetaDto;
use App\EconumoBundle\Domain\Service\Budget\Dto\BudgetDataDto;
use App\EconumoBundle\Domain\Service\Budget\BudgetEntityServiceInterface;
use App\EconumoBundle\Domain\Service\DatetimeServiceInterface;
use App\EconumoBundle\Domain\Service\UserServiceInterface;
use App\EconumoBundle\Domain\Service\Budget\BudgetDeletionService;
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
        private UserRepositoryInterface $userRepository,
        private CurrencyRepositoryInterface $currencyRepository,
    ) {
    }

    public function createBudget(
        Id $userId,
        Id $budgetId,
        BudgetName $name,
        ?DateTimeInterface $startDate,
        ?Id $currencyId,
        array $excludedAccountsIds = []
    ): BudgetDto {
        $this->antiCorruptionService->beginTransaction(__METHOD__);
        try {
            $date = $startDate !== null ? $startDate : $this->datetimeService->getCurrentDatetime();
            if ($currencyId === null) {
                $user = $this->userRepository->get($userId);
                $currency = $this->currencyRepository->getByCode($user->getCurrency());
            } else {
                $currency = $this->currencyRepository->getReference($currencyId);
            }
            $budget = $this->budgetFactory->create(
                $userId,
                $budgetId,
                $name,
                $date,
                $currency->getId(),
                $excludedAccountsIds,
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

        return $this->budgetDtoAssembler->assemble($userId, $budget, $this->datetimeService->getCurrentDatetime());
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

        return $dto;
    }

    public function getData(Id $userId, Id $budgetId, DateTimeInterface $period): BudgetDataDto
    {
        return $this->budgetDataService->getData($userId, $budgetId, $period);
    }
}
