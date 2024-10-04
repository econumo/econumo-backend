<?php

declare(strict_types=1);

namespace App\Domain\Service\Budget\Dto;

use App\Domain\Entity\ValueObject\BudgetEntityType;
use App\Domain\Entity\ValueObject\Id;

readonly class BudgetEntityAmountDto
{
    public function __construct(
        public Id $entityId,
        public BudgetEntityType $entityType,
        public ?Id $tagId,
        public ?float $budgeted,
        public float $budgetedBefore,
        /** @var BudgetEntityAmountSpentDto[] */
        public array $currenciesSpent = [],
        /** @var BudgetEntityAmountSpentDto[] */
        public array $currenciesSpentBefore = [],
    ) {
    }
}
