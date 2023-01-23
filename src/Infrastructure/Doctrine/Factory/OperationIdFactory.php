<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Factory;

use App\Domain\Entity\ValueObject\Id;
use App\Domain\Service\DatetimeServiceInterface;
use App\Infrastructure\Doctrine\Entity\OperationId;

class OperationIdFactory
{
    public function __construct(private readonly DatetimeServiceInterface $datetimeService)
    {
    }

    public function create(Id $id): OperationId
    {
        return new OperationId($id, $this->datetimeService->getCurrentDatetime());
    }
}
