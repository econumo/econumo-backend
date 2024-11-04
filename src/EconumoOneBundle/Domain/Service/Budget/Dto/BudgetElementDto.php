<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Domain\Service\Budget\Dto;

use App\EconumoOneBundle\Domain\Entity\ValueObject\BudgetElementType;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;

readonly class BudgetElementDto
{
    public function __construct(
        public Id $elementId,
        public BudgetElementType $elementType,
        public ?Id $currencyId,
        public ?Id $folderId,
        public ?int $position,
    ) {
    }
}