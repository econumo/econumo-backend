<?php

declare(strict_types=1);

namespace App\UI\Service\Dto;

use App\Domain\Entity\ValueObject\Id;
use Symfony\Component\Lock\LockInterface;

class OperationDto
{
    public Id $operationId;

    public ?LockInterface $lock = null;
}
