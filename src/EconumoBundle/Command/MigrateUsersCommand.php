<?php

declare(strict_types=1);

namespace App\EconumoBundle\Command;

use App\EconumoBundle\Domain\Entity\ValueObject\Email;
use App\EconumoBundle\Domain\Factory\UserFactoryInterface;
use App\EconumoBundle\Domain\Repository\UserRepositoryInterface;
use App\EconumoBundle\Domain\Service\EncodeServiceInterface;
use App\EconumoBundle\Infrastructure\Doctrine\Repository\UserRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

class MigrateUsersCommand extends Command
{
    protected static $defaultName = 'app:migrate-users';

    protected static $defaultDescription = 'Migrate users';

    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly EncodeServiceInterface $encodeService,
    ) {
        parent::__construct(self::$defaultName);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $users = $this->userRepository->findAll();
        foreach ($users as $user) {
            $avatarUrl = sprintf('https://www.gravatar.com/avatar/%s', md5($user->getEmail()));
            $user->updateAvatarUrl($avatarUrl);

            $originalEmail = $user->getEmail();
            $encodedEmail = $this->encodeService->encode($originalEmail);
            $user->updateEmail($encodedEmail);

            $identifier = $this->encodeService->hash($originalEmail);
            $user->updateUserIdentifier($identifier);
        }
        $this->userRepository->save($users);

        return Command::SUCCESS;
    }
}
