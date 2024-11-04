<?php

declare(strict_types=1);


namespace App\EconumoOneBundle\Domain\Factory;


use App\EconumoOneBundle\Domain\Entity\BudgetAccess;
use App\EconumoOneBundle\Domain\Entity\ValueObject\BudgetUserRole;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Entity\ValueObject\UserRole;
use App\EconumoOneBundle\Domain\Repository\BudgetRepositoryInterface;
use App\EconumoOneBundle\Domain\Repository\UserRepositoryInterface;
use App\EconumoOneBundle\Domain\Service\DatetimeServiceInterface;

readonly class BudgetAccessFactory implements BudgetAccessFactoryInterface
{
    public function __construct(
        private DatetimeServiceInterface $datetimeService,
        private BudgetRepositoryInterface $budgetRepository,
        private UserRepositoryInterface $userRepository,
    ) {
    }

    public function create(Id $budgetId, Id $userId, BudgetUserRole $role): BudgetAccess
    {
        return new BudgetAccess(
            $this->budgetRepository->getReference($budgetId),
            $this->userRepository->getReference($userId),
            UserRole::createFromAlias($role->getAlias()),
            $this->datetimeService->getCurrentDatetime()
        );
    }
}
