<?php

declare(strict_types=1);


namespace App\EconumoBundle\Domain\Service\Budget;


use App\EconumoBundle\Domain\Entity\BudgetAccess;
use App\EconumoBundle\Domain\Entity\ValueObject\BudgetUserRole;
use App\EconumoBundle\Domain\Entity\ValueObject\Id;
use App\EconumoBundle\Domain\Entity\ValueObject\UserRole;
use App\EconumoBundle\Domain\Exception\AccessDeniedException;
use App\EconumoBundle\Domain\Factory\BudgetAccessFactoryInterface;
use App\EconumoBundle\Domain\Repository\BudgetAccessRepositoryInterface;
use App\EconumoBundle\Domain\Repository\BudgetElementRepositoryInterface;
use App\EconumoBundle\Domain\Repository\BudgetRepositoryInterface;
use App\EconumoBundle\Domain\Repository\UserRepositoryInterface;
use App\EconumoBundle\Domain\Service\AntiCorruptionServiceInterface;
use App\EconumoBundle\Domain\Service\Connection\ConnectionServiceInterface;
use App\EconumoBundle\Domain\Service\UserServiceInterface;

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
        $usersMap[$budget->getUser()->getId()->getValue()] = $budget->getUser()->getId();

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
        $access = null;
        $budgetInvites = $this->budgetAccessRepository->getByBudgetId($budgetId);
        foreach ($budgetInvites as $budgetInvite) {
            if ($budgetInvite->getUserId()->isEqual($userId)) {
                $access = $budgetInvite;
                break;
            }
        }
        if ($access === null) {
            return;
        }

        $this->removeUserAccess($access);
    }

    /**
     * @param BudgetAccess $access
     * @return void
     * @throws \Throwable
     */
    private function removeUserAccess(BudgetAccess $access): void {
        $this->antiCorruptionService->beginTransaction(__METHOD__);
        try {
            if (!$access->getRole()->isReader() && $access->isAccepted()) {
                $this->budgetElementService->deleteCategoriesElements($access->getUserId(), $access->getBudgetId());
                $this->budgetElementService->deleteTagsElements($access->getUserId(), $access->getBudgetId());
            }
            $this->budgetAccessRepository->delete([$access]);
            $user = $this->userRepository->get($access->getUserId());
            if ($user->getDefaultPlanId() && $user->getDefaultPlanId()->isEqual($access->getBudgetId())) {
                $availableBudgets = $this->budgetRepository->getByUserId($access->getUserId());
                $newBudgetId = null;
                foreach ($availableBudgets as $budget) {
                    if (!$budget->getId()->isEqual($access->getBudgetId()) && $budget->isUserAccepted($access->getUserId())) {
                        $newBudgetId = $budget->getId();
                        break;
                    }
                }
                $this->userService->updateBudget($access->getUserId(), $newBudgetId);
            }

            $this->antiCorruptionService->commit(__METHOD__);
        } catch (\Throwable $exception) {
            $this->antiCorruptionService->rollback(__METHOD__);
            throw $exception;
        }
    }
}
