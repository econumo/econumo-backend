<?php

declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Entity\ValueObject\Email;
use App\Domain\Factory\PasswordUserRequestFactoryInterface;
use App\Domain\Repository\PasswordUserRequestRepositoryInterface;
use App\Domain\Repository\UserRepositoryInterface;

class PasswordUserRequestService implements PasswordUserRequestServiceInterface
{
    private PasswordUserRequestFactoryInterface $passwordUserRequestFactory;

    private PasswordUserRequestRepositoryInterface $passwordUserRequestRepository;

    private UserRepositoryInterface $userRepository;

    private EventDispatcherInterface $eventDispatcher;

    public function __construct(
        PasswordUserRequestFactoryInterface $passwordUserRequestFactory,
        PasswordUserRequestRepositoryInterface $passwordUserRequestRepository,
        UserRepositoryInterface $userRepository,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->passwordUserRequestFactory = $passwordUserRequestFactory;
        $this->passwordUserRequestRepository = $passwordUserRequestRepository;
        $this->userRepository = $userRepository;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function remindPassword(Email $email): void
    {
        $user = $this->userRepository->getByEmail($email);
        $passwordUserRequest = $this->passwordUserRequestFactory->create($user->getId());
        $this->passwordUserRequestRepository->save([$passwordUserRequest]);
        $this->eventDispatcher->dispatchAll($passwordUserRequest->releaseEvents());
    }
}
