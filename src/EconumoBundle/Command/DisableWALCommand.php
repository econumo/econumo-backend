<?php

declare(strict_types=1);

namespace App\EconumoBundle\Command;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class DisableWALCommand extends Command
{
    protected static $defaultName = 'app:disable-wal';

    protected static $defaultDescription = 'Disable WAL-mode for SQLite';

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
        parent::__construct(self::$defaultName);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $connection = $this->entityManager->getConnection();
        $connection->executeStatement('PRAGMA journal_mode = delete;');

        $io->success('WAL mode is disabled for SQLite');

        return Command::SUCCESS;
    }
}
