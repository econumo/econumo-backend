<?php

declare(strict_types=1);

namespace App\EconumoBundle\Domain\Service;

use App\EconumoBundle\Domain\Entity\User;
use App\EconumoBundle\Domain\Entity\UserOption;
use App\EconumoBundle\Domain\Entity\ValueObject\CurrencyCode;
use App\EconumoBundle\Domain\Entity\ValueObject\Email;
use App\EconumoBundle\Domain\Entity\ValueObject\FolderName;
use App\EconumoBundle\Domain\Entity\ValueObject\Id;
use App\EconumoBundle\Domain\Entity\ValueObject\ReportPeriod;
use App\EconumoBundle\Domain\Exception\NotFoundException;
use App\EconumoBundle\Domain\Exception\UserRegisteredException;
use App\EconumoBundle\Domain\Exception\UserRegistrationDisabledException;
use App\EconumoBundle\Domain\Factory\ConnectionInviteFactoryInterface;
use App\EconumoBundle\Domain\Factory\FolderFactoryInterface;
use App\EconumoBundle\Domain\Factory\UserFactoryInterface;
use App\EconumoBundle\Domain\Factory\UserOptionFactoryInterface;
use App\EconumoBundle\Domain\Repository\ConnectionInviteRepositoryInterface;
use App\EconumoBundle\Domain\Repository\FolderRepositoryInterface;
use App\EconumoBundle\Domain\Repository\UserOptionRepositoryInterface;
use App\EconumoBundle\Domain\Repository\UserRepositoryInterface;
use App\EconumoBundle\Domain\Service\AntiCorruptionServiceInterface;
use App\EconumoBundle\Domain\Service\EventDispatcherInterface;
use App\EconumoBundle\Domain\Service\Translation\TranslationServiceInterface;
use App\EconumoBundle\Domain\Service\User\UserRegistrationServiceInterface;
use App\EconumoBundle\Domain\Service\UserServiceInterface;

readonly class UserService implements UserServiceInterface
{
    public function __construct(
        private UserFactoryInterface $userFactory,
        private UserRepositoryInterface $userRepository,
        private EventDispatcherInterface $eventDispatcher,
        private FolderFactoryInterface $folderFactory,
        private FolderRepositoryInterface $folderRepository,
        private AntiCorruptionServiceInterface $antiCorruptionService,
        private TranslationServiceInterface $translator,
        private ConnectionInviteFactoryInterface $connectionInviteFactory,
        private ConnectionInviteRepositoryInterface $connectionInviteRepository,
        private UserOptionFactoryInterface $userOptionFactory,
        private UserOptionRepositoryInterface $userOptionRepository,
        private UserRegistrationServiceInterface $userRegistrationService
    )
    {
    }

    public function register(Email $email, string $password, string $name): User
    {
        if (!$this->userRegistrationService->isRegistrationAllowed()) {
            throw new UserRegistrationDisabledException();
        }

        try {
            $this->userRepository->getByEmail($email);
            throw new UserRegisteredException();
        } catch (NotFoundException) {
        }

        $this->antiCorruptionService->beginTransaction(__METHOD__);
        try {
            $user = $this->userFactory->create($name, $email, $password);
            $this->userRepository->save([$user]);

            $folder = $this->folderFactory->create($user->getId(), new FolderName($this->translator->trans('account.folder.all_accounts')));
            $this->folderRepository->save([$folder]);

            $connectionInvite = $this->connectionInviteFactory->create($user);
            $this->connectionInviteRepository->save([$connectionInvite]);

            $this->userOptionRepository->save(
                [
                    $this->userOptionFactory->create($user, UserOption::CURRENCY, UserOption::DEFAULT_CURRENCY),
                    $this->userOptionFactory->create($user, UserOption::REPORT_PERIOD, UserOption::DEFAULT_REPORT_PERIOD),
                    $this->userOptionFactory->create($user, UserOption::DEFAULT_BUDGET, null)
                ]
            );

            $this->antiCorruptionService->commit(__METHOD__);
        } catch (\Throwable $throwable) {
            $this->antiCorruptionService->rollback(__METHOD__);
            throw $throwable;
        }

        $this->eventDispatcher->dispatchAll($user->releaseEvents());
        // do not send first folder creation event
//        $this->eventDispatcher->dispatchAll($folder->releaseEvents());

        return $user;
    }

    public function updateName(Id $userId, string $name): void
    {
        $user = $this->userRepository->get($userId);
        $user->updateName($name);

        $this->userRepository->save([$user]);
    }

    public function updateCurrency(Id $userId, CurrencyCode $currencyCode): void
    {
        $this->antiCorruptionService->beginTransaction(__METHOD__);
        try {
            $user = $this->userRepository->get($userId);
            $user->updateCurrency($currencyCode);
            $this->userRepository->save([$user]);
            $this->antiCorruptionService->commit(__METHOD__);
        } catch (\Throwable $throwable) {
            $this->antiCorruptionService->rollback(__METHOD__);
            throw $throwable;
        }
    }

    public function updateReportPeriod(Id $userId, ReportPeriod $reportPeriod): void
    {
        $this->antiCorruptionService->beginTransaction(__METHOD__);
        try {
            $user = $this->userRepository->get($userId);
            $user->updateReportPeriod($reportPeriod);
            $this->userRepository->save([$user]);
            $this->antiCorruptionService->commit(__METHOD__);
        } catch (\Throwable $throwable) {
            $this->antiCorruptionService->rollback(__METHOD__);
            throw $throwable;
        }
    }

    public function updateDefaultBudget(Id $userId, Id $budgetId): void
    {
        $this->antiCorruptionService->beginTransaction(__METHOD__);
        try {
            $user = $this->userRepository->get($userId);
            $user->updateDefaultBudget($budgetId);
            $this->userRepository->save([$user]);
            $this->antiCorruptionService->commit(__METHOD__);
        } catch (\Throwable $throwable) {
            $this->antiCorruptionService->rollback(__METHOD__);
            throw $throwable;
        }
    }
}
