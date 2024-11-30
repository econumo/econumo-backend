<?php

declare(strict_types=1);

namespace App\EconumoBundle\Command;

use App\EconumoBundle\Infrastructure\Doctrine\Repository\UserRepository;
use DateTimeImmutable;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class DeactivateUsersCommand extends Command
{
    protected static $defaultName = 'app:deactivate-users';

    protected static $defaultDescription = 'Deactivate users';

    public function __construct(
        private readonly UserRepository $userRepository
    ) {
        parent::__construct(self::$defaultName);
    }

    protected function configure(): void
    {
        $this->addOption('date', 'd', InputOption::VALUE_REQUIRED, 'Older than a date');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $date = new DateTimeImmutable(trim((string)$input->getOption('date')));

        $users = $this->userRepository->getAll();
        $deactivatedUsers = [];
        foreach ($users as $user) {
            if ($user->getCreatedAt() < $date) {
                $user->deactivate();
                $deactivatedUsers[] = $user;
            }
        }

        if ($deactivatedUsers !== []) {
            $this->userRepository->save($deactivatedUsers);
        }

        return Command::SUCCESS;
    }
}