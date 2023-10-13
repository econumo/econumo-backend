<?php

declare(strict_types=1);


namespace App\Domain\Service\Budget;


use App\Domain\Entity\Plan;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Entity\ValueObject\PlanFolderName;
use App\Domain\Entity\ValueObject\PlanName;
use App\Domain\Entity\ValueObject\UserRole;
use App\Domain\Exception\NotFoundException;
use App\Domain\Exception\PlanAlreadyExistsException;
use App\Domain\Exception\RevokeOwnerAccessException;
use App\Domain\Factory\EnvelopeFactoryInterface;
use App\Domain\Factory\EnvelopeFolderFactoryInterface;
use App\Domain\Factory\PlanAccessFactoryInterface;
use App\Domain\Factory\PlanFactoryInterface;
use App\Domain\Factory\PlanOptionsFactoryInterface;
use App\Domain\Repository\AccountRepositoryInterface;
use App\Domain\Repository\CategoryRepositoryInterface;
use App\Domain\Repository\CurrencyRepositoryInterface;
use App\Domain\Repository\EnvelopeRepositoryInterface;
use App\Domain\Repository\PlanAccessRepositoryInterface;
use App\Domain\Repository\PlanFolderRepositoryInterface;
use App\Domain\Repository\PlanOptionsRepositoryInterface;
use App\Domain\Repository\PlanRepositoryInterface;
use App\Domain\Repository\TagRepositoryInterface;
use App\Domain\Repository\UserOptionRepositoryInterface;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\Service\AntiCorruptionServiceInterface;
use App\Domain\Service\Dto\PlanDto;
use App\Domain\Service\UserServiceInterface;

