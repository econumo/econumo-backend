<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\UI\Service;

use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\UI\Service\Dto\OperationDto;

interface OperationServiceInterface
{
    public function lock(Id $id): OperationDto;

    public function release(OperationDto $dto): void;
}
