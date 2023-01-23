<?php

declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Entity\ValueObject\Email;
use App\Domain\Factory\PasswordUserRequestFactoryInterface;
use App\Domain\Repository\PasswordUserRequestRepositoryInterface;
use App\Domain\Repository\UserRepositoryInterface;

class PasswordUserRequestService implements PasswordUserRequestServiceInterface
{
    public function __construct(private readonly PasswordUserRequestFactoryInterface $passwordUserRequestFactory, private readonly PasswordUserRequestRepositoryInterface $passwordUserRequestRepository, private readonly UserRepositoryInterface $userRepository, private readonly EventDispatcherInterface $eventDispatcher)
    {
    }

    public function remindPassword(Email $email): void
    {
        $user = $this->userRepository->getByEmail($email);
        $passwordUserRequest = $this->passwordUserRequestFactory->create($user->getId());
        $this->passwordUserRequestRepository->save([$passwordUserRequest]);
        $this->eventDispatcher->dispatchAll($passwordUserRequest->releaseEvents());
    }
}
