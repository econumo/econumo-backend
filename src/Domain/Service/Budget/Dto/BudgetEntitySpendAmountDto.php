<?php

declare(strict_types=1);

namespace App\Domain\Service\Budget\Dto;

use App\Domain\Entity\ValueObject\BudgetEntityType;
use App\Domain\Entity\ValueObject\Id;

readonly class BudgetEntitySpendAmountDto
{
    public function __construct(
        public Id $entityId,
        public BudgetEntityType $entityType,
        public Id $currencyId,
        public float $amount,
    ) {
    }
}