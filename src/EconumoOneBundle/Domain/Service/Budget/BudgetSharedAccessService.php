<?php

declare(strict_types=1);


namespace App\EconumoOneBundle\Domain\Service\Budget;


use App\EconumoOneBundle\Domain\Entity\ValueObject\BudgetUserRole;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Entity\ValueObject\UserRole;
use App\EconumoOneBundle\Domain\Exception\AccessDeniedException;
use App\EconumoOneBundle\Domain\Factory\BudgetAccessFactoryInterface;
use App\EconumoOneBundle\Domain\Repository\BudgetAccessRepositoryInterface;
use App\EconumoOneBundle\Domain\Repository\BudgetElementRepositoryInterface;
use App\EconumoOneBundle\Domain\Repository\BudgetRepositoryInterface;
use App\EconumoOneBundle\Domain\Repository\UserRepositoryInterface;
use App\EconumoOneBundle\Domain\Service\AntiCorruptionServiceInterface;
use App\EconumoOneBundle\Domain\Service\Connection\ConnectionServiceInterface;
use App\EconumoOneBundle\Domain\Service\UserServiceInterface;

readonly class BudgetSharedAccessService implements BudgetSharedAccessServiceInterface
{
    public function __construct(
        private ConnectionServiceInterface $connectionService,
        private BudgetAccessFactoryInterface $budgetAccessFactory,
        private BudgetAccessRepositoryInterface $budgetAccessRepository,
        private BudgetRepositoryInterface $budgetRepository,
        private BudgetElementServiceInterface $budgetElementService,
        private BudgetElementRepositoryInterface $budgetElementRepository,
        private UserServiceInterface $userService,
        private AntiCorruptionServiceInterface $antiCorruptionService,
        private UserRepositoryInterface $userRepository,
    ) {
    }

    public function grantAccess(Id $ownerId, Id $budgetId, Id $invitedUserId, BudgetUserRole $role): void
    {
        $usersConnected = false;
        $connections = $this->connectionService->getUserList($ownerId);
        foreach ($connections as $connection) {
            if ($connection->getId()->isEqual($invitedUserId)) {
                $usersConnected = true;
                break;
            }
        }
        if (!$usersConnected) {
            throw new AccessDeniedException();
        }

        $invitation = null;
        $budgetInvites = $this->budgetAccessRepository->getByBudgetId($budgetId);
        foreach ($budgetInvites as $budgetInvite) {
            if ($budgetInvite->getUserId()->isEqual($invitedUserId)) {
                $invitation = $budgetInvite;
                break;
            }
        }
        if ($invitation === null) {
            $invitation = $this->budgetAccessFactory->create($budgetId, $invitedUserId, $role);
        } else {
            $invitation->updateRole(UserRole::createFromAlias($role->getAlias()));
        }
        $this->budgetAccessRepository->save([$invitation]);
    }

    public function acceptAccess(Id $budgetId, Id $invitedUserId): void
    {
        $invitation = null;
        $budgetInvites = $this->budgetAccessRepository->getByBudgetId($budgetId);
        foreach ($budgetInvites as $budgetInvite) {
            if ($budgetInvite->getUserId()->isEqual($invitedUserId)) {
                $invitation = $budgetInvite;
                break;
            }
        }
        if ($invitation === null) {
            throw new AccessDeniedException();
        }

        $budget = $this->budgetRepository->get($budgetId);
        $budgetAccessList = $budget->getAccessList();
        $usersMap = [];
        foreach ($budgetAccessList as $budgetAccess) {
            if (!$budgetAccess->isAccepted()) {
                continue;
            }
            if ($budgetAccess->getUserId()->isEqual($invitedUserId)) {
                continue;
            }
            $usersMap[$budgetAccess->getUserId()->getValue()] = $budgetAccess->getUserId();
        }

        $usersConnected = false;
        $connections = $this->connectionService->getUserList($invitedUserId);
        foreach ($connections as $connection) {
            if (array_key_exists($connection->getId()->getValue(), $usersMap)) {
                $usersConnected = true;
                break;
            }
        }
        if (!$usersConnected) {
            throw new AccessDeniedException();
        }

        $this->antiCorruptionService->beginTransaction(__METHOD__);
        try {
            $invitation->accept();
            $this->budgetAccessRepository->save([$invitation]);
            $this->userService->updateBudget($invitedUserId, $budgetId);
            if (!$invitation->getRole()->isReader()) {
                $position = $this->budgetElementRepository->getNextPosition($budgetId, null);
                [$position,] = $this->budgetElementService->createCategoriesElements(
                    $invitedUserId,
                    $budgetId,
                    $position
                );
                $this->budgetElementService->createTagsElements($invitedUserId, $budgetId, $position);
            }
            $this->antiCorruptionService->commit(__METHOD__);
        } catch (\Throwable $exception) {
            $this->antiCorruptionService->rollback(__METHOD__);
            throw $exception;
        }
    }

    public function revokeAccess(Id $budgetId, Id $userId): void
    {
        $invitation = null;
        $budgetInvites = $this->budgetAccessRepository->getByBudgetId($budgetId);
        foreach ($budgetInvites as $budgetInvite) {
            if ($budgetInvite->getUserId()->isEqual($userId)) {
                $invitation = $budgetInvite;
                break;
            }
        }
        if ($invitation === null) {
            return;
        }

        $this->antiCorruptionService->beginTransaction(__METHOD__);
        try {
            if ($invitation->getRole()->isReader() || !$invitation->isAccepted()) {
                $this->budgetAccessRepository->delete([$invitation]);
            } else {
                $this->budgetElementService->deleteCategoriesElements($invitation->getUserId(), $budgetId);
                $this->budgetElementService->deleteTagsElements($invitation->getUserId(), $budgetId);
            }
            $user = $this->userRepository->get($userId);
            if ($user->getDefaultPlanId() && $user->getDefaultPlanId()->isEqual($budgetId)) {
                $availableBudgets = $this->budgetRepository->getByUserId($userId);
                $newBudgetId = null;
                foreach ($availableBudgets as $budget) {
                    if (!$budget->getId()->isEqual($budgetId) && $budget->isUserAccepted($userId)) {
                        $newBudgetId = $budget->getId();
                        break;
                    }
                }
                $this->userService->updateBudget($userId, $newBudgetId);
            }

            $this->antiCorruptionService->commit(__METHOD__);
        } catch (\Throwable $exception) {
            $this->antiCorruptionService->rollback(__METHOD__);
            throw $exception;
        }
    }
}