readonly class PlanService implements PlanServiceInterface
{
    public function __construct(
        private AntiCorruptionServiceInterface $antiCorruptionService,
        private PlanFactoryInterface $planFactory,
        private PlanRepositoryInterface $planRepository,
        private PlanOptionsRepositoryInterface $planOptionsRepository,
        private PlanOptionsFactoryInterface $planOptionsFactory,
        private PlanAccessRepositoryInterface $planAccessRepository,
        private UserServiceInterface $userService,
        private UserRepositoryInterface $userRepository,
        private UserOptionRepositoryInterface $userOptionRepository,
        private PlanAccessFactoryInterface $planAccessFactory,
        private PlanFolderRepositoryInterface $planFolderRepository,
        private EnvelopeRepositoryInterface $envelopeRepository,
        private CategoryRepositoryInterface $categoryRepository,
        private TagRepositoryInterface $tagRepository,
        private AccountRepositoryInterface $accountRepository,
        private EnvelopeFactoryInterface $envelopeFactory,
        private CurrencyRepositoryInterface $currencyRepository,
        private EnvelopeFolderFactoryInterface $envelopeFolderFactory
    ) {
    }

    public function createPlan(Id $userId, PlanName $name): Plan
    {
        $this->antiCorruptionService->beginTransaction(__METHOD__);
        try {
            $plans = $this->planRepository->findByOwnerId($userId);
            foreach ($plans as $plan) {
                if ($plan->getName()->isEqual($name)) {
                    throw new PlanAlreadyExistsException();
                }
            }

            $userPlanOptions = $this->planOptionsRepository->getByUserId($userId);
            $position = 0;
            foreach ($userPlanOptions as $option) {
                if ($option->getPosition() > $position) {
                    $position = $option->getPosition();
                }
            }

            if ($position === 0) {
                $position = count($this->planRepository->getAvailableForUserId($userId));
            } else {
                $position++;
            }

            $plan = $this->planFactory->create($userId, $name);
            $this->planRepository->save([$plan]);

            $planOptions = $this->planOptionsFactory->create($plan->getId(), $userId, $position);
            $this->planOptionsRepository->save([$planOptions]);

            $categoriesFolder = $this->envelopeFolderFactory->create($plan->getId(), new PlanFolderName('Expenses'), 0);
            $tagsFolder = $this->envelopeFolderFactory->create($plan->getId(), new PlanFolderName('Tags'), 1);
            $this->planFolderRepository->save([$categoriesFolder, $tagsFolder]);

            $user = $this->userRepository->get($userId);
            $userCurrency = $this->currencyRepository->getByCode($user->getCurrency());
            $envelopes = [];
            $categories = $this->categoryRepository->findByOwnerId($userId);
            $envelopePosition = 0;
            foreach ($categories as $category) {
                $envelopes[] = $this->envelopeFactory->createFromCategory($plan->getId(), $category, $userCurrency->getId(), $envelopePosition++, ($category->getType()->isIncome() ? null : $categoriesFolder->getId()));
            }
            $tags = $this->tagRepository->findByOwnerId($userId);
            foreach ($tags as $tag) {
                $envelopes[] = $this->envelopeFactory->createFromTag($plan->getId(), $tag, $userCurrency->getId(), $envelopePosition++, $tagsFolder->getId());
            }
            $this->envelopeRepository->save($envelopes);

            $this->userService->updateDefaultPlan($userId, $plan->getId());
            $this->antiCorruptionService->commit(__METHOD__);
        } catch (\Throwable $e) {
            $this->antiCorruptionService->rollback(__METHOD__);
            throw $e;
        }

        return $plan;
    }

    /**
     * @inheritDoc
     */
    public function orderPlans(Id $userId, array $changes): void
    {
        $this->antiCorruptionService->beginTransaction(__METHOD__);
        try {
            $plans = $this->planRepository->getAvailableForUserId($userId);
            $changed = [];
            foreach ($plans as $plan) {
                foreach ($changes as $change) {
                    if ($plan->getId()->isEqual($change->getId())) {
                        try {
                            $options = $this->planOptionsRepository->get($plan->getId(), $userId);
                            $options->updatePosition($change->position);
                        } catch (NotFoundException $e) {
                            $options = $this->planOptionsFactory->create($plan->getId(), $userId, $change->position);
                        }
                        $changed[] = $options;
                        break;
                    }
                }
            }

            if ($changed === []) {
                return;
            }

            $this->planOptionsRepository->save($changed);
            $this->antiCorruptionService->commit(__METHOD__);
        } catch (\Throwable $e) {
            $this->antiCorruptionService->rollback(__METHOD__);
            throw $e;
        }
    }

    public function deletePlan(Id $userId, Id $planId): void
    {
        $this->antiCorruptionService->beginTransaction(__METHOD__);
        try {
            $plan = $this->planRepository->get($planId);

            if ($plan->getOwnerUserId()->isEqual($userId)) {
                $access = $this->planAccessRepository->getByPlanId($planId);
                foreach ($access as $item) {
                    $this->updateUserDefaultPlanWhenItWasDeleted($item->getUserId(), $item->getPlanId());
                }
                $this->planRepository->delete($planId);
            } else {
                $access = $this->planAccessRepository->get($planId, $userId);
                $this->planAccessRepository->delete($access);
                try {
                    $options = $this->planOptionsRepository->get($planId, $userId);
                    $this->planOptionsRepository->delete($options);
                } catch (NotFoundException $e) {
                }
            }
            $this->updateUserDefaultPlanWhenItWasDeleted($userId, $planId);
            $this->antiCorruptionService->commit(__METHOD__);
        } catch (\Throwable $e) {
            $this->antiCorruptionService->rollback(__METHOD__);
            throw $e;
        }
    }

    public function updatePlan(Id $planId, PlanName $name): Plan
    {
        $plan = $this->planRepository->get($planId);
        $plan->updateName($name);
        $this->planRepository->save([$plan]);

        return $plan;
    }

    private function updateUserDefaultPlanWhenItWasDeleted(Id $userId, Id $planId): void
    {
        $user = $this->userRepository->get($userId);
        if (!$user->getDefaultPlanId() || $user->getDefaultPlanId()->isEqual($planId)) {
            $availablePlans = $this->planRepository->getAvailableForUserId($userId);
            $planUpdated = false;
            if (count($availablePlans) > 0) {
                foreach ($availablePlans as $availablePlan) {
                    if ($availablePlan->getId()->isEqual($planId)) {
                        continue;
                    }
                    $user->updateDefaultPlan($availablePlan->getId());
                    $planUpdated = true;
                }
            }
            if (!$planUpdated) {
                $user->updateDefaultPlan(null);
            }
            $this->userRepository->save([$user]);
        }
    }

    public function revokeAccess(Id $planId, Id $sharedUserId): void
    {
        $plan = $this->planRepository->get($planId);
        if ($plan->getOwnerUserId()->isEqual($sharedUserId)) {
            throw new RevokeOwnerAccessException();
        }
        $access = $this->planAccessRepository->get($planId, $sharedUserId);
        $this->planAccessRepository->delete($access);
    }

    public function grantAccess(Id $planId, Id $sharedUserId, UserRole $role): void
    {
        try {
            $access = $this->planAccessRepository->get($planId, $sharedUserId);
            $access->updateRole($role);
        } catch (NotFoundException) {
            $access = $this->planAccessFactory->create($planId, $sharedUserId, $role);
        }

        $this->planAccessRepository->save([$access]);
    }

    public function acceptAccess(Id $planId, Id $userId): void
    {
        $access = $this->planAccessRepository->get($planId, $userId);
        $access->accept();
        $this->planAccessRepository->save([$access]);
    }

    public function getPlan(Id $planId): PlanDto
    {
        $plan = $this->planRepository->get($planId);
        $dto = new PlanDto();
        $dto->id = $plan->getId();
        $dto->name = $plan->getName();
        $dto->ownerUserId = $plan->getOwnerUserId();
        $dto->createdAt = $plan->getCreatedAt();
        $dto->updatedAt = $plan->getUpdatedAt();
        $dto->folders = [];
        foreach ($this->planFolderRepository->getByPlanId($planId) as $item) {
            $dto->folders[] = $item->getId();
        }
        $dto->envelopes = [];
        foreach ($this->envelopeRepository->getByPlanId($planId) as $item) {
            $dto->envelopes[] = $item->getId();
            $dto->categories[$item->getId()->getValue()] = [];
            foreach ($item->getCategories() as $category) {
                $dto->categories[$item->getId()->getValue()][] = $category->getId();
            }
            $dto->tags[$item->getId()->getValue()] = [];
            foreach ($item->getTags() as $tag) {
                $dto->tags[$item->getId()->getValue()][] = $tag->getId();
            }
        }
        $planAccess = $this->planAccessRepository->getByPlanId($planId);
        foreach ($planAccess as $item) {
            $dto->sharedAccess[] = $item->getUserId();
        }
        $dto->currencies = [];
        foreach ($planAccess as $item) {
            if ($item->isAccepted()) {
                foreach ($this->accountRepository->getUserAccounts($item->getUserId()) as $account) {
                    if ($account->isExcludedFromBudgeting()) {
                        continue;
                    }
                    if (!in_array($account->getCurrencyId(), $dto->currencies)) {
                        $dto->currencies[] = $account->getCurrencyId();
                    }
                }
            }
        }
        foreach ($this->accountRepository->getUserAccounts($plan->getOwnerUserId()) as $account) {
            if ($account->isExcludedFromBudgeting()) {
                continue;
            }
            if (!in_array($account->getCurrencyId(), $dto->currencies)) {
                $dto->currencies[] = $account->getCurrencyId();
            }
        }

        return $dto;
    }
}
