<?php

declare(strict_types=1);


namespace App\EconumoFamilyBundle\Application\UseCase\ConnectUsers;


use App\EconumoBundle\Domain\Events\UserRegisteredEvent;
use App\EconumoBundle\Domain\Repository\UserRepositoryInterface;
use App\EconumoBundle\Domain\Service\EventHandlerInterface;

readonly class ConnectUsersEventHandler implements EventHandlerInterface
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {
    }

    public function __invoke(UserRegisteredEvent $event): void
    {
        $users = $this->userRepository->getAll();
        $newUser = $this->userRepository->get($event->getUserId());
        $toSave = [
            $newUser->getId()->getValue() => $newUser
        ];
        foreach ($users as $user) {
            if ($user->getId()->isEqual($event->getUserId())) {
                continue;
            }

            $user->connectUser($newUser);
            $newUser->connectUser($user);
            $toSave[$user->getId()->getValue()] = $user;
        }

        $this->userRepository->save($toSave);
    }
}
