<?php

declare(strict_types=1);

namespace App\Domain\Service\Budget;

use App\Domain\Entity\Account;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\AccountRepositoryInterface;
use App\Domain\Repository\PlanAccessRepositoryInterface;
use App\Domain\Repository\PlanRepositoryInterface;

readonly class PlanAccountsService
{
    public function __construct(
        private PlanAccessRepositoryInterface $planAccessRepository,
        private PlanRepositoryInterface $planRepository,
        private AccountRepositoryInterface $accountRepository,
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
        $accessUserId = [];
        foreach ($access as $planAccess) {
            if (!$planAccess->isAccepted()) {
                continue;
            }
            $accessUserId[$planAccess->getUserId()->getValue()] = $planAccess->getUserId();

            $accounts = $this->accountRepository->getUserAccountsForBudgeting($planAccess->getUserId());
            foreach ($accounts as $account) {
                $result[$account->getId()->getValue()] = $account;
            }
        }

        $plan = $this->planRepository->get($planId);
        $accessUserId[$plan->getOwnerUserId()->getValue()] = $plan->getOwnerUserId();
        $accounts = $this->accountRepository->getUserAccountsForBudgeting($plan->getOwnerUserId());
        foreach ($accounts as $account) {
            if (!isset($accessUserId[$account->getUserId()->getValue()])) {
                continue;
            }

            $result[$account->getId()->getValue()] = $account;
        }

        $result = array_values($result);
        usort($result, fn (Account $a, Account $b) => $a->getBalance() <=> $b->getBalance());
        return array_reverse($result);
    }

    /**
     * @param Id $planId
     * @return Account[]
     */
    public function getHoardAccountsForPlanId(Id $planId): array
    {
        $result = [];
        $access = $this->planAccessRepository->getByPlanId($planId);
        $accessUserId = [];
        foreach ($access as $planAccess) {
            if (!$planAccess->isAccepted()) {
                continue;
            }
            $accessUserId[$planAccess->getUserId()->getValue()] = $planAccess->getUserId();

            $accounts = $this->accountRepository->getExcludedUserAccountsForBudgeting($planAccess->getUserId());
            foreach ($accounts as $account) {
                $result[$account->getId()->getValue()] = $account;
            }
        }

        $plan = $this->planRepository->get($planId);
        $accessUserId[$plan->getOwnerUserId()->getValue()] = $plan->getOwnerUserId();
        $accounts = $this->accountRepository->getExcludedUserAccountsForBudgeting($plan->getOwnerUserId());
        foreach ($accounts as $account) {
            if (!isset($accessUserId[$account->getUserId()->getValue()])) {
                continue;
            }

            $result[$account->getId()->getValue()] = $account;
        }

        $result = array_values($result);
        usort($result, fn (Account $a, Account $b) => $a->getBalance() <=> $b->getBalance());
        return array_reverse($result);
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
