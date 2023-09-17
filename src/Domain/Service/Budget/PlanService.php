<?php

declare(strict_types=1);


namespace App\Domain\Service\Budget;


use App\Domain\Entity\Plan;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Entity\ValueObject\PlanName;
use App\Domain\Exception\PlanAlreadyExistsException;
use App\Domain\Factory\PlanFactoryInterface;
use App\Domain\Factory\PlanOptionsFactoryInterface;
use App\Domain\Repository\PlanOptionsRepositoryInterface;
use App\Domain\Repository\PlanRepositoryInterface;
use App\Domain\Service\AntiCorruptionServiceInterface;

class PlanService implements PlanServiceInterface
{
    public function __construct(
        private readonly AntiCorruptionServiceInterface $antiCorruptionService,
        private readonly PlanFactoryInterface $planFactory,
        private readonly PlanRepositoryInterface $planRepository,
        private readonly PlanOptionsRepositoryInterface $planOptionsRepository,
        private readonly PlanOptionsFactoryInterface $planOptionsFactory,
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
}
