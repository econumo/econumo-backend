<?php

declare(strict_types=1);


namespace App\Domain\Factory;


use App\Domain\Entity\Plan;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Entity\ValueObject\PlanName;
use App\Domain\Repository\PlanRepositoryInterface;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\Service\DatetimeServiceInterface;

class PlanFactory implements PlanFactoryInterface
{
    public function __construct(
        private readonly DatetimeServiceInterface $datetimeService,
        private readonly PlanRepositoryInterface $planRepository,
        private readonly UserRepositoryInterface $userRepository
    ) {
    }

    public function create(Id $userId, PlanName $name): Plan
    {
        return new Plan(
            $this->planRepository->getNextIdentity(),
            $this->userRepository->getReference($userId),
            $name,
            $this->datetimeService->getCurrentDatetime()
        );
    }
}
