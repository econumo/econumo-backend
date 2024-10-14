<?php

declare(strict_types=1);


namespace App\EconumoOneBundle\Domain\Service\Budget;

use App\EconumoOneBundle\Domain\Entity\ValueObject\BudgetName;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Exception\AccessDeniedException;
use App\EconumoOneBundle\Domain\Repository\AccountRepositoryInterface;
use App\EconumoOneBundle\Domain\Repository\BudgetRepositoryInterface;
use App\EconumoOneBundle\Domain\Service\Budget\Assembler\BudgetMetaDtoAssembler;
use App\EconumoOneBundle\Domain\Service\Budget\Dto\BudgetMetaDto;

readonly class BudgetUpdateService
{
    public function __construct(
        private BudgetRepositoryInterface $budgetRepository,
        private BudgetMetaDtoAssembler $budgetMetaDtoAssembler,
        private AccountRepositoryInterface $accountRepository,
    ) {
    }

    /**
     * @param Id $userId
     * @param Id $budgetId
     * @param BudgetName $name
     * @param Id[] $excludedAccountsIds
     * @return BudgetMetaDto
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
        return $this->budgetMetaDtoAssembler->assemble($budget);
    }
}
