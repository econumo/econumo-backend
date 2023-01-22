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
    private DatetimeServiceInterface $datetimeService;

    private PasswordUserRequestRepositoryInterface $passwordUserRequestRepository;

    private UserRepositoryInterface $userRepository;

    public function __construct(
        DatetimeServiceInterface $datetimeService,
        PasswordUserRequestRepositoryInterface $passwordUserRequestRepository,
        UserRepositoryInterface $userRepository
    ) {
        $this->datetimeService = $datetimeService;
        $this->passwordUserRequestRepository = $passwordUserRequestRepository;
        $this->userRepository = $userRepository;
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
