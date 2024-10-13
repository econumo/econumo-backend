<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Domain\Factory;

use App\EconumoOneBundle\Domain\Entity\User;
use App\EconumoOneBundle\Domain\Entity\UserOption;
use App\EconumoOneBundle\Domain\Repository\UserOptionRepositoryInterface;
use App\EconumoOneBundle\Domain\Service\DatetimeServiceInterface;
use App\EconumoOneBundle\Domain\Factory\UserOptionFactoryInterface;

class UserOptionFactory implements UserOptionFactoryInterface
{
    public function __construct(private readonly UserOptionRepositoryInterface $userOptionRepository, private readonly DatetimeServiceInterface $datetimeService)
    {
    }

    public function create(User $user, string $name, ?string $value): UserOption
    {
        return new UserOption(
            $this->userOptionRepository->getNextIdentity(),
            $user,
            $name,
            $value,
            $this->datetimeService->getCurrentDatetime()
        );
    }
}
