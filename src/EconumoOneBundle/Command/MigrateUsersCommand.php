<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Command;

use App\EconumoOneBundle\Domain\Entity\ValueObject\Email;
use App\EconumoOneBundle\Domain\Factory\UserFactoryInterface;
use App\EconumoOneBundle\Domain\Repository\UserRepositoryInterface;
use App\EconumoOneBundle\Domain\Service\EncodeServiceInterface;
use App\EconumoOneBundle\Infrastructure\Doctrine\Repository\UserRepository;
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

            $encodedEmail = $this->encodeService->encode($user->getEmail());
            $user->updateEmail($encodedEmail);

            $identifier = $this->encodeService->hash($user->getEmail());
            $user->updateUserIdentifier($identifier);
        }
        $this->userRepository->save($users);

        return Command::SUCCESS;
    }
}
