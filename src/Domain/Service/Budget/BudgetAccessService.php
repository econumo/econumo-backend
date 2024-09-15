<?php

declare(strict_types=1);


namespace App\Domain\Service\Budget;


use App\Domain\Entity\ValueObject\Id;
use App\Domain\Entity\ValueObject\UserRole;
use App\Domain\Exception\AccessDeniedException;
use App\Domain\Repository\BudgetRepositoryInterface;

readonly class BudgetAccessService implements BudgetAccessServiceInterface
{
    public function __construct(
        private BudgetRepositoryInterface $budgetRepository,
    ) {
    }

    public function canReadBudget(Id $userId, Id $budgetId): bool
    {
        try {
            $this->getBudgetAccess($userId, $budgetId);
        } catch (AccessDeniedException $e) {
            return false;
        }

        return true;
    }

    private function getBudgetAccess(Id $userId, Id $budgetId): UserRole
    {
        $budget = $this->budgetRepository->get($budgetId);
        if ($budget->getUser()->getId()->isEqual($userId)) {
            return UserRole::admin();
        }

        $accessList = $budget->getAccessList();
        foreach ($accessList as $access) {
            if ($access->getUserId()->isEqual($userId)) {
                if (!$access->isAccepted()) {
                    throw new AccessDeniedException();
                }
                return $access->getRole();
            }
        }
        throw new AccessDeniedException();
    }
}
