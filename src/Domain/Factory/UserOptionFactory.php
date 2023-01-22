<?php

declare(strict_types=1);

namespace App\Domain\Factory;

use App\Domain\Entity\User;
use App\Domain\Entity\UserOption;
use App\Domain\Repository\UserOptionRepositoryInterface;
use App\Domain\Service\DatetimeServiceInterface;

class UserOptionFactory implements UserOptionFactoryInterface
{
    private UserOptionRepositoryInterface $userOptionRepository;

    private DatetimeServiceInterface $datetimeService;

    public function __construct(
        UserOptionRepositoryInterface $userOptionRepository,
        DatetimeServiceInterface $datetimeService
    ) {
        $this->userOptionRepository = $userOptionRepository;
        $this->datetimeService = $datetimeService;
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
