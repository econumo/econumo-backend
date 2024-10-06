<?php

declare(strict_types=1);

namespace App\EconumoBundle\Domain\Service\Budget\Dto;

use App\EconumoBundle\Domain\Entity\ValueObject\BudgetEntityType;
use App\EconumoBundle\Domain\Entity\ValueObject\Id;

readonly class BudgetEntityOptionDto
{
    public function __construct(
        public Id $entityId,
        public BudgetEntityType $entityType,
        public ?Id $currencyId,
        public ?Id $folderId,
        public ?int $position,
    ) {
    }
}