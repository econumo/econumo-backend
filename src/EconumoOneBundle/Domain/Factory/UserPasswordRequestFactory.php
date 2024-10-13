<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Domain\Factory;

use App\EconumoOneBundle\Domain\Entity\UserPasswordRequest;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Entity\ValueObject\UserPasswordRequestCode;
use App\EconumoOneBundle\Domain\Factory\PasswordUserRequestFactoryInterface;
use App\EconumoOneBundle\Domain\Repository\UserPasswordRequestRepositoryInterface;
use App\EconumoOneBundle\Domain\Repository\UserRepositoryInterface;
use App\EconumoOneBundle\Domain\Service\DatetimeServiceInterface;

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
