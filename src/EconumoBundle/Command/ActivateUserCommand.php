<?php

declare(strict_types=1);

namespace App\EconumoBundle\Command;

use App\EconumoBundle\Domain\Entity\ValueObject\Email;
use App\EconumoBundle\Domain\Repository\UserRepositoryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ActivateUserCommand extends Command
{
    protected static $defaultName = 'app:activate-user';

    protected static $defaultDescription = 'Activate a user';

    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) {
        parent::__construct(self::$defaultName);
    }


    protected function configure(): void
    {
        $this->addArgument('email', InputArgument::REQUIRED, 'E-mail');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $email = trim((string)$input->getArgument('email'));

        $user = $this->userRepository->getByEmail(new Email($email));
        $user->activate();

        $this->userRepository->save([$user]);

        $io->success(sprintf('User %s successfully activated! (id: %s)', $email, $user->getId()));

        return Command::SUCCESS;
    }
}
