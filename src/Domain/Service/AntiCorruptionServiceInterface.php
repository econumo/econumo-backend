<?php

declare(strict_types=1);


namespace App\Domain\Service;


interface AntiCorruptionServiceInterface
{
    public function beginTransaction(): void;

    public function commit(): void;

    public function rollback(): void;
}
