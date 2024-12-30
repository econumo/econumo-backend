<?php

declare(strict_types=1);


namespace App\EconumoBundle\Domain\Service\Budget;

use App\EconumoBundle\Domain\Entity\ValueObject\BudgetName;
use App\EconumoBundle\Domain\Entity\ValueObject\Id;
use App\EconumoBundle\Domain\Exception\AccessDeniedException;
use App\EconumoBundle\Domain\Repository\AccountRepositoryInterface;
use App\EconumoBundle\Domain\Repository\BudgetRepositoryInterface;
use App\EconumoBundle\Domain\Service\Budget\Builder\BudgetMetaBuilder;
use App\EconumoBundle\Domain\Service\Budget\Dto\BudgetMetaDto;

readonly class BudgetUpdateService
{
    public function __construct(
        private BudgetRepositoryInterface $budgetRepository,
        private BudgetMetaBuilder $budgetMetaBuilder,
        private AccountRepositoryInterface $accountRepository,
    ) {
    }

    /**
     * @param Id[] $excludedAccountsIds
     */
    public function updateBudget(
        Id $userId,
        Id $budgetId,
        BudgetName $name,
        array $excludedAccountsIds = []
    ): BudgetMetaDto {
        $accounts = [];
        foreach ($excludedAccountsIds as $excludedAccountId) {
            $account = $this->accountRepository->get($excludedAccountId);
            if (!$account->getUserId()->isEqual($userId)) {
                throw new AccessDeniedException();
            }

            $accounts[] = $account;
        }

        $budget = $this->budgetRepository->get($budgetId);
        $budget->updateName($name);

        $alreadyExcludedAccounts = $budget->getExcludedAccounts($userId);
        foreach ($alreadyExcludedAccounts as $alreadyExcludedAccount) {
            $budget->includeAccount($alreadyExcludedAccount);
        }

        foreach ($accounts as $account) {
            $budget->excludeAccount($account);
        }

        $this->budgetRepository->save([$budget]);
        return $this->budgetMetaBuilder->build($budget);
    }
}
