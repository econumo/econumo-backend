<?php

declare(strict_types=1);

namespace App\EconumoBundle\Command;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class EnableWALCommand extends Command
{
    protected static $defaultName = 'app:enable-wal';

    protected static $defaultDescription = 'Enable WAL-mode for SQLite';

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
        parent::__construct(self::$defaultName);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $connection = $this->entityManager->getConnection();
        $connection->executeStatement('PRAGMA journal_mode = WAL;');

        $io->success('WAL mode is enabled for SQLite');

        return Command::SUCCESS;
    }
}
