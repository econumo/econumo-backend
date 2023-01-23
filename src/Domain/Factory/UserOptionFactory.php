<?php

declare(strict_types=1);

namespace App\Domain\Factory;

use App\Domain\Entity\User;
use App\Domain\Entity\UserOption;
use App\Domain\Repository\UserOptionRepositoryInterface;
use App\Domain\Service\DatetimeServiceInterface;

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
