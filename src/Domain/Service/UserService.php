<?php

declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Entity\User;
use App\Domain\Entity\UserOption;
use App\Domain\Entity\ValueObject\CurrencyCode;
use App\Domain\Entity\ValueObject\Email;
use App\Domain\Entity\ValueObject\FolderName;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Exception\NotFoundException;
use App\Domain\Exception\UserRegisteredException;
use App\Domain\Factory\ConnectionInviteFactoryInterface;
use App\Domain\Factory\FolderFactoryInterface;
use App\Domain\Factory\UserFactoryInterface;
use App\Domain\Factory\UserOptionFactoryInterface;
use App\Domain\Repository\ConnectionInviteRepositoryInterface;
use App\Domain\Repository\FolderRepositoryInterface;
use App\Domain\Repository\UserOptionRepositoryInterface;
use App\Domain\Repository\UserRepositoryInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class UserService implements UserServiceInterface
{
    private UserFactoryInterface $userFactory;
    private UserRepositoryInterface $userRepository;
    private EventDispatcherInterface $eventDispatcher;
    private FolderFactoryInterface $folderFactory;
    private FolderRepositoryInterface $folderRepository;
    private AntiCorruptionServiceInterface $antiCorruptionService;
    private TranslatorInterface $translator;
    private ConnectionInviteFactoryInterface $connectionInviteFactory;
    private ConnectionInviteRepositoryInterface $connectionInviteRepository;
    private UserOptionFactoryInterface $userOptionFactory;
    private UserOptionRepositoryInterface $userOptionRepository;

    public function __construct(
        UserFactoryInterface $userFactory,
        UserRepositoryInterface $userRepository,
        EventDispatcherInterface $eventDispatcher,
        FolderFactoryInterface $folderFactory,
        FolderRepositoryInterface $folderRepository,
        AntiCorruptionServiceInterface $antiCorruptionService,
        TranslatorInterface $translator,
        ConnectionInviteFactoryInterface $connectionInviteFactory,
        ConnectionInviteRepositoryInterface $connectionInviteRepository,
        UserOptionFactoryInterface $userOptionFactory,
        UserOptionRepositoryInterface $userOptionRepository
    ) {
        $this->userFactory = $userFactory;
        $this->userRepository = $userRepository;
        $this->eventDispatcher = $eventDispatcher;
        $this->folderFactory = $folderFactory;
        $this->folderRepository = $folderRepository;
        $this->antiCorruptionService = $antiCorruptionService;
        $this->translator = $translator;
        $this->connectionInviteFactory = $connectionInviteFactory;
        $this->connectionInviteRepository = $connectionInviteRepository;
        $this->userOptionFactory = $userOptionFactory;
        $this->userOptionRepository = $userOptionRepository;
    }

    public function register(Email $email, string $password, string $name): User
    {
        try {
            $this->userRepository->getByEmail($email);
            throw new UserRegisteredException();
        } catch (NotFoundException $exception) {
        }

        $this->antiCorruptionService->beginTransaction();
        try {
            $user = $this->userFactory->create($name, $email, $password);
            $this->userRepository->save($user);

            $folder = $this->folderFactory->create($user->getId(), new FolderName($this->translator->trans('account.folder.all_accounts')));
            $this->folderRepository->save($folder);

            $connectionInvite = $this->connectionInviteFactory->create($user);
            $this->connectionInviteRepository->save($connectionInvite);

            $this->userOptionRepository->save(
                $this->userOptionFactory->create($user, UserOption::CURRENCY, UserOption::DEFAULT_CURRENCY),
                $this->userOptionFactory->create($user, UserOption::REPORT_DAY, UserOption::DEFAULT_REPORT_DAY)
            );

            $this->antiCorruptionService->commit();
        } catch (\Throwable $exception) {
            $this->antiCorruptionService->rollback();
            throw $exception;
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
        $this->userRepository->save($user);
    }

    public function updateCurrency(Id $userId, CurrencyCode $currencyCode): void
    {
        $user = $this->userRepository->get($userId);
        $this->userOptionRepository->save(
            $this->userOptionFactory->create($user, UserOption::CURRENCY, $currencyCode->getValue())
        );
    }
}
