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
            $this->getBudgetRole($userId, $budgetId);
        } catch (AccessDeniedException $e) {
            return false;
        }

        return true;
    }

    public function getBudgetRole(Id $userId, Id $budgetId): UserRole
    {
        $budget = $this->budgetRepository->get($budgetId);
        if ($budget->getUser()->getId()->isEqual($userId)) {
            return UserRole::owner();
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

    public function canDeleteBudget(Id $userId, Id $budgetId): bool
    {
        try {
            $role = $this->getBudgetRole($userId, $budgetId);
            if ($role->isAdmin() || $role->isOwner()) {
                return true;
            }
        } catch (AccessDeniedException $e) {
            return false;
        }

        return false;
    }

    public function canUpdateBudget(Id $userId, Id $budgetId): bool
    {
        try {
            $role = $this->getBudgetRole($userId, $budgetId);
            if ($role->isOwner() || $role->isAdmin() || $role->isUser()) {
                return true;
            }
        } catch (AccessDeniedException $e) {
            return false;
        }

        return false;
    }

    public function canResetBudget(Id $userId, Id $budgetId): bool
    {
        try {
            $role = $this->getBudgetRole($userId, $budgetId);
            if ($role->isOwner() || $role->isAdmin()) {
                return true;
            }
        } catch (AccessDeniedException $e) {
            return false;
        }

        return false;
    }
}