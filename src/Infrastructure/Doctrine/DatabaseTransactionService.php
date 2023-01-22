<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine;

use App\Domain\Service\AntiCorruptionServiceInterface;
use Doctrine\ORM\EntityManagerInterface;

class DatabaseTransactionService implements AntiCorruptionServiceInterface
{
    private EntityManagerInterface $entityManager;

    private bool $started = false;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function beginTransaction(): void
    {
        if ($this->started) {
            return;
        }

        $this->entityManager->beginTransaction();
        $this->started = true;
    }

    public function commit(): void
    {
        $this->entityManager->commit();
        $this->started = false;
    }

    public function rollback(): void
    {
        $this->entityManager->rollback();
        $this->started = false;
    }
}
