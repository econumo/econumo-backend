<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Infrastructure\Doctrine\Factory;

use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Service\DatetimeServiceInterface;
use App\EconumoOneBundle\Infrastructure\Doctrine\Entity\OperationId;

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
