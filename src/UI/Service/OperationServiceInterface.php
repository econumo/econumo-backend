<?php

declare(strict_types=1);

namespace App\UI\Service;

use App\Domain\Entity\ValueObject\Id;
use App\UI\Service\Dto\OperationDto;

interface OperationServiceInterface
{
    public function lock(Id $id): OperationDto;

    public function release(OperationDto $dto): void;
}
