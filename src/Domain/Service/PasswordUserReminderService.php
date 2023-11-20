<?php

declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Entity\ValueObject\Email;
use App\Domain\Entity\ValueObject\UserPasswordRequestCode;
use App\Domain\Exception\UserPasswordRequestExpiredException;
use App\Domain\Factory\UserPasswordRequestFactory;
use App\Domain\Factory\TemporaryUserPasswordFactoryInterface;
use App\Domain\Repository\UserPasswordRequestRepositoryInterface;
use App\Domain\Repository\TemporaryUserPasswordRepositoryInterface;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\Service\User\UserPasswordServiceInterface;
use Throwable;

readonly class PasswordUserReminderService implements PasswordUserReminderServiceInterface
{
    public function __construct(
        private UserPasswordRequestFactory $userPasswordRequestFactory,
        private UserPasswordRequestRepositoryInterface $userPasswordRequestRepository,
        private UserRepositoryInterface $userRepository,
        private EventDispatcherInterface $eventDispatcher,
        private AntiCorruptionServiceInterface $antiCorruptionService,
        private UserPasswordServiceInterface $userPasswordService
    ) {
    }

    public function remindPassword(Email $email): void
    {
        $user = $this->userRepository->getByEmail($email);
        $this->antiCorruptionService->beginTransaction(__METHOD__);
        try {
            $this->userPasswordRequestRepository->removeUserCodes($user->getId());
            $userPasswordRequest = $this->userPasswordRequestFactory->create($user->getId());
            $this->userPasswordRequestRepository->save([$userPasswordRequest]);
            $this->eventDispatcher->dispatchAll($userPasswordRequest->releaseEvents());

            $this->antiCorruptionService->commit(__METHOD__);
        } catch (Throwable $e) {
            $this->antiCorruptionService->rollback(__METHOD__);
            throw $e;
        }
    }

    public function resetPassword(Email $email, UserPasswordRequestCode $code, string $password): void
    {
        $user = $this->userRepository->getByEmail($email);
        $this->antiCorruptionService->beginTransaction(__METHOD__);
        try {
            $userPasswordRequest = $this->userPasswordRequestRepository->getByUserAndCode($user->getId(), $code);
            if ($userPasswordRequest->isExpired()) {
                throw new UserPasswordRequestExpiredException();
            }

            $this->userPasswordService->updatePassword($user->getId(), $password);
            $this->userPasswordRequestRepository->delete($userPasswordRequest);
            $this->antiCorruptionService->commit(__METHOD__);
        } catch (Throwable $e) {
            $this->antiCorruptionService->rollback(__METHOD__);
            throw $e;
        }
    }
}
