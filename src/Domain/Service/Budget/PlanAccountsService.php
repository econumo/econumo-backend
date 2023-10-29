<?php

declare(strict_types=1);

namespace App\Domain\Service\Budget;

use App\Domain\Entity\Account;
use App\Domain\Entity\ValueObject\Id;
use App\Infrastructure\Doctrine\Repository\AccountRepository;
use App\Infrastructure\Doctrine\Repository\PlanAccessRepository;
use App\Infrastructure\Doctrine\Repository\PlanRepository;

readonly class PlanAccountsService
{
    public function __construct(
        private PlanAccessRepository $planAccessRepository,
        private PlanRepository $planRepository,
        private AccountRepository $accountRepository,
    ) {
    }

    /**
     * @param Id $planId
     * @return Account[]
     */
    public function getAvailableAccountsForPlanId(Id $planId): array
    {
        $result = [];
        $access = $this->planAccessRepository->getByPlanId($planId);
        foreach ($access as $planAccess) {
            if (!$planAccess->isAccepted()) {
                continue;
            }

            $accounts = $this->accountRepository->getUserAccounts($planAccess->getUserId());
            foreach ($accounts as $account) {
                if (!$account->isExcludedFromBudget()) {
                    $result[$account->getId()->getValue()] = $account;
                }
            }
        }

        $plan = $this->planRepository->get($planId);
        $accounts = $this->accountRepository->getUserAccounts($plan->getOwnerUserId());
        foreach ($accounts as $account) {
            $result[$account->getId()->getValue()] = $account;
        }

        return array_values($result);
    }

    /**
     * @param Id $planId
     * @return Id[]
     */
    public function getAvailableCurrencyIdsForPlanId(Id $planId): array
    {
        $result = [];
        foreach ($this->getAvailableAccountsForPlanId($planId) as $account) {
            $result[$account->getCurrencyId()->getValue()] = $account->getCurrencyId();
        }

        return array_values($result);
    }
}
