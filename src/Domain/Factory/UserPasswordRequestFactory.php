<?php

declare(strict_types=1);

namespace App\Domain\Factory;

use App\Domain\Entity\UserPasswordRequest;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Entity\ValueObject\UserPasswordRequestCode;
use App\Domain\Repository\UserPasswordRequestRepositoryInterface;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\Service\DatetimeServiceInterface;

readonly class UserPasswordRequestFactory implements PasswordUserRequestFactoryInterface
{
    public function __construct(private DatetimeServiceInterface $datetimeService, private UserPasswordRequestRepositoryInterface $passwordUserRequestRepository, private UserRepositoryInterface $userRepository)
    {
    }

    public function create(Id $userId): UserPasswordRequest
    {
        return new UserPasswordRequest(
            $this->passwordUserRequestRepository->getNextIdentity(),
            $this->userRepository->getReference($userId),
            UserPasswordRequestCode::generate(),
            $this->datetimeService->getCurrentDatetime()
        );
    }
}
