<?php

declare(strict_types=1);

namespace App\Domain\Factory;

use App\Domain\Entity\PasswordUserRequest;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\PasswordUserRequestRepositoryInterface;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\Service\DatetimeServiceInterface;

class PasswordUserRequestFactory implements PasswordUserRequestFactoryInterface
{
    public function __construct(private readonly DatetimeServiceInterface $datetimeService, private readonly PasswordUserRequestRepositoryInterface $passwordUserRequestRepository, private readonly UserRepositoryInterface $userRepository)
    {
    }

    public function create(Id $userId): PasswordUserRequest
    {
        return new PasswordUserRequest(
            $this->passwordUserRequestRepository->getNextIdentity(),
            $this->userRepository->getReference($userId),
            md5(uniqid(PasswordUserRequest::class)),
            $this->datetimeService->getCurrentDatetime()
        );
    }
}
