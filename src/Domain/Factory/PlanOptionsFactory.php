<?php

declare(strict_types=1);


namespace App\Domain\Factory;


use App\Domain\Entity\PlanOptions;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\PlanRepositoryInterface;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\Service\DatetimeServiceInterface;

class PlanOptionsFactory implements PlanOptionsFactoryInterface
{
    public function __construct(
        private readonly PlanRepositoryInterface $planRepository,
        private readonly UserRepositoryInterface $userRepository,
        private readonly DatetimeServiceInterface $datetimeService
    )
    {
    }

    public function create(Id $planId, Id $userId, int $position): PlanOptions
    {
        return new PlanOptions(
            $this->planRepository->getReference($planId),
            $this->userRepository->getReference($userId),
            $position,
            $this->datetimeService->getCurrentDatetime()
        );
    }
}
