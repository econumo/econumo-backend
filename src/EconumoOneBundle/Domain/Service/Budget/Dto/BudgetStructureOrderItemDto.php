<?php

declare(strict_types=1);


namespace App\EconumoOneBundle\Domain\Service\Budget\Dto;

use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;

readonly class BudgetStructureOrderItemDto
{
    public function __construct(
        public Id $id,
        public int $position,
    ) {
    }
}
