<?php

declare(strict_types=1);


namespace App\Domain\Factory;


use App\Domain\Entity\PlanAccess;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Entity\ValueObject\UserRole;
use App\Domain\Repository\PlanRepositoryInterface;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\Service\DatetimeServiceInterface;

readonly class PlanAccessFactory implements PlanAccessFactoryInterface
{
    public function __construct(
        private PlanRepositoryInterface $planRepository,
        private UserRepositoryInterface $userRepository,
        private DatetimeServiceInterface $datetimeService
    ) {
    }

    public function create(Id $planId, Id $userId, UserRole $role): PlanAccess
    {
        return new PlanAccess(
            $this->planRepository->getReference($planId),
            $this->userRepository->getReference($userId),
            $role,
            $this->datetimeService->getCurrentDatetime()
        );
    }
}
