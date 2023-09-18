<?php

declare(strict_types=1);


namespace App\Domain\Service\Budget;


use App\Domain\Entity\ValueObject\Id;
use App\Domain\Entity\ValueObject\UserRole;
use App\Domain\Exception\AccessDeniedException;
use App\Domain\Repository\PlanAccessRepositoryInterface;
use App\Domain\Repository\PlanRepositoryInterface;

readonly class PlanAccessService implements PlanAccessServiceInterface
{
    public function __construct(
        private PlanRepositoryInterface $planRepository,
        private PlanAccessRepositoryInterface $planAccessRepository,
    ) {
    }

    public function canDeletePlan(Id $userId, Id $planId): bool
    {
        try {
            $this->getPlanUserRole($userId, $planId);
            return true;
        } catch (AccessDeniedException $e) {
            return false;
        }
    }

    public function canUpdatePlan(Id $userId, Id $planId): bool
    {
        try {
            $role = $this->getPlanUserRole($userId, $planId);
            return $role->isAdmin();
        } catch (AccessDeniedException $e) {
            return false;
        }
    }

    private function getPlanUserRole(Id $userId, Id $planId): UserRole
    {
        $plans = $this->planRepository->getAvailableForUserId($userId);
        $exists = false;
        $plan = null;
        foreach ($plans as $item) {
            if ($item->getId()->isEqual($planId)) {
                $plan = $item;
                $exists = true;
                break;
            }
        }
        if (!$exists) {
            throw new AccessDeniedException();
        }

        if ($plan->getUserId()->isEqual($userId)) {
            return UserRole::admin();
        }
        $access = $this->planAccessRepository->get($planId, $userId);
        return $access->getRole();
    }

    public function canManagePlanAccess(Id $userId, Id $planId): bool
    {
        try {
            $role = $this->getPlanUserRole($userId, $planId);
            return $role->isAdmin();
        } catch (AccessDeniedException $e) {
            return false;
        }
    }
}
