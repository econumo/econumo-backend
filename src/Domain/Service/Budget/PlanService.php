<?php

declare(strict_types=1);


namespace App\Domain\Service\Budget;


use App\Domain\Entity\Plan;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Entity\ValueObject\PlanName;
use App\Domain\Exception\PlanAlreadyExistsException;
use App\Domain\Factory\PlanFactoryInterface;
use App\Domain\Factory\PlanOptionsFactoryInterface;
use App\Domain\Repository\PlanAccessRepositoryInterface;
use App\Domain\Repository\PlanOptionsRepositoryInterface;
use App\Domain\Repository\PlanRepositoryInterface;
use App\Domain\Service\AntiCorruptionServiceInterface;

readonly class PlanService implements PlanServiceInterface
{
    public function __construct(
        private AntiCorruptionServiceInterface $antiCorruptionService,
        private PlanFactoryInterface $planFactory,
        private PlanRepositoryInterface $planRepository,
        private PlanOptionsRepositoryInterface $planOptionsRepository,
        private PlanOptionsFactoryInterface $planOptionsFactory,
        private PlanAccessRepositoryInterface $planAccessRepository
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
            }

            $plan = $this->planFactory->create($userId, $name);
            $this->planRepository->save([$plan]);

            $planOptions = $this->planOptionsFactory->create($plan->getId(), $userId, $position);
            $this->planOptionsRepository->save([$planOptions]);
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
                        $options = $this->planOptionsRepository->get($plan->getId(), $userId);
                        $options->updatePosition($change->position);
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
        $plan = $this->planRepository->get($planId);
        if ($plan->getUserId()->isEqual($userId)) {
            $this->planRepository->delete($planId);
        } else {
            $access = $this->planAccessRepository->get($planId, $userId);
            $this->planAccessRepository->delete($access);
        }
    }

    public function updatePlan(Id $planId, PlanName $name): Plan
    {
        $plan = $this->planRepository->get($planId);
        $plan->updateName($name);
        $this->planRepository->save([$plan]);

        return $plan;
    }
}
